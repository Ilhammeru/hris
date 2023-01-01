<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'waste_code_id',
        'waste_type'
    ];

    public function in():BelongsTo
    {
        return $this->belongsTo(WasteLogIn::class, 'waste_log_id');
    }
}
