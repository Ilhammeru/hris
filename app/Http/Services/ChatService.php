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
    }
}