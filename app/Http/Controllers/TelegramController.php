<?php

namespace App\Http\Controllers;

use App\Http\Services\ChatService;
use App\Http\Services\TelegramService;
use App\Models\TelegramUserChat;
use App\Models\WasteCode;
use Carbon\Carbon;
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
            $is_finish = false;
            $is_amount = false;
            $is_type = false;
            $chat_service = new ChatService();
            $tele_service = new TelegramService();

            $content = $request->getContent();
            $item = json_decode($content, true);
            Log::debug($item);
            
            $url = self::URL . env('TELEGRAM_BOT_TOKEN') . '/' . self::SEND_MESSAGE;

            // get room id
            $room_id = null;
            if (!empty($item['callback_query'])) {
                $room_id = $item['callback_query']['from']['id'];
            } else if (!empty($item['message'])) {
                $room_id = $item['message']['from']['id'];
            }

            $current_chat = session('current_chat');

            $res_message = [
                'chat_id' => $room_id,
                'text' => ''
            ];

            if (!empty($item['message'])) {
                $chat_id = $item['message']['chat']['id'];
                if (!empty($item['message']['text'])) {
                    $message = $item['message']['text'];

                    /**
                     * Start the chat with command /start
                     */
                    if ($message == '/start') {
                        $tele_service->start_chat($res_message);
                    }
                }
            } else if (!empty($item['callback_query'])) {
                $theme = $item['callback_query']['data'];
                
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

    /**
     * Function to get current chat theme
     * @param string room_id
     * 
     * @return string
     */
    public function user_chat_data($room_id)
    {
        $data = TelegramUserChat::select('theme', 'message')
            ->where('room_id', $room_id)
            ->orderBy('id', 'desc')
            ->first();

        return $data;
    }
}
