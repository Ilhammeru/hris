<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteLogIn extends Model
{
    use HasFactory;
    protected $fillable = [
        'waste_log_id',
        'date',
        'waste_source',
        'exp',
        'code_number'
    ];
}
