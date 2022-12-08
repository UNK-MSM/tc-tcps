<?php

namespace App;

class Country extends BaseModel
{
    //
    public static $fillableFields = [
	    	'label_en' => ['type' => 'text', 'validation'=> 'max:45'],
	    	'label_ar' => ['type' => 'text', 'validation'=> 'max:45'],
	    	'iso_code' => ['type' => 'text', 'validation'=> 'max:3|required'],
	    	'area_code' => ['type' => 'integer', 'validation'=> 'max:3|required']
    	];
}
