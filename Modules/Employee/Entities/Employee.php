<?php

namespace Modules\Employee\Entities;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Company\Entities\Division;

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

    protected $fillable = [
        'employee_code',
        'name',
        'email',
        'date_of_birth',
        'phone',
        'nik',
        'division_id',
        'department_id',
        'address',
        'village_id',
        'district_id',
        'city_id',
        'province_id',
        'account_number',
        'bank_name',
        'social_media',
        'bpjs_ketenagakerjaan',
        'bpjs_kesehatan',
        'npwp',
        'is_active',
        'meta_experience',
        'meta_education',
        'mother_name',
        'status',
        'internship_date',
        'apply_vaccant_date',
        'user_id'
    ];
    protected $table = 'employees';
    protected $appends = ['working_time', 'employement_status'];

    public function scopeActive($query)
    {
        $query->where('is_active', true);
    }

    public function division():BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function workingTime():Attribute
    {
        return new Attribute(
            get: fn () => parse_date_to_readable($this->internship_date)
        );
    }

    public function employementStatus():Attribute
    {
        return new Attribute(
            get: fn () => $this->status == 1 ? __('employee::view.permanent') : __('employee::view.internship')
        );
    }
}

