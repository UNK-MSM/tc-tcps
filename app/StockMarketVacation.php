<?php

namespace App;

class StockMarketVacation extends BaseModel
{
    //
    public static $fillableFields = [
	    	'description_en' => ['type' => 'textarea'],
	    	'description_ar' => ['type' => 'textarea'],
	    	'stock_market_serial_no' => ['type' => 'list', 'validation'=> 'required'],
	    	'annual' => ['type' => 'boolean', 'validation'=> 'required'],
	    	'start_date' => ['type' => 'date', 'validation'=> 'required'],
	    	'end_date' => ['type' => 'date'],
    	];
}
