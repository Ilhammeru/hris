<?php

namespace App\Http\Services;

use App\Models\TelegramUserChat;
use App\Models\WasteCode;
use App\Models\WasteLog;
use App\Models\WasteLogIn;
use Carbon\Carbon;
use CURLFile;
use Illuminate\Support\Facades\DB;
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
    const SEND_DOCUMENT = 'sendDocument';
    const CHAT_THEME_WASTE = 'limbah_theme';
    const CHAT_THEME_HRD = 'hrd_theme';

    public function url()
    {
        return self::URL . env('TELEGRAM_BOT_TOKEN') . '/' . self::SEND_MESSAGE;
    }

    public function urlDocument($chat_id)
    {
        return self::URL . env('TELEGRAM_BOT_TOKEN') . '/' . self::SEND_DOCUMENT . '?chat_id=' . $chat_id;
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
            return $this->send_underdevelopment_chat($payload, 'HRD');
        }

        if ($msg == self::CHAT_THEME_WASTE) {
            $this->send_init_waste_chat($payload);
            Redis::set('user_chat_theme', self::CHAT_THEME_WASTE);
        } else {
            $this->send_format_failed($payload);
        }
    }

    /**
     * Function to send the rollback message to inform that selected theme is under development
     * 
     * @param array payload
     * 
     * @return void
     */
    public function send_underdevelopment_chat($payload, $text = 'Menu ini')
    {
        $payload['text'] = 'Maaf yaa, untuk '. $text .' masih dalam pengembangan, harap bersabar :)';
        Http::post($this->url(), $payload);
    }


    /******************************************************************************** BEGIN WASTE CHAT SECTION */
    public function waste_list_action()
    {
        return [
            'will_choose_action_theme', // for insert new record for incoming waste
            'will_choose_waste_code', // for insert new record for incoming waste
            'will_send_detail_waste', // for insert new record for incoming waste
            'will_send_qty', // for insert new record for incoming waste
            'will_finish', // for insert new record for incoming waste
            
            'will_send_period_time', // for waste list command
            'will_send_result_of_period', // for waste list command

            'will_send_existing_waste', // for insert outcoming waste
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

        /**
         * Handle out_of_theme convo
         * If it happened, remove all session
         */
        if ($msg == 'out_of_theme') {
            $this->send_out_of_theme_notif($payload);
            return $this->flush_redis();
        } else if ($msg == 'input_limbah_keluar') {
            return $this->send_underdevelopment_chat($payload, 'Limbah Keluar');
            // return $this->send_underdevelopment_chat($payload, 'List Limbah');
            /**
             ** Take over the last step action session
             * 
             */
            $last_step_action = 6;
        } else if ($msg == 'list_limbah') {
            // return $this->send_underdevelopment_chat($payload, 'List Limbah');
            /**
             ** Take over the last step action session
             * 
             */
            $last_step_action = 4;
        }

        $posistion = array_search($last_step_action, array_keys($this->waste_list_action()));
        $posistion_next = (int) $posistion + 1;
        if (isset($this->waste_list_action()[$posistion_next])) {
            $function_name = 'send_' . $this->waste_list_action()[$posistion_next] . '_chat';
            $this->$function_name($payload, $posistion_next, $msg);
        }
    }

    /**
     * Function to send chat list of waste code
     * The purpose is user will be select on of them
     * and Process it!
     */
    public function send_will_choose_waste_code_chat($payload, $next_step, $msg)
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
        return Redis::set('last_step_action', $next_step);
    }

    /**
     * Function to send instruction chat to user to input the detail of waste
     * In this function will retrieve 'waste_code' that has been choose by user (by reply current message)
     * 
     * @param array payload
     * @param int next_step
     * @param string msg : This is the waste code send by user
     * 
     * @return void
     */
    public function send_will_send_detail_waste_chat($payload, $next_step, $msg)
    {
        DB::beginTransaction();
        try {
            $exp_code = explode('@type-', $msg);
            $waste_code = $exp_code[0];
    
            $waste_code_id = WasteCode::where('code', $waste_code)->first();
            $waste_code_id = $waste_code_id->id;
    
            $model = new WasteLog();
            $model->waste_code_id = $waste_code_id;
            $model->waste_type = 'not-filled-yet';
            
            if ($model->save()) {
                $mod = new WasteLogIn();
                $mod->waste_log_id = $model->id;
                $mod->date = date('Y-m-d');
                $mod->exp = date('Y-m-d', strtotime('+90 day'));
                $mod->code_number = generate_waste_code_number($waste_code, $waste_code_id);
                $mod->save();
            }
            
            DB::commit();

            $payload['text'] = 'Silahkan ketik 1 detail limbah yang akan masuk.';
            Http::post($this->url(), $payload);

            $payload['text'] = 'Tolong ketik dengan format seperti di chat selanjutnya ya, atau kamu bisa copy paste pesan tersebut';
            Http::post($this->url(), $payload);

            $payload['text'] = "Detail Limbah *$waste_code* \n";
            $payload['text'] .= "Detail = \n";
            $payload['text'] .= "Jenis Limbah = \n";
            $payload['text'] .= "Sifat Limbah = \n";
            $payload['text'] .= "Sumber Limbah = \n";
            Http::post($this->url(), $payload);
            Redis::set('waste_log_id', $model->id);
            return Redis::set('last_step_action', $next_step);
        } catch (\Throwable $th) {
            Log::debug('error send detail code', ['data' => $th]);
            DB::rollBack();
            return $this->send_failed_to_process_message($payload);
        }
    }

    /**
     * Function to send instuction to user to input qty of waste
     * In this section user will be send the detail text. So it's need to be parsed and save to dataabase
     * 
     * @param array payload
     * @param int next_step
     * @param string msg
     * 
     * @return void
     */
    public function send_will_send_qty_chat($payload, $next_step, $msg)
    {
        DB::beginTransaction();
        try {
            $s = str_replace('Detail =', 'd-', $msg);
            $s = str_replace('Jenis Limbah =', 'd-', $s);
            $s = str_replace('Sifat Limbah =', 'd-', $s);
            $s = str_replace('Sumber Limbah =', 'd-', $s);
            $str_user = explode('d-', $s);
            Log::debug('str_user', ['data' => $str_user]);

            if (count($str_user) < 4) {
                DB::rollBack();
                return $this->send_format_failed($payload);
            }

            $waste_detail = $str_user[1] ?? null;
            $waste_type = $str_user[2] ?? null;
            $waste_properties = $str_user[3] ?? null;
            $waste_source = $str_user[4] ?? null;

            if (
                $waste_type == null ||
                $waste_properties == null ||
                $waste_source == null
            ) {
                DB::rollBack();
                return $this->send_format_failed($payload);
            }

            $helper = $str_user[0];
            $exp_helper = explode('*', $helper);
            $waste_code = $exp_helper[1];
    
            $waste_log_id = Redis::get('waste_log_id');
    
            $model = WasteLog::find($waste_log_id);
            $model->waste_type = $waste_type;
            $model->waste_detail = $waste_detail;
            
            if ($model->save()) {
                $mod = WasteLogIn::where('waste_log_id', $model->id)->first();
                $mod->waste_source = $waste_source;
                $mod->waste_properties = $waste_properties;
                $mod->save();
            }
            
            DB::commit();

            $payload['text'] = 'Masukan berat limbah dalam satuan Kilogram';
            Http::post($this->url(), $payload);
            return Redis::set('last_step_action', $next_step);
        } catch (\Throwable $th) {
            Log::debug('error send qty', ['data' => $th]);
            DB::rollBack();
            return $this->send_failed_to_process_message($payload);
        }
    }

    /**
     * Function to retrieve qty waste,
     * Send the finish message to convo,
     * And end the session of convo
     * 
     * @param array payload
     * @param int next_step
     * @param string msg
     * 
     * @return void
     */
    public function send_will_finish_chat($payload, $next_step, $msg)
    {
        $str = explode('kg', $msg);
        if (count($str) != 2) {
            return $this->send_format_failed($payload);
        }

        $waste_log_id = Redis::get('waste_log_id');
        if (!$waste_log_id) {
            Log::error("err get waste log id", ['data' => $waste_log_id]);
            return $this->send_failed_to_process_message($payload);
        }

        // get current total waste
        $total_current_waste = $this->get_waste_total_qty();
        $total = floatval($str[0]) + floatval($total_current_waste);

        $model = WasteLogIn::where('waste_log_id', $waste_log_id)->first();
        $model->qty = $total;
        $model->save();

        $w = $this->get_result_data($waste_log_id);

        $payload['text'] = "Baik, terima kasih. Data yang kamu input sudah tersimpan di Digital LogBook Limbah B3. \n";
        $payload['text'] .= "Kamu bisa melihat list laporan terbaru yang sudah diinput dengan klik tombol 'List Limbah' saat kamu memilih layanan Limbah ya. \n";
        Http::post($this->url(), $payload);

        $payload['text'] = 'Senang bisa membantumu :) semoga harimu menyenangkan.';
        Http::post($this->url(), $payload);

        $payload['text'] = "Satu Tekad Satu Semangat dan Satu Tujuan Kita Pasti Bisa";
        Http::post($this->url(), $payload);
        
        $payload['text'] = "Berikut hasil dari inputan kamu: \n";
        $payload['text'] .= "Nomor Registrasi: " . $w->in->code_number . "\n";
        $payload['text'] .= "Kode Limbah: " . $w->code->code . "\n";
        $payload['text'] .= "Detail Limbah: " . $w->waste_detail . "\n";
        $payload['text'] .= "Jenis Limbah: " . $w->waste_type . "\n";
        $payload['text'] .= "Sifat Limbah: " . $w->in->waste_properties . "\n";
        Http::post($this->url(), $payload);
        return $this->flush_redis();
    }

    /**
     * Function to send the confirmation period list to show the all waste
     * 
     * @param array payload
     * @param int next_step
     * @param string msg
     * 
     * @return void
     */
    public function send_will_send_period_time_chat($payload, $next_step, $msg)
    {
        $payload['text'] = "Pilih periode waktu yang kamu inginkan";
        $payload['reply_markup'] = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'List Input Terakhir',
                        'callback_data' => 'last_record'
                    ],
                    [
                        'text' => '1 Minggu Terakhir',
                        'callback_data' => 'this_week'
                    ],
                ],
                [
                    [
                        'text' => 'Semua Record',
                        'callback_data' => 'all_record'
                    ],
                ],
            ],
            'resize_keyboard' => true
        ];
        Http::post($this->url(), $payload);
        Redis::set('last_step_action', $next_step);
    }

    public function send_will_send_result_of_period_chat($payload, $next_step, $msg)
    {
        // check data
        if ($msg == 'this_week') {
            $start = date('Y-m-d 00:00:00');
            $end = date('Y-m-d 00:00:00', strtotime('-7 day'));
            $time = [$end, $start];
            $check = WasteLogIn::whereBetween('date', $time)->get();
            if (count($check) == 0) {
                goto send_empty_data;
            }
        }

        $data = $this->get_result_by_period($msg);

        //* generate and save excel in local
        $file = $this->generate_report_as_excel($data);

        if ($file != '') {
            //* send to chat as document
            // Create CURLFile
            $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);
            $cFile = new CURLFile($file, $finfo);
            $send = [
                'document' => $cFile
            ];
    
            $this->handle_send_document_request($this->urlDocument($payload['chat_id']), $send);
        } else {
            send_empty_data:
            $payload['text'] = 'Belum ada data tersimpan untuk peride waktu yang anda pilih';
            Http::post($this->url(), $payload);
        }

        return $this->flush_redis();
    }

    public function send_will_send_existing_waste_chat($payload, $next_step, $msg)
    {
        $waste_code = WasteCode::all();
        $payload['text'] = "Silahkan pilih limbah dulu ya";
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
        return Redis::set('last_step_action', $next_step);
    }

    public function handle_send_document_request($url, $file)
    {
        // Send dummy file
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Add CURLFile to CURL request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $file);

        // Call
        $result = curl_exec($ch);

        // Show result and close curl
        var_dump($result);
        curl_close($ch);
    }
    /******************************************************************************** END WASTE CHAT SECTION */
    
    public function flush_redis()
    {
        Redis::del('user_chat_theme');
        Redis::del('last_step_action');
        Redis::del('user_theme_action');
        Redis::del('waste_log_id');
    }

    public function send_out_of_theme_notif($payload)
    {
        $payload['text'] = "Kamu sudah tidak memiliki tema yang aktif. \n";
        $payload['text'] .= "Untuk memulai pembicaraan, ketik '/start' yaa";
        Http::post($this->url(), $payload);
    }

    public function get_result_data($waste_log_id)
    {
        $data = WasteLog::with(['in', 'code'])->find($waste_log_id);
        return $data;
    }

    public function get_result_by_period($period)
    {
        $q = WasteLog::query();
        $q->with('code');
        if ($period == 'last_record') {
            $q->with('in')
                ->orderBy('id', 'desc')
                ->limit(1);
        } else if ($period == 'this_week') {
            $start = date('Y-m-d 00:00:00');
            $end = date('Y-m-d 00:00:00', strtotime('-7 day'));
            $time = [$end, $start];
            $q->with('in', function($query) use($time) {
                $query->whereBetween('date', $time);
            });
        } else {
            $q->with('in');
        }
        $data = $q->get();

        return $data;
    }

    public function get_waste_total_qty()
    {
        $data = WasteLog::select('id', 'total_qty')->get();
        $total = collect($data)->sum('total_qty');
        return $total;
    }

    public function send_failed_to_process_message($payload)
    {
        $payload['text'] = 'Mohon maaf terjadi gangguan server, mohon hubungi penanggung jawab untuk menyelesaikan ini';
        Http::post($this->url(), $payload);
    }

    public function send_format_failed($payload)
    {
        $payload['text'] = 'Format yang kamu masukan tidak sesuai';
        Http::post($this->url(), $payload);
    }

    public function generate_report_as_excel($data)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly(["PRINT"]);
        $spreadsheet = $reader->load("logbook.xlsx");
        $spreadsheet->getActiveSheet()->setCellValue('D7', '2023');
        
        $fix_file = '';
        if (count($data) > 0) {
            $start_row = 12;
            foreach ($data as $key => $d) {
                $code_number = $d->in->code_number;
                $code = $d->code->code;
                $detail = $d->waste_detail;
                $prop = $d->in->waste_properties;
                $source = $d->in->waste_source;
                $qty = $d->in->qty;
    
                $spreadsheet->getActiveSheet()->setCellValue('B'."$start_row", ($key + 1));
                $spreadsheet->getActiveSheet()->setCellValue('C'."$start_row", $code . ' (' . $detail . ')');
                $spreadsheet->getActiveSheet()->setCellValue('D'."$start_row", date('d F Y', strtotime($d->in->date)));
                $spreadsheet->getActiveSheet()->setCellValue('E'."$start_row", $source);
                $spreadsheet->getActiveSheet()->setCellValue('F'."$start_row", number_format($qty, 2, '.', ''));
                $spreadsheet->getActiveSheet()->setCellValue('G'."$start_row", date('d F Y', strtotime($d->in->exp)));
                $spreadsheet->getActiveSheet()->setCellValue('L'."$start_row", number_format($d->total_qty, 2, '.', ''));

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
    
            $columns = ['B', 'C', 'D', 'E', 'F', 'G'];
            for ($xy = 0; $xy < count($columns); $xy++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columns[$xy])->setAutoSize(true);
            }
    
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            $file = 'result.xlsx';
            $writer->save($file);
            $fix_file = './' . $file;
        }
        
        return $fix_file;
    }
}