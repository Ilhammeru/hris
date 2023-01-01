<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailWasteCode extends Model
{
    use HasFactory;
    protected $table = 'detail_waste_code';
    protected $fillable = [
        'waste_code_id',
        'name'
    ];
}
