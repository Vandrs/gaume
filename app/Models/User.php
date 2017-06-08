<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;
use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cpf', 'name', 'nickname', 'email', 'password', 'birth_date', 'photo_profile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getNicknameAttribute($nickname)
    {
        return empty($nickname) ? $this->name : $nickname;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id','id');
    }

    public function hasRole($role)
    {
        return $this->role->role == $role;
    }

}
