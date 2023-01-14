<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteLogIn extends Model
{
    use HasFactory;
    protected $fillable = [
        'waste_log_id',
        'date',
        'waste_source',
        'waste_properties',
        'exp',
        'code_number'
    ];

    public function log(): BelongsTo
    {
        return $this->belongsTo(WasteLog::class, 'waste_log_id');
    }
}
