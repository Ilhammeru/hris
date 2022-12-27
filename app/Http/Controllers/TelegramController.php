<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Employee\Http\Services\LeavePermissionService;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    const URL = 'https://api.telegram.org/bot';
    const SEND_MESSAGE = 'sendMessage';
    const SET_COMMANDS = 'setMyCommands';

    public function webhook(Request $request): JsonResponse
    {
        try {
            $content = $request->getContent();
            $item = json_decode($content, true);
            Log::debug($content);
            
            $url = self::URL . env('TELEGRAM_BOT_TOKEN') . '/' . self::SEND_MESSAGE;
            $chat_id = $item['message']['chat']['id'];
            if (!empty($item['message']['text'])) {
                $message = $item['message']['text'];
                $res_message = [
                    'chat_id' => $chat_id,
                    'text' => ''
                ];
    
                // greeting
                if ($message == '/start') {
                    $res_message['text'] = 'Hai, selamat datang di Layanan Digital MPS Brondong';
                    $send = Http::post($url, $res_message);
                    Log::debug($send);
                    if (!empty($send['ok'])) {
                        $res_message['text'] = 'Kamu bisa panggil aku CigarBot.';
                        $send_1 = Http::post($url, $res_message);
                        if (!empty($send_1['ok'])) {
                            $res_message['text'] = 'Klik tombol dibawah sesuai kebutuhanmu ya, aku akan membantu dengan senang hati :)';
                            $res_message['reply_markup'] = [
                                'inline_keyboard' => [
                                    [
                                        [
                                            'text' => 'Limbah'
                                        ],
                                        [
                                            'text' => 'HRD'
                                        ]
                                    ]
                                ],
                                'one_time_keyboard' => true
                            ];
                            $send_2 = Http::post($url, $res_message);
                        }
                    }
                }
            }
            
            return response()->json($content);
        } catch (\Throwable $th) {
            Log::error([
                'line' => $th->getLine(),
                'file' => $th->getFile(),
                'message' => $th->getMessage()
            ]);
        }
    }
}
