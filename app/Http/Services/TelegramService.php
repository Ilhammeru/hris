<?php

namespace App\Http\Services;

use App\Models\TelegramUserChat;
use App\Models\WasteCode;
use App\Models\WasteLogIn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

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

    public function set_user_chat_theme_and_send_greeting_theme($payload, $msg)
    {
        /**
         * Validate the theme, if selected theme is under development,
         * Then send the rollback message
         */
        if ($msg == self::CHAT_THEME_HRD) {
            return $this->send_underdevelopment_chat($payload);
        }

        if ($msg == self::CHAT_THEME_WASTE) {
            $this->send_init_waste_chat($payload);
            Redis::set('user_chat_theme', self::CHAT_THEME_WASTE);
        }
    }

    /**
     * Function to send the rollback message to inform that selected theme is under development
     * 
     * @param array payload
     * 
     * @return void
     */
    public function send_underdevelopment_chat($payload)
    {
        $payload['text'] = 'Maaf yaa, untuk HRD masih dalam pengembangan, harap bersabar :)';
        Http::post($this->url(), $payload);
    }


    /******************************************************************************** BEGIN WASTE CHAT SECTION */
    public function waste_list_action()
    {
        return [
            'will_choose_action_theme',
            'will_choose_waste_code',
            'will_send_detail_waste',
            'will_send_qty',
            'will_finish'
        ];
    }

    public function send_init_waste_chat($payload)
    {
        $this->sendAction($payload['chat_id']);

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
        Redis::set('last_step_action', 0);
    }

    public function generate_message_based_on_last_action_of_waste($payload, $msg, $last_step_action)
    {
        $this->sendAction($payload['chat_id']);

        $posistion = array_search($last_step_action, $this->waste_list_action());
        $posistion = (int) $posistion + 1;
        $function_name = 'send_' . $this->waste_list_action()[$posistion] . '_chat';
        $this->$function_name($payload, $posistion);
    }

    /**
     * Function to send chat list of waste code
     * The purpose is user will be select on of them
     * and Process it!
     */
    public function send_will_choose_waste_code_chat($payload, $next_step)
    {
        $waste_code = WasteCode::all();
        $payload['text'] = "Silahkan pilih kode limbah dulu ya";
        $textMarkup = [];
        foreach ($waste_code as $k => $c) {
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
        Redis::set('last_step_action', $next_step);
    }
    /******************************************************************************** END WASTE CHAT SECTION */
    
}