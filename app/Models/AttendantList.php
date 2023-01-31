<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Company\Entities\Position;

class AttendantList extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'attendant_lists';
    protected $fillable = ['employee_id', 'name', 'position_id'];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
