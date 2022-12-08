<?php

namespace App;

class MembershipPlanInstanceState extends BaseModel
{
    //
    public static $fillableFields = [
	    	'label_en' => ['type' => 'text', 'validation'=> 'max:245'],
	    	'label_ar' => ['type' => 'text', 'validation'=> 'max:245'],
    	];
}
