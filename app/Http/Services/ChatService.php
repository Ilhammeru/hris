<?php

namespace App\Http\Services;

use App\Models\TelegramUserChat;
use App\Models\WasteLogIn;
use Carbon\Carbon;

class ChatService {
    public function save_waste_chat($request = [])
    {
        if (!empty($request['id'])) {
            $model = WasteLogIn::find($request['id']);
            $model->qty = $request['qty'] ?? 0;
        } else {
            $model = new WasteLogIn();
            $all = TelegramUserChat::select('id')->count();
            $last = $all + 1;
            $model->waste_log_id = $request['waste_code'] . '/' . $last;
            $model->date = Carbon::now();
            $model->waste_source = $request['source'] ?? null;
            $model->exp = date('Y-m-d', strtotime('+ 90day')) ?? null;
            $model->code_number = $all;
        }
        $model->save();

        return $model;
    }

    public function save_chat($request = [])
    {
        if ($request['is_new_record']) {
            $model = new TelegramUserChat();
            $model->room_id = $request['room_id'] ?? null;
        } else {
            $model = TelegramUserChat::where('room_id', $request['room_id'])->first();
        }
        $model->theme = $request['theme'] ?? null;
        $model->message = $request['message'] ?? null;
        $model->supposed_to_send = $request['supposed_to_send'] ?? null;
        $model->save();
        return $model;



        // define detail limbah reply
        // $waste_detail_reply = explode('Detail Limbah ', $message);
        // if (count($waste_detail_reply) > 1) { // !TODO: Create condition current theme
        //     $is_amount = true;
        //     goto send_amount_of_waste;
        // }

        // // define amount of waste
        // $amw = explode(' kg', $message);
        // if (count($amw) > 1) { // !TODO: Create condition current theme
        //     $is_finish = true;
        //     goto send_finish_confirmation;
        // }

        // // greeting
        // if ($message == '/start') {
        //     $res_message['text'] = 'Hai, selamat datang di Layanan Digital MPS Brondong';
        //     $send = Http::post($url, $res_message);
        //     if (!empty($send['ok'])) {
        //         $res_message['text'] = 'Kamu bisa panggil aku CigarBot.';
        //         $send_1 = Http::post($url, $res_message);
        //         if (!empty($send_1['ok'])) {
        //             $res_message['text'] = 'Klik tombol dibawah sesuai kebutuhanmu ya, aku akan membantu dengan senang hati :)';
        //             $res_message['reply_markup'] = [
        //                 'inline_keyboard' => [
        //                     [
        //                         [
        //                             'text' => 'Limbah',
        //                             'callback_data' => 'limbah_theme'
        //                         ],
        //                         [
        //                             'text' => 'HRD',
        //                             'callback_data' => 'hrd_theme'
        //                         ],
        //                     ]
        //                 ],
        //                 'resize_keyboard' => true
        //             ];
        //             $send_2 = Http::post($url, $res_message);
        //         }
        //     }
        // }

        // send_amount_of_waste:
        // if ($is_amount) {
        //     $res_message['text'] = 'Masukan berat limbah dalam satuan Kilogram';
        //     Http::post($url, $res_message);
        //     $request = [
        //         'is_new_record' => false,
        //         'room_id' => $room_id,
        //         'supposed_to_send' => 'weight_waste'
        //     ];
        //     $chat_service->save_chat($request);
        // }

        // send_finish_confirmation:
        // if ($is_finish) {
        //     $res_message['text'] = "Baik, terima kasih. Data yang kamu input sudah tersimpan di Digital LogBook Limbah B3. \n";
        //     $res_message['text'] .= "Kamu bisa melihat list laporan terbaru yang sudah diinput dengan klik tombol 'List Limbah' saat kamu memilih layanan Limbah ya. \n";
        //     Http::post($url, $res_message);

        //     sleep(.5);
        //     $res_message['text'] = 'Senang bisa membantumu :) semoga harimu menyenangkan.';
        //     Http::post($url, $res_message);

        //     sleep(.5);
        //     $res_message['text'] = "Satu Tekad Satu Semangat dan Satu Tujuan Kita Pasti Bisa";
        //     Http::post($url, $res_message);
        //     $request = [
        //         'is_new_record' => false,
        //         'room_id' => $room_id,
        //         'supposed_to_send' => 'finish'
        //     ];
        //     $chat_service->save_chat($request);

        //     $current_waste = session('current_waste');
        //     $request_waste = [
        //         'id' => $current_waste->id,
        //         'qty' => $message
        //     ];
        //     $chat_service->save_waste_chat($request);
        // }





        /************************************************ */

        // $room_id = $item['callback_query']['message']['chat']['id'];

        // $res_message = [
        //     'chat_id' => $room_id,
        //     'text' => ''
        // ];

        // // define waste code
        // $str_code = explode('@type', $theme);
        // if (count($str_code) > 1) {
        //     $is_type = true;
        //     goto send_type;
        // }

        // if ($chat_theme) {

        // } else {
        //     $current_chat = new TelegramUserChat();
        //     $current_chat->room_id = $room_id;
        //     $current_chat->theme = $theme;
        //     $current_chat->message = $theme;
        //     $current_chat->save();

        //     if ($theme == 'limbah_theme') {
        //         $waste_code = WasteCode::all();
        //         $res_message['text'] = "Baik, aku akan membantumu untuk mengetahui lebih dalam tentang limbah. \n";
        //         $res_message['text'] .= "Berikut adalah list kode limbah yang ada di MPS Brondong \n";
        //         foreach ($waste_code as $k => $c) {
        //             $res_message['text'] .= ($k + 1) . ". " . $c->code . " : " . $c->description . " \n";
        //         }
        //         $res_message['text'] .= "Pilih salah satu tombol di bawah ya. \n";
        //         Http::post($url, $res_message);

        //         sleep(.5);

        //         $res_message['text'] = "Jika kamu ingin keluar dari tema limbah ini, kamu bisa tekan tombol 'keluar' \n";
        //         $res_message['reply_markup'] = [
        //             'inline_keyboard' => [
        //                 [
        //                     [
        //                         'text' => 'Input Limbah Datang',
        //                         'callback_data' => 'input_limbah_datang'
        //                     ]
        //                 ],
        //                 [
        //                     [
        //                         'text' => 'Input Limbah Keluar',
        //                         'callback_data' => 'input_limbah_keluar'
        //                     ]
        //                 ],
        //                 [
        //                     [
        //                         'text' => 'List Limbah',
        //                         'callback_data' => 'list_limbah'
        //                     ]
        //                 ],
        //                 [
        //                     [
        //                         'text' => 'keluar',
        //                         'callback_data' => 'out_of_theme'
        //                     ]
        //                 ],
        //             ],
        //             'resize_keyboard' => true
        //         ];
        //         Http::post($url, $res_message);
        //     } else if ($theme == 'hrd_theme') {
        //         $res_message['text'] = 'Saya belum bisa memberikan informasi lebih lanjut tentang HRD, saya masih mengumpulkan semua informasi yang ada, harap bersabar yaa :)';
        //         Http::post($url, $res_message);
        //     } else if ($theme = 'input_limbah_datang') {
        //         $waste_code = WasteCode::all();
        //         $res_message['text'] = "Silahkan pilih kode limbah dulu ya";
        //         $textMarkup = [];
        //         foreach ($waste_code as $k => $c) {
        //             $textMarkup[] = [
        //                 [
        //                     'text' => $c->code,
        //                     'callback_data' => $c->code . '@type-type'
        //                 ]
        //             ];
        //         }
        //         $res_message['reply_markup'] = [
        //             'inline_keyboard' => $textMarkup,
        //             'resize_keyboard' => true
        //         ];
        //         Http::post($url, $res_message);

        //         $request = [
        //             'room_id' => $room_id,
        //             'theme' => $theme,
        //             'supposed_to_send' => 'waste_code',
        //             'is_new_record' => true
        //         ];
        //         $chat_service->save_chat($request);
        //     } else {
        //         send_type:
        //         if ($is_type) {
        //             // check callback type
        //             $str = explode('@type', $theme);
        //             $selected_code = $str[0];
        //             /**
        //              * User send waste code
        //              * System supposed to answer type of waste
        //              */
        //             $res_message['text'] = 'Silahkan ketik 1 detail limbah yang akan masuk.';
        //             $send = Http::post($url, $res_message);

        //             sleep(.5);
        //             $res_message['text'] = 'Tolong ketik dengan format seperti di chat selanjutnya ya, atau kamu bisa copy paste pesan tersebut';
        //             Http::post($url, $res_message);

        //             sleep(.5);

        //             $res_message['text'] = "Detail Limbah \n";
        //             $res_message['text'] .= "Detail = <detail limbah ketik disini ya> \n";
        //             $res_message['text'] .= "Jenis Limbah = <jenis limbah ketik disini ya> \n";
        //             $res_message['text'] .= "Sifat Limbah = <sifat limbah ketik disini ya> \n";
        //             Http::post($url, $res_message);
        //             $request = [
        //                 'room_id' => $room_id,
        //                 'theme' => $theme,
        //                 'supposed_to_send' => 'waste_detail',
        //                 'is_new_record' => false
        //             ];
        //             $chat_service->save_chat($request);

        //             $request_waste = [
        //                 'waste_code' => $selected_code
        //             ];
        //             $sw = $chat_service->save_waste_chat($request_waste);
        //             session('current_waste', $sw->id);
        //         }
        //     }
        // }
    }
}