<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Entities\Employee;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'applicant';

    public function detail():BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
