<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject; //tambahkan

class User extends Authenticatable implements JWTSubject //tambahkan ini
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Firstname', 'Lastname', 'email', 'password', 'password_verify',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'password_verify',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //tambahkan ini
    public function getJWTIdentifier()
    {
      return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
      return [];
    }
}
