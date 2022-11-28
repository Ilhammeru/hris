<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecruitmentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'step'
    ];
    protected $table = 'recruitment_setting';
}
