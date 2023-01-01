<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WasteLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'waste_code_id',
        'waste_type'
    ];

    public function in():HasOne
    {
        return $this->hasOne(WasteLogIn::class, 'waste_log_id');
    }

    public function code():BelongsTo
    {
        return $this->belongsTo(WasteCode::class, 'waste_code_id');
    }
}
