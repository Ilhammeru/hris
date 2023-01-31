<?php

namespace Modules\Event\Entities;

use App\Models\AttendantList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendees extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendant_id',
        'event_id',
        'signature',
        'check_in_at',
    ];

    public function attendant(): BelongsTo
    {
        return $this->belongsTo(AttendantList::class, 'attendant_id');
    }
}
