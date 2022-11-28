<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    
    // status list
    const PERMANENT = 1;
    const INTERNSHIP = 2;
    const VACCANT = 3;
    const REJECT_APPLICANT = 4;

    // prefix employee code
    const PREFIX_EMP_CODE = 'EMP-';

    protected $fillable = [];
    protected $table = 'employees';
}
