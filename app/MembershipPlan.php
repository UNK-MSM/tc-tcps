<?php

namespace App;

class MembershipPlan extends BaseModel
{
    //
    public static $fillableFields = [
	    	'label_en' => ['type' => 'text', 'validation'=> 'max:245'],
	    	'label_ar' => ['type' => 'text', 'validation'=> 'max:245'],
	    	'description_en' => ['type' => 'textarea'],
	    	'description_ar' => ['type' => 'textarea'],
	    	'single_day_membership' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'monthly_fees' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'monthly_fees_3_months' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'monthly_fees_6_months' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'monthly_fees_12_months' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'monthly_fees_24_months' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'active' => ['type' => 'boolean', 'xview' => true, 'validation'=> 'required'],
    	];
}
