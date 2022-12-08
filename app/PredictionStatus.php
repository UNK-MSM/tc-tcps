<?php

namespace App;

class PredictionStatus extends BaseModel
{
    public static $fillableFields = [
	    	'label_en' => ['type' => 'text', 'validation'=> 'max:145'],
	    	'label_ar' => ['type' => 'text', 'validation'=> 'max:145'],
	    	'final' => ['type' => 'boolean', 'switch' => ['YES', 'NO'], 'validation'=> 'max:3|required']
    	];
}
