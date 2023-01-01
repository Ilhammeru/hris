<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class TelegramService {
    const URL = 'https://api.telegram.org/bot';
    const SEND_MESSAGE = 'sendMessage';
    const SET_COMMANDS = 'setMyCommands';

    public function url()
    {
        return self::URL . env('TELEGRAM_BOT_TOKEN') . '/' . self::SEND_MESSAGE;
    }

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
                                'callback_data' => 'limbah_theme'
                            ],
                            [
                                'text' => 'HRD',
                                'callback_data' => 'hrd_theme'
                            ],
                        ]
                    ],
                    'resize_keyboard' => true
                ];
                Http::post($this->url(), $payload);
                
            }

        }
    }
}