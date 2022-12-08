<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract,
                                        AuthorizableContract,
                                        CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public static $fillableFields = [
            'full_name' => ['type' => 'text', 'validation'=> 'max:145|required'],
            'username' => ['type' => 'text', 'updatable' => false, 'validation'=> 'email|unique:users'],
            'password' => ['type' => 'password', 'validation' => 'min:8|max:12',],
            'mobile_no' => ['type' => 'text',],
            'country_serial_no' => ['type' => 'list', 'validation' => 'required'],
            'user_type_serial_no' => ['type' => 'list', 'validation'=> 'required'],
            'user_status_serial_no' => ['type' => 'list', 'validation' => 'required'],
            'empty00' => ['type' => 'empty',],
            'email' => ['type' => 'text', 'updatable' => false, 'insertable' => false, 'xview' => true, 'validation'=> 'email|unique:users'],
            //'email_verified' => ['type' => 'boolean',],
            //'mobile_verified' => ['type' => 'boolean',],
        ];
    public static $viewJS = 'view_users.js';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->email = $instance->username;
        });
    }
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getLabel()
    {
        return 'username';
    }

    public function user_type()
    {
        return $this->belongsTo('\App\UserType', 'user_type_serial_no');
    }
}
