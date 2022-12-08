<?php

namespace App;

class StockEntrySource extends BaseModel
{
    //
    public static $fillableFields = [
	    	'source_name' => ['type' => 'text', 'validation'=> 'required|max:245'],
    	];
}
