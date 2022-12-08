<?php

namespace App;

class Stock extends BaseModel
{
    //
    public static $fillableFields = [
    //xview: dont show on datatable
    //list_reference to override system convention of listing
	    	'logo' => ['type' => 'image', 'validation' => 'image|max:200'],
	    	'empty' => ['type'=>'empty'],
	    	'stock_name_en' => ['type' => 'text', 'validation'=> 'max:145'],
	    	'stock_name_ar' => ['type' => 'text', 'validation'=> 'max:145'],
	    	'description_en' => ['type' => 'textarea'],
	    	'description_ar' => ['type' => 'textarea'],
	    	'stock_market_serial_no' => ['type' => 'list', 'validation' => 'required'],
	    	'stock_symbol' => ['type' => 'text', 'validation'=> 'required'],
	    	'stock_type_serial_no' => ['type' => 'list', 'validation' => 'required'],
	    	'active' => ['type' => 'boolean', 'xview' => true, 'switch' => ['YES', 'NO'], ],
	    	'custom_membership_price' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'currency_serial_no' => ['type' => 'list', 'xview' => true],
	    	'regular_stock' => ['type' => 'boolean', 'xview' => true, 'switch' => ['YES', 'NO'], ],
            'irp_calculation_window' => ['type' => 'float', 'validation'=> 'numeric'],
	    	//'urgent_causes' => ['type' => 'multiselect', 'list_reference' => 'urgent_cause'],
            //'emtpy21' => ['type' => 'empty'],
	    	'stock_price_decimal_places' => ['type' => 'integer', 'xview' => true, 'validation'=> 'required|numeric'],
            'display_stock_price_decimal_places' => ['type' => 'integer', 'xview' => true, 'validation'=> 'required|numeric'],
    		'stock_urgent_calculations' => ['type' => 'urgent-calculation-tabular', 'relation' => 'one-to-many'],
	    	'emtpy0' => ['type' => 'empty'],
	    	'activated_up_levels_count' => ['type' => 'integer', 'validation'=> 'required|max:1|digits:1|same:activated_down_levels_count'],
	    	'activated_down_levels_count' => ['type' => 'integer', 'validation'=> 'required|max:1|digits:1|same:activated_up_levels_count', 'xview' => true],
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

            'label_2' => ['type' => 'label', 'value' => 'Calculation Settings', 'class' => 'calculation-settings-label'],
            'emtpy21' => ['type' => 'empty'],
            'emergency_range' => ['type' => 'range_input_mx',],
            'normal_range' => ['type' => 'range_input_mx',],
            'difference_in_percent' => ['type' => 'integer', 'xview' => true, 'validation'=> 'numeric'],
            'addition_in_percent' => ['type' => 'integer', 'xview' => true, 'validation'=> 'numeric'],
            'minimum_in_percent' => ['type' => 'integer', 'xview' => true, 'validation'=> 'numeric'],
            'emtpy22' => ['type' => 'empty'],
            'invert_tuning_enabled' => ['type' => 'radio_buttons', 'xview' => true, 'list' => [1 => 'Yes', 0 => 'No', 2 => ''], ],
            'push_tuning_enabled' => ['type' => 'radio_buttons', 'xview' => true, 'list' => [1 => 'Yes', 0 => 'No', 2 => ''], ],
    	];

   	public static $customForm = true;
    public static $viewJS = 'view_stocks.js';
   	public static $customActions = [
   		//'stock_urgent_calculations' => ['type' => 'redirect', 'icon' => 'icon-shield', 'route' => 'stock.stock_urgent_calculation.index', 'title' => 'Urgent Calculations'],
   		'recalculate' => ['type' => 'ajax', 'class' => 'recalculate', 'icon' => 'fa fa-refresh', 'route' => 'stock.recalculate', 'title' => 'Recalculate'],
   		'calibration' => ['type' => 'redirect', 'class' => 'calibration font-yellow-gold', 'icon' => 'fa fa-sliders', 'route' => 'stock.calibration', 'title' => 'Calibration'],
   		'prediction' => ['type' => 'redirect', 'class' => 'prediction font-purple', 'icon' => 'fa fa-line-chart', 'route' => 'stock.results', 'title' => 'Prediction'],
        'refresh_last_stock_prediction' => ['type' => 'ajax', 'class' => 'font-yellow-mint refresh_last_stock_prediction', 'icon' => 'fa fa-dot-circle-o', 'route' => 'stock.refresh_last_stock_prediction', 'title' => 'Refresh last stock prediction'],
   	];
    public static $orderBy = "[[2, 'asc']]";

    public static $relationalFields = ['stock_urgent_calculations'];

    public function getLogoAttribute($value)
    {
    	if(!empty($value))
    	{
    		$logo = '<img src="'.asset('img/logo/r/'.$value).'" style="height: 35px;" />';
        	return $logo;
    	}else
    	{
        	return '';
    	}
    }

    public function stock_urgent_calculations()
    {
    	return $this->hasMany('\App\StockUrgentCalculation', 'stock_serial_no');
    }

    public function stock_urgent_causes()
    {
    	return $this->belongsToMany('\App\UrgentCause', 'stock_urgent_calculations', 'stock_serial_no', 'urgent_calculation_cause_serial_no');
    }

    public function stock_closing_readings()
    {
        return $this->hasMany('\App\StockClosingReading', 'stock_serial_no');
    }

    public function stock_closing_reading_for_results()
    {
        return $this->hasMany('\App\StockClosingReadingForResult', 'stock_serial_no');
    }

    public function stock_market()
    {
        return $this->belongsTo('\App\StockMarket', 'stock_market_serial_no');
    }

    public function calibration()
    {
    	return $this->hasOne('\App\StockCalibration', 'stock_serial_no');
    }

    public function getStockPriceDecimalPlacesAttribute($value)
    {
    	if(isset($value))
    	{
    		return $value;
    	}
    	return $this->stock_market->stock_price_decimal_places;
    }

    public function getStockNameAttribute()
    {
        $lang = \Session::get('lang', 'en');
        if($lang == 'ar')
        {
            return $this->stock_name_ar;
        }
        return $this->stock_name_en;
    }
   	public static function getLabel()
   	{
        $lang = \Session::get('lang', 'en');
        return 'stock_name_'.$lang;
   	}
    public static function boot()
    {
        parent::boot();

        static::updating(function ($instance) {
            if($instance->difference_in_percent == '')
            {
                $instance->difference_in_percent = null;
            }
            if($instance->addition_in_percent == '')
            {
                $instance->addition_in_percent = null;
            }
            if($instance->minimum_in_percent == '')
            {
                $instance->minimum_in_percent = null;
            }
            if($instance->normal_range_min == '')
            {
                $instance->normal_range_min = null;
            }
            if($instance->normal_range_max == '')
            {
                $instance->normal_range_max = null;
            }
            if($instance->emergency_range_min == '')
            {
                $instance->emergency_range_min = null;
            }
            if($instance->emergency_range_max == '')
            {
                $instance->emergency_range_max = null;
            }
            
        });
    }
}
