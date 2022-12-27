<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteLogOut extends Model
{
    use HasFactory;
    protected $fillable = [
        'waste_log_id',
        'waste_log_in_id',
        'qty',
        'date',
        'target_out'
    ];
}
