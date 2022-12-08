<?php

namespace App;
class DiscountCode extends BaseModel
{
    //
    //protected $primaryKey = 'id';
    public static $fillableFields = [
	    	'code' => ['type' => 'text', 'validation' => 'required'],
	    	//'rate_or_fixed' => ['type' => 'list', 'list'=>[0 => 'RATE', 1 => 'FIXED']],
	    	'discount_description_en' => ['type' => 'textarea'],
	    	'discount_description_ar' => ['type' => 'textarea'],
	    	'rate_or_fixed' => ['type' => 'boolean', 'switch' => ['FIXED', 'RATE'],],
	    	'amount' => ['type' => 'float', 'validation' => 'required'],
	    	'start_date' => ['type' => 'date', 'validation' => 'required'],
	    	'expire_date' => ['type' => 'date',],
	    	'active' => ['type' => 'boolean', 'xview' => true, 'switch' => ['YES', 'NO'], ],
	    	'max_redemption_times' => ['type' => 'integer', 'value' => 1, 'validation' => 'required'],
	    	'redemption_times' => ['type' => 'integer', 'updateable' => false, 'insertable' => false],
	    	'specific_member_id' => ['type' => 'list', 'list_reference' => 'membership'],
    	];

    //public static $insertable = false;
    //public static $deletable = false;
    public static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            if($instance->specific_member_id == '')
            {
                $instance->specific_member_id = null;
            }
            if($instance->expire_date == '')
            {
                $instance->expire_date = null;
            }
        });
        static::updating(function ($instance) {
            if($instance->specific_member_id == '')
            {
                $instance->specific_member_id = null;
            }
            if($instance->expire_date == '')
            {
                $instance->expire_date = null;
            }
            
        });
    }
}
