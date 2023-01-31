<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    const SIGNATURE = 1;
    const CONFIRMATION = 2;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'option_finisher',
        'start_date',
        'end_date',
    ];
}
