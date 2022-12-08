<?php

namespace App;

class UserType extends BaseModel
{
    //
    public static $fillableFields = [
	    	'label_en' => ['type' => 'text', 'validation' => 'max:45|required'],
	    	'label_ar' => ['type' => 'text', 'validation' => 'max:45|required']
    	];
}
