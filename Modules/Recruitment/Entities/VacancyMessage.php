<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacancyMessage extends Model
{
    use HasFactory;

    protected $table = 'vacancy_message';
    protected $fillable = [
        'sender_email',
        'sender_phone',
        'receiver_email',
        'receiver_phone',
        'message',
        'vacancy_id',
        'message_group',
        'read_at'
    ];

    public function vacancy():BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'vacancy_id', 'id');
    }

    public function scopeUnread($query)
    {
        $query->where('read_at', null);
    }
}
