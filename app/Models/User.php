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
use App\Models\Wallet;
use Storage;
use Carbon\Carbon;
use Cmgmyr\Messenger\Traits\Messagable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPushSubscriptions, Messagable;

    protected $fillable = [
        'cpf', 'name', 'nickname', 'email', 'password', 'birth_date', 'photo_profile', 'role_id', 'status', 'is_online'
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

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function getLessonsByPeriod(Carbon $dtIni, Carbon $dtEnd)
    {
        
    }

}
