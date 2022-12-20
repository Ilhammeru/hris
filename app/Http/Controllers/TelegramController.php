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
                                'keyboard' => [
                                    [
                                        [
                                            'text' => 'Izin Keluar'
                                        ],
                                        [
                                            'text' => 'Izin Cuti Haid'
                                        ]
                                    ],
                                    [
                                        [
                                            'text' => 'Form Input Karyawan'
                                        ]
                                    ]
                                ],
                                'one_time_keyboard' => true
                            ];
                            $send_2 = Http::post($url, $res_message);
                        }
                    }
                } else if ($message == 'Izin Keluar') {
                    // Izin Keluar
                    $res_message['text'] = 'Oke saya akan membuatkan surat izin keluar.';
                    $send = Http::post($url, $res_message);
                    if (!empty($send['ok'])) {
                        $res_message['text'] = 'Tapi sebelumnya, beritahu aku nama mu. Ketik dan kirim nama lengkap mu ya';
                        $send_1 = Http::post($url, $res_message);
                    }
                } else if ($message == 'Izin Cuti Haid') {
                    $res_message['text'] = 'Mohon maaf ya, layanan ini masih dalam perbaikan.';
                    Http::post($url, $res_message);
                } else if ($message == 'Form Input Karyawan') {
                    $res_message['text'] = 'Mohon maaf ya, layanan ini masih dalam perbaikan.';
                    Http::post($url, $res_message);
                } else {
                    if (
                        strtolower($message) == 'rany' ||
                        strtolower($message) == 'desy' ||
                        strtolower($message) == 'rany desy kurniasari'
                    ) {
                        $res_message['text'] = 'Hai Rany, sebelum membuat keperluan mu, aku ingin menyampaikan pesan dari pembuatku';
                        $send = Http::post($url, $res_message);
                        if (!empty($send['ok'])) {
                            $res_message['text'] = 'ILOVEYOU TO THE MOON TO THE MARS TO ANYWHERE. Maaf yaa sudah marah kemarin ke kamu. ILOVE YOU';
                            $send_1 = Http::post($url, $res_message);
                            if (!empty($send_1['ok'])) {
                                $res_message['text'] = 'Itu pesan yg disampaikan ke aku :) Sepertinya dia sangat mencintaimu :D';
                                $send_2 = Http::post($url, $res_message);
                                if (!empty($send_2['ok'])) {
                                    $res_message['text'] = "Ok. sekarang saatnya bekerja, aku akan membuatkan surat izin keluar untukmu. Silahkan kirim aku pesan sesuai dengan format ini ya \n\n";
                                    $res_message['text'] .= "Nama: nama pekerja \n";
                                    $res_message['text'] .= "Bagian: bagian pekerja \n";
                                    $res_message['text'] .= "Waktu: jam / waktu pekerja keluar kantor \n";
                                    $res_message['text'] .= "Keperluan: Alasan pekerja keluar \n\n";
                            $res_message['text'] .= "Perlu di perhatikan bahwa penulisan waktu tidak boleh menggunakan '<b>:</b>'. Penulisan waktu harus seperti <b>08.00</b>";
                                    $res_message['parse_mode'] = 'HTML';
                                    $send_3 = Http::post($url, $res_message);
                                }
                            }
                        }
                    } else {
                        $spl_message = explode("\n", $message);
                        Log::debug($spl_message);
                        if (count($spl_message) == 4) {
                            $service = new LeavePermissionService();
                            $url_action = $service->create_leave_permission($spl_message);
                            $res_message['text'] = "Permintaan izin keluar sudah dibuat ya \n";
                            $res_message['text'] .= '<a href="'. $url_action .'">Print disini ya</a>';
                            $res_message['parse_mode'] = "HTML";
                            $send = Http::post($url, $res_message);
                            Log::debug(['end' => $send]);
                        } else {
                            $res_message['text'] = "Ok. Silahkan kirim aku pesan sesuai dengan format ini ya \n\n";
                            $res_message['text'] .= "Nama: nama pekerja \n";
                            $res_message['text'] .= "Bagian: bagian pekerja \n";
                            $res_message['text'] .= "Waktu: jam / waktu pekerja keluar kantor \n";
                            $res_message['text'] .= "Keperluan: Alasan pekerja keluar \n\n";
                            $res_message['text'] .= "Perlu di perhatikan bahwa penulisan waktu tidak boleh menggunakan '<b>:</b>'. Penulisan waktu harus seperti <b>08.00</b>";
                            $res_message['parse_mode'] = 'HTML';
                            Http::post($url, $res_message);
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
