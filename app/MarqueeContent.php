<?php

namespace App;

class MarqueeContent extends BaseModel
{
    //
    public static $fillableFields = [
	    	'content' => ['type' => 'textarea', 'validation'=> 'required', 'xview'=>false],
	    	'display_speed' => ['type' => 'integer',],
    	];
}
