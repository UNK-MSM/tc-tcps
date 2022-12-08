<?php

namespace App;

class UserStatus extends BaseModel
{
	protected $table = 'user_status';
    //
    public static $fillableFields = [
	    	'label_en' => ['type' => 'text', 'validation'=> 'max:45'],
	    	'label_ar' => ['type' => 'text', 'validation'=> 'max:45'],
	    	'login_blocking' => ['type' => 'boolean', 'switch' => ['YES', 'NO'], 'validation'=> 'required']
    	];
}
