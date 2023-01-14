<?php

namespace App\Http\Controllers;

use App\Http\Services\ChatService;
use App\Http\Services\TelegramService;
use App\Models\TelegramUserChat;
use App\Models\WasteCode;
use App\Models\WasteLog;
use Carbon\Carbon;
use CURLFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Modules\Employee\Http\Services\LeavePermissionService;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    const URL = 'https://api.telegram.org/bot';
    const SEND_MESSAGE = 'sendMessage';
    const SET_COMMANDS = 'setMyCommands';
    const DUMMY_FILE = './result_20230113214407.xlsx';

    public function webhook(Request $request)
    {
        try {

            $is_finish = false;
            $is_amount = false;
            $is_type = false;
            $chat_service = new ChatService();
            $tele_service = new TelegramService();

            $content = $request->getContent();
            $item = json_decode($content, true);
            Log::debug('item', ['data' => $item]);

            // get room id
            $room_id = null;
            $msg = null;
            if (!empty($item['callback_query'])) {
                $room_id = $item['callback_query']['from']['id'];
                $msg = $item['callback_query']['data'];
            } else if (!empty($item['message'])) {
                $room_id = $item['message']['from']['id'];
                $msg = $item['message']['text'];
            }

            $user_chat_theme = Redis::get('user_chat_theme');
            $user_theme_action = Redis::get('user_theme_action');
            $last_step_action = Redis::get('last_step_action');

            $res_message = [
                'chat_id' => $room_id,
                'text' => ''
            ];

            /**
             ** If last_step_action and user_chat_theme session is defined,
             ** Then stop the logic in here and
             ** RUN the conversation based on THEME and STEP
             * 
             ** If user chat theme session already define, 
             ** The last step action session is already defined to
             * 
             ** So Check the last step action position to decide for the next move
             */
            if ($last_step_action != null && $user_chat_theme != null) {
                if ($user_chat_theme == $tele_service::CHAT_THEME_WASTE) {
                    $tele_service->generate_message_based_on_last_action_of_waste($res_message, $msg, $last_step_action);
                    return;
                }
            }

            if (!empty($item['message'])) {

                if (!empty($item['message']['text'])) {

                    $message = $item['message']['text'];

                    /**
                     ** Start the chat with command /start
                     */
                    if ($message == '/start') {

                        $tele_service->start_chat($res_message);

                    } else {

                        $tele_service->send_format_failed($res_message);

                    }

                }

            } else if (!empty($item['callback_query'])) {

                if (!$user_chat_theme) {
                    /**
                     ** Set user chat theme, sent by $item['callback_query']['data'] key
                     */
                    $tele_service->set_user_chat_theme_and_send_greeting_theme($res_message, $msg);
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

    public function generate_report()
    {

        $data = WasteLog::with(['code', 'in'])
            ->get();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly(["PRINT"]);
        $spreadsheet = $reader->load("logbook.xlsx");
        $spreadsheet->getActiveSheet()->setCellValue('D7', '2023');
        
        $start_row = 12;
        foreach ($data as $key => $d) {
            $spreadsheet->getActiveSheet()->setCellValue('B'."$start_row", ($key + 1));
            $spreadsheet->getActiveSheet()->setCellValue('C'."$start_row", $d->code->code . ' (' . $d->in->waste_properties . ')');
            $spreadsheet->getActiveSheet()->setCellValue('D'."$start_row", date('d F Y', strtotime($d->in->date)));
            $spreadsheet->getActiveSheet()->setCellValue('E'."$start_row", $d->in->waste_source);
            $spreadsheet->getActiveSheet()->setCellValue('F'."$start_row", $d->in->qty);
            $spreadsheet->getActiveSheet()->setCellValue('G'."$start_row", date('d F Y', strtotime($d->in->exp)));
            
            /**
             ** Write a formula to calculate total waste day by day
             */
            if ($start_row == 12) {
                $spreadsheet->getActiveSheet()->setCellValue(
                    'L' . $start_row,
                    '=F' . $start_row
                );
            }

            if ($start_row != 12) {
                $spreadsheet->getActiveSheet()->setCellValue(
                    'L'. $start_row,
                    '=L' . ($start_row-1) . '+F' . $start_row . '-H' . $start_row
                );
            }

            $start_row++;
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("result.xlsx");
        echo 'okeoke';
    }
}
