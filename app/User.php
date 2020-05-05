<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    // nhanvienb@gmail.com
    // pass: quocki1234.
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin',
        'phone', 'sex', 'address', 'store_code',
        'role_id', 'birthday', 'status', 'wallet',
        'token_device', 'is_login', 'point', 'is_surplus_box', 'surplus_box', 'login_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany('App\Order', 'customer_id');
    }

    public function rechages()
    {
        return $this->hasMany('App\Rechage', 'customer_id');
    }
}
