<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class Point extends BaseModel
{
    //
    protected $table = "membership_notifications";
    public static $fillableFields = [
            'user_serial_no' => ['type' => 'list', 'updatable' => false,],
            'param_2' => ['type' => 'list', 'list' => [ -1 => 'Redeem', 1 => 'Claim']],
            'param_1' => ['type' => 'float',],
            'empty00' => ['type' => 'empty'],
	    	'body_ar' => ['type' => 'textarea', 'value' => 'حصلت على {points} نقطة', 'data' => ['redemption_message' => 'تم استهلاك {points} نقطة في اشتراك', 'claim_message' => 'حصلت على {points} نقطة']],
	    	'body_en' => ['type' => 'textarea', 'value' => 'You have Earned {points} Points', 'data' => ['redemption_message' => '{points} points were consumed in membership', 'claim_message' => 'You have Earned {points} Points']],
            'points_balance' => ['type' => 'float', 'insertable' => false, 'updatable' => false,],
    	];

    public static $insertable = true;
    public static $deletable = true;
    public static $editable = false;
    public static $createJS = 'create_points.js';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('user', function(Builder $builder) {
        	$builder->select('membership_notifications.*', 'users.full_name', 'users.points_balance')->join('users', 'users.serial_no', '=', 'membership_notifications.user_serial_no')->whereIn('type_serial_no', [1,6]);
        });

        static::creating(function ($instance) {
            $user = \App\User::findOrFail($instance->user_serial_no);
            if($instance->param_2 == -1)
            {
                $user->decrement('points_balance', $instance->param_1);
                $instance->type_serial_no = 1;
            }else
            {
                $user->increment('points_balance', $instance->param_1);
                $instance->type_serial_no = 6;
            }
            $body_ar = str_replace('{points}', $instance->param_1, $instance->body_ar);
            $body_en = str_replace('{points}', $instance->param_1, $instance->body_en);

            $instance->body_ar = $body_ar;
            $instance->body_en = $body_en;

        });
        static::deleted(function ($instance) {
            
        });
    }

    public function user()
    {
    	return $this->hasOne('\App\User', 'user_serial_no');
    }

}
