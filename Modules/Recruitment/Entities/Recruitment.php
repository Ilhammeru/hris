<?php

namespace Modules\Recruitment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Company\Entities\Department;
use Modules\Company\Entities\Division;
use Modules\Employee\Entities\Employee;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

class Recruitment extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [];
    protected $table = 'vacancy';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Job Type ID
     */
    const JOB_TYPE_FULL_TIME = 1;
    const JOB_TYPE_PART_TIME = 2;
    const JOB_TYPE_INTERNSHIP_TIME = 3;
    
    /**
     * Working type
     */
    const WFO = 1;
    const WFA = 2;
    const HYBRID = 3;

    /**
     * Function to get all job type
     * @return array
     */
    public function getJobType()
    {
        return [
            [
                'id' => Recruitment::JOB_TYPE_FULL_TIME,
                'name' => 'Full Time'
            ],
            [
                'id' => Recruitment::JOB_TYPE_PART_TIME,
                'name' => 'Part Time'
            ],
            [
                'id' => Recruitment::JOB_TYPE_INTERNSHIP_TIME,
                'name' => 'Internship'
            ],
        ];
    }

    /**
     * Function to get job type by ID
     * @param int ID
     * @return array
     */
    function getJobTypeById($id)
    {
        $data = $this->getJobType();
        $res = collect($data)->filter(function($item) use($id) {
            return $item['id'] == $id;
        })->values();
        return $res;
    }

    /**
     * Function to define working type
     * @return array
     */
    public function getWorkingType()
    {
        return [
            [
                'id' => Recruitment::WFO,
                'name' => 'Work From Office'
            ],
            [
                'id' => Recruitment::WFA,
                'name' => 'Remote (World Wide)'
            ],
            [
                'id' => Recruitment::HYBRID,
                'name' => 'Hybrid'
            ],
        ];
    }

    /**
     * Function to get working type value by given ID
     * @param int id
     * @return array
     */
    public function getWorkingTypeById($id)
    {
        $data = $this->getWorkingType();
        $res = collect($data)->filter(function($item) use($id) {
            return $item['id'] == $id;
        })->values();
        return $res;
    }

    public static function getTagClassName(): string
    {
        return Tag::class;
    }

    public function tags(): MorphToMany
    {
        return $this
            ->morphToMany(Tag::class, 'taggable', 'taggables', null, 'tag_id');
    }

    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function division():BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function publish()
    {
        $publish = '-';

        $relation = User::select('email')
            ->find($this->publish_by);
        if ($relation) {
            $publish = $relation->email;
    
            if ($relation) {
                $employee = Employee::select('name')
                    ->where('email', $relation->email)
                    ->first();
                $publish = $employee->name ?? $relation->email;
            }
        }

        return $publish;
    }

    public function created_by()
    {
        $relation = User::select('email')
            ->find($this->created_by);
        $created = $relation->email;

        if ($relation) {
            $employee = Employee::select('name')
                ->where('email', $relation->email)
                ->first();
            $created = $employee->name ?? $relation->email;
        }

        return $created;
    }

    public function applicants():HasMany
    {
        return $this->hasMany(Applicant::class, 'vacancy_id', 'id');
    }
}
