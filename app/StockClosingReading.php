<?php

namespace App;
use Illuminate\Database\Eloquent\Builder;

class StockClosingReading extends BaseModel
{

	protected $casts = [
        'date_closed' => 'date'
    ];
    public static $fillableFields = [
    //xview: dont show on datatable
    //list_reference to override system convention of listing
	    	'stock_serial_no' => ['type' => 'list', 'updatable' => false, 'data' => ['route_url' => 'stock_closing_reading/lcd' ],],// 'validation' => 'required_without:urgent_calculation_serial_no'],
            'empty' => ['type' => 'empty'],
	    	'date_closed' => ['type' => 'date', 'updatable' => false, 'width' => '15%',],// 'validation'=> 'required_with:file'],
	    	'open_selling_price' => ['type' => 'float', 'insertable' => false, 'validation'=> 'numeric'],
	    	'close_selling_price' => ['type' => 'float', ],// 'validation'=> 'required_without:urgent_calculation_serial_no|numeric'],
	    	'urgent_calculation_serial_no' => ['type' => 'list', 'insertable' => false, 'list_reference' => 'stock_urgent_calculation'],
	];

	public static $viewJS = 'view_closing_reading.js';
    public static $createJS = 'create_closing_reading.js';

    public function urgent_calculation()
    {
        return $this->belongsTo('\App\StockUrgentCalculation', 'urgent_calculation_serial_no');
    }

    public function getOpenSellingPriceAttribute($value)
    {
    	/*$stock_serial_no = $this->stock_serial_no;
    	$decimals = \Cache::get('stock_decimals_'.$stock_serial_no, function() use ($stock_serial_no) {
    		$temp_decimals = \App\Stock::find($stock_serial_no)->stock_price_decimal_places;
    		\Cache::put('stock_decimals_'.$stock_serial_no, $temp_decimals, 1440);
		    return $temp_decimals;
		  });*/
      $decimals = $this->stock_price_decimal_places;
    	return number_format($value, $decimals);
    }

    public function getCloseSellingPriceAttribute($value)
    {
        /*$stock_serial_no = $this->stock_serial_no;
        $decimals = \Cache::get('stock_decimals_'.$stock_serial_no, function() use ($stock_serial_no) {
            $temp_decimals = \App\Stock::find($stock_serial_no)->stock_price_decimal_places;
            \Cache::put('stock_decimals_'.$stock_serial_no, $temp_decimals, 1440);
            return $temp_decimals;
        });*/
        $decimals = $this->stock_price_decimal_places;
        return number_format($value, $decimals);
    }
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('market', function(Builder $builder) {
            $builder->select('stock_closing_readings.*', 'stocks.stock_market_serial_no', 'stocks.stock_price_decimal_places')->join('stocks', 'stocks.serial_no', '=', 'stock_closing_readings.stock_serial_no');
        });
    }
}
