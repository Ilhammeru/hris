<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasRoles, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role'
    ];

    protected $table = 'users';
    protected $hidden = ['password'];

    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class, 'role', 'id');
    }
}
