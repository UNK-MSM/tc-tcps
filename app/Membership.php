<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class Membership extends BaseModel
{
    //
    public static $fillableFields = [
            'user_serial_no' => ['type' => 'list', 'updatable' => false,],
            'mobile_no' => ['type' => 'text', 'updatable' => false,],
	    	'country_serial_no' => ['type' => 'list', 'updatable' => false,],
	    	'city' => ['type' => 'text', 'xview' => true, 'updatable' => false,],
	    	'full_address' => ['type' => 'text', 'xview' => true, 'updatable' => false,],
            'membership_type_serial_no' => ['type' => 'list', 'updatable' => false,],
            'by_referral_member_serial_no' => ['type' => 'list', 'list_reference' => 'membership', 'updatable' => false,],
            'referral_extension_in_days' => ['type' => 'integer', 'updatable' => false, 'xview' => true,],
            'points_balance' => ['type' => 'integer', 'updatable' => false,],
            'empty00' => ['type' => 'empty',],
            'status' => ['type' => 'list', 'list_reference' => 'membership_state', 'updatable' => false,],
	    	'membership_activated' => ['type' => 'boolean', 'updatable' => false,],

            'membership_plan_instances' => ['type' => 'membership-plan-instances-tabular', 'relation' => 'one-to-many', 'xview' => true],
    	];
    public static $viewJS = 'view_memberships.js';

    public static $insertable = false;
    public static $deletable = false;
    public static $relationalFields = ['membership_plan_instances'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('user', function(Builder $builder) {
        	$builder->select('memberships.*', 'users.full_name', 'users.country_serial_no', 'users.mobile_no', 'users.points_balance')->join('users', 'users.serial_no', '=', 'memberships.user_serial_no');
        });
    }

    public function user()
    {
    	return $this->hasOne('\App\User', 'user_serial_no');
    }

    public function membership_plan_instances()
    {
        return $this->hasMany('\App\MembershipPlanInstance', 'membership_serial_no');
    }

    public static function getLabel()
    {
        return 'full_name';
    }
}
