<?php

namespace App\Http\Services;

use App\Models\TelegramUserChat;
use App\Models\WasteCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * *Available session is describe bellow
 * *current_chat_theme -> current selected theme
 * *current_chat -> Current chat
 * *current_waste_step -> Current waste theme step. This step define in waste_step() function
 * 
 */

class TelegramService {
    const URL = 'https://api.telegram.org/bot';
    const SEND_MESSAGE = 'sendMessage';
    const SET_COMMANDS = 'setMyCommands';
    const SEND_ACTION = 'sendChatAction';
    const CHAT_THEME_WASTE = 'limbah_theme';
    const CHAT_THEME_HRD = 'hrd_theme';

    public function url()
    {
        return self::URL . env('TELEGRAM_BOT_TOKEN') . '/' . self::SEND_MESSAGE;
    }

    public function urlAction()
    {
        return self::URL . env('TELEGRAM_BOT_TOKEN') . '/' . self::SEND_ACTION;
    }

    public function sendAction($chat_id, $type = 'typing')
    {
        Http::post($this->urlAction(), ['chat_id' => $chat_id, 'action' => $type]);
    }

    public function serviceChat()
    {
        return new ChatService();
    }

    public function waste_steps()
    {
        return [
            'init_chat_theme',
            'waste_code_list_and_waste_action_option',
            'waste_action'
        ];
    }

    /**
     * Function to start a conversation
     * Usually user start with command '/start'
     * 
     * @param array payload
     * 
     * @return void
     */
    public function start_chat($payload)
    {
        $payload['text'] = 'Hai, selamat datang di Layanan Digital MPS Brondong';
        $send = Http::post($this->url(), $payload);

        if (!empty($send['ok'])) {

            $payload['text'] = 'Kamu bisa panggil aku CigarBot.';
            $send_1 = Http::post($this->url(), $payload);

            if (!empty($send_1['ok'])) {

                $payload['text'] = 'Klik tombol dibawah sesuai kebutuhanmu ya, aku akan membantu dengan senang hati :)';
                $payload['reply_markup'] = [
                    'inline_keyboard' => [
                        [
                            [
                                'text' => 'Limbah',
                                'callback_data' => self::CHAT_THEME_WASTE
                            ],
                            [
                                'text' => 'HRD',
                                'callback_data' => self::CHAT_THEME_HRD
                            ],
                        ]
                    ],
                    'resize_keyboard' => true
                ];
                Http::post($this->url(), $payload);

            }

        }
    }

    public function init_chat_with_theme($theme, $payload)
    {
        if ($theme == self::CHAT_THEME_WASTE) {
            session(['current_chat_theme' => $theme]);
            TelegramUserChat::insert([
                'room_id' => $payload['chat_id'],
                'theme' => $theme,
                'message' => 'init chat theme',
                'supposed_to_send' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            session(['current_waste_step' => 1]);
            $this->chat_waste_by_step(1, $payload);
        } else if ($theme == self::CHAT_THEME_HRD) {
            $this->under_development_chat($payload);
        }
    }

    /**
     * Function to send information to user
     * If requested theme is under development mode
     * 
     * @param array payload
     * 
     * @return void
     */
    public function under_development_chat($payload)
    {
        $payload['text'] = 'Mohon maaf layanan ini masih dalam tahap pengembangan, harap bersabar yaa :)';
        Http::post($this->url(), $payload);
    }

    public function chat_waste_by_step($step, $payload, $message = null)
    {
        $step = $this->waste_steps()[$step];
        if ($step == 'waste_code_list_and_waste_action_option') {

            $waste_code = WasteCode::all();
            $payload['text'] = "Baik, aku akan membantumu untuk mengetahui lebih dalam tentang limbah. \n";
            $payload['text'] .= "Berikut adalah list kode limbah yang ada di MPS Brondong \n";
            foreach ($waste_code as $k => $c) {
                $payload['text'] .= ($k + 1) . ". " . $c->code . " : " . $c->description . " \n";
            }
            $payload['text'] .= "Pilih salah satu tombol di bawah ya. \n";
            Http::post($this->url(), $payload);

            $payload['text'] = "Jika kamu ingin keluar dari tema limbah ini, kamu bisa tekan tombol 'keluar' \n";
            $payload['reply_markup'] = [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Input Limbah Datang',
                            'callback_data' => 'input_limbah_datang'
                        ]
                    ],
                    [
                        [
                            'text' => 'Input Limbah Keluar',
                            'callback_data' => 'input_limbah_keluar'
                        ]
                    ],
                    [
                        [
                            'text' => 'List Limbah',
                            'callback_data' => 'list_limbah'
                        ]
                    ],
                    [
                        [
                            'text' => 'keluar',
                            'callback_data' => 'out_of_theme'
                        ]
                    ],
                ],
                'resize_keyboard' => true
            ];
            Http::post($this->url(), $payload);
            TelegramUserChat::where('room_id', $payload['chat_id'])
                ->update([
                    'supposed_to_send' => 2,
                    'updated_at' => Carbon::now(),
                    'message' => self::CHAT_THEME_WASTE
                ]);

        } else if ($step == 'waste_action') {

            if ($message == 'input_limbah_datang') {
                $this->chat_incoming_waste($payload);
            }

        }
    }

    public function conversation_by_chat($payload, $current_step, $chat_from_user)
    {
        $next_step = $current_step + 1;
        $this->chat_waste_by_step($next_step, $payload, $chat_from_user);
    }

    public function chat_incoming_waste($payload)
    {
        $inside_step = [
            'choose_waste_code',
            'input_waste_detail',
            'input_waste_qty',
            'finish'
        ];

        $current_inside_step = session('current_inside_step');
        if (!$current_inside_step) {
            // send first step of this waste theme
            $this->send_waste_code_list($payload);
        }
    }

    public function send_waste_code_list($payload)
    {
        $this->sendAction($payload['chat_id']);

        $waste_code = WasteCode::all();
        $payload['text'] = "Silahkan pilih kode limbah dulu ya";
        $textMarkup = [];
        foreach ($waste_code as $c) {
            $textMarkup[] = [
                [
                    'text' => $c->code,
                    'callback_data' => $c->code . '@type-type'
                ]
            ];
        }
        $payload['reply_markup'] = [
            'inline_keyboard' => $textMarkup,
            'resize_keyboard' => true
        ];
        Http::post($this->url(), $payload);
    }
    
}