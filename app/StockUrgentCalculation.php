<?php

namespace App;

class StockUrgentCalculation extends BaseModel
{
    //
    public static $fillableFields = [
	    	'urgent_calculation_cause_serial_no' => ['type' => 'list', 'list_reference' => 'urgent_cause', 'data' => ['route' => 'urgent_cause.index' ],],
	    	'positive_effect' => ['type' => 'boolean', 'value'=>0, 'switch' => ['YES', 'NO']],
	    	'from_value' => ['type' => 'float', 'validation'=> 'numeric'],
	    	'to_value' => ['type' => 'float', 'validation'=> 'numeric'],
            'is_active' => ['type' => 'boolean', 'value'=>0, 'switch' => ['YES', 'NO']],
    	];
    public static $createJS = 'create_stock_urgent_calculation.js';
    public static $editJS = 'create_stock_urgent_calculation.js';
    public static $viewJS = 'create_stock_urgent_calculation.js';

    public function urgent_cause()
    {
        return $this->belongsTo('\App\UrgentCause', 'urgent_calculation_cause_serial_no');
    }
}
