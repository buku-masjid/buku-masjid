<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    const ROLE_ADMIN = 1;
    const ROLE_CHAIRMAN = 2;
    const ROLE_SECRETARY = 3;
    const ROLE_FINANCE = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRoleAttribute()
    {
        if ($this->role_id == self::ROLE_CHAIRMAN) {
            return __('user.role_chairman');
        }
        if ($this->role_id == self::ROLE_SECRETARY) {
            return __('user.role_secretary');
        }
        if ($this->role_id == self::ROLE_FINANCE) {
            return __('user.role_finance');
        }

        return __('user.role_admin');
    }
}
