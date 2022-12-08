<?php

namespace App;


class MembershipCustomPlan extends BaseModel
{
    //
    public static $fillableFields = [
	    	'single_day_membership' => ['type' => 'float', 'validation'=> 'required'],
	    	'monthly_fees' => ['type' => 'float', 'validation'=> 'required'],
	    	'monthly_fees_6_months' => ['type' => 'float', 'validation'=> 'required'],
	    	'monthly_fees_12_months' => ['type' => 'float', 'validation'=> 'required'],
	    	'monthly_fees_24_months' => ['type' => 'float', 'validation'=> 'required'],
	    	'membership_serial_no' => ['type' => 'list', 'validation' => 'required'],
    	];
    	
    public static function getLabel()
    {
        return 'serial_no';
    }
}
