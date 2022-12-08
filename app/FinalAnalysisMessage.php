<?php

namespace App;

class FinalAnalysisMessage extends BaseModel
{
    //
    protected $primaryKey = 'id';
    public static $fillableFields = [
	    	'message_en' => ['type' => 'editor', ],
	    	'message_ar' => ['type' => 'editor', ],
	    	'evaluation_formula' => ['type' => 'textarea',],
    	];

    public static $insertable = false;
    public static $deletable = false;
}
