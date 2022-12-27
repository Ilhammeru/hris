<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCode extends Model
{
    use HasFactory;
    protected $table = 'waste_code';
    protected $fillable = [
        'code',
        'description'
    ];
}
