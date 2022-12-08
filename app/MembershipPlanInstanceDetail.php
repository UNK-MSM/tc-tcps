<?php

namespace App;

class MembershipPlanInstanceDetail extends BaseModel
{
    //
    public static $fillableFields = [
	    	'stock_market_serial_no' => ['type' => 'list',],
	    	'stock_serial_no' => ['type' => 'list',],
	    	'item_actual_price' => ['type' => 'float', 'validation'=> 'numeric'],
	    	'optional_discount_rate' => ['type' => 'float', 'validation'=> 'numeric'],
	    	'discount_by_user_serial_no' => ['type' => 'list', 'list_reference'=> 'user'],
	    	'date_added' => ['type' => 'date', 'updateable' => false, 'insertable' => false],
    	];

    public static $insertable = false;
    public static $deletable = false;
    public static $editable = false;
}
