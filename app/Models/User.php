<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;
use App\Models\Role;
use App\Models\Address;
use App\Models\BankAccount;
use App\Models\UserEvaluation;
use App\Models\TeacherGame;
use Storage;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPushSubscriptions;

    protected $fillable = [
        'cpf', 'name', 'nickname', 'email', 'password', 'birth_date', 'photo_profile', 'role_id', 'status'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'birth_date'
    ];
    
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

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function getPhotoProfileUrl()
    {
        if ($this->photo_profile) {
            return Storage::disk('public')->url($this->photo_profile);
        }
        return null;
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }

    public function evaluation()
    {
        return $this->hasOne(UserEvaluation::class);
    }

    public function teacherGames()
    {
        return $this->hasMany(TeacherGame::class, 'teacher_id');
    }

}
