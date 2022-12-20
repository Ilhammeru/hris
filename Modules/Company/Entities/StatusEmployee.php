<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusEmployee extends Model
{
    use HasFactory;

    protected $table = 'status_employee';
    protected $fillable = [
        'name'
    ];
}
