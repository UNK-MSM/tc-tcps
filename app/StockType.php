<?php

namespace App;

class StockType extends BaseModel
{
    //
    public static $fillableFields = [
	    	'label_en' => ['type' => 'text', 'validation'=> 'max:245|required'],
	    	'label_ar' => ['type' => 'text', 'validation'=> 'max:245|required']
    	];

}
