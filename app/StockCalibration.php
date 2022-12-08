<?php

namespace App;

class StockCalibration extends BaseModel
{
    //
    protected $table = "stocks_calibration";
    public static $fillableFields = [
    //xview: dont show on datatable
    //list_reference to override system convention of listing
    		'stock_serial_no' => ['type' => 'list'],
    		'start_from_date' => ['type' => 'date'],
	    	'activated_up_levels_count' => ['type' => 'integer', 'validation'=> 'required|max:1|digits:1|same:activated_down_levels_count'],
	    	'activated_down_levels_count' => ['type' => 'integer', 'validation'=> 'required|max:1|digits:1|same:activated_up_levels_count'],
	    	'label_1' => ['type' => 'label', 'value' => 'Levels'],
	    	'emtpy1' => ['type' => 'empty'],
	    	'u9' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy2' => ['type' => 'empty'],
	    	'u8' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy3' => ['type' => 'empty'],
	    	'u7' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy4' => ['type' => 'empty'],
	    	'u6' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy5' => ['type' => 'empty'],
	    	'u5' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy6' => ['type' => 'empty'],
	    	'u4' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy7' => ['type' => 'empty'],
	    	'u3' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy8' => ['type' => 'empty'],
	    	'u2' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy9' => ['type' => 'empty'],
	    	'u1' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy10' => ['type' => 'empty'],
	    	's' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy11' => ['type' => 'empty'],
	    	'd1' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy12' => ['type' => 'empty'],
	    	'd2' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy13' => ['type' => 'empty'],
	    	'd3' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy14' => ['type' => 'empty'],
	    	'd4' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy15' => ['type' => 'empty'],
	    	'd5' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy16' => ['type' => 'empty'],
	    	'd6' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy17' => ['type' => 'empty'],
	    	'd7' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy18' => ['type' => 'empty'],
	    	'd8' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy19' => ['type' => 'empty'],
	    	'd9' => ['type' => 'range_input', 'class'=>'levels'],
	    	'emtpy20' => ['type' => 'empty'],
    	];

   	public static $customForm = true;
    public static $viewJS = 'view_stocks.js';

    public function stock()
    {
    	return $this->belongsTo('\App\Stocl', 'stock_serial_no');
    }
}
