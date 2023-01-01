<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUserChat extends Model
{
    use HasFactory;
    protected $table = 'telegram_user_chat';
    protected $fillable = [
        'room_id',
        'theme',
        'message',
        'supposed_to_send'
    ];
}
