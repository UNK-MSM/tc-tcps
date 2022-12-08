<?php

namespace App;

class DiscountCodeUsage  extends BaseModel
{
    //
    //protected $primaryKey = 'id';
    public static $fillableFields = [
	    	'code' => ['type' => 'text', 'validation' => 'required'],
	    	'membership_serial_no' => ['type' => 'list', 'list_reference' => 'membership'],
	    	'date_used' => ['type' => 'date',],
	    	'membership_plan_instance_serial_no' => ['type' => 'list', 'list_reference' => 'membership_plan'],
	    	'amount_discounted' => ['type' => 'float',],
    	];

    public static $insertable = false;
    public static $deletable = false;
}
