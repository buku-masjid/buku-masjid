<?php

namespace App;

use App\Traits\Models\ConstantsGetter;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use ConstantsGetter, HasApiTokens, Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_CHAIRMAN = 2;
    const ROLE_SECRETARY = 3;
    const ROLE_FINANCE = 4;

    const API_TOKEN_LENGTH = 24;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'role_id', 'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
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

    public function getStatusAttribute()
    {
        return $this->isActive() ? __('app.active') : __('app.inactive');
    }

    public function isActive()
    {
        return (int) $this->is_active === 1;
    }

    public function generateApiToken()
    {
        return $this->api_token = Str::random(self::API_TOKEN_LENGTH);
    }
}
