<?php

namespace App;

class StockMarket extends BaseModel
{
    public static $fillableFields = [
	    	'logo' => ['type' => 'image', 'validation' => 'image|max:200'],
	    	'empty' => ['type'=>'empty'],
	    	'label_en' => ['type' => 'text', 'validation'=> 'max:245'],
	    	'label_ar' => ['type' => 'text', 'validation'=> 'max:245'],
	    	'description_en' => ['type' => 'textarea'],
	    	'description_ar' => ['type' => 'textarea'],
	    	'regular_working_days' => ['type' => 'weekline', 'xview' => true, 'class' => 'weekline', 'validation'=> 'required'],
	    	'official_date_format' => ['type' => 'text', 'xview' => true, 'validation'=> 'max:45|required'],
	    	'official_website_url' => ['type' => 'url', 'validation'=> 'url'],
	    	'close_prices_rss_url' => ['type' => 'url', 'validation'=> 'url'],
	    	'rss_pulling_time' => ['type' => 'time', 'xview' => true],
	    	'stock_rate' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'stock_price_decimal_places' => ['type' => 'integer', 'validation'=> 'required|numeric'],
            'market_price' => ['type' => 'float', 'validation'=> 'numeric'],
	    	'maximum_limit_up' => ['type' => 'float', 'validation'=> 'numeric'],
	    	'maximum_limit_down' => ['type' => 'float', 'validation'=> 'numeric'],
            'active' => ['type' => 'boolean', 'switch' => ['YES', 'NO'], ],
    	];
    //public static $formId = 'stock_market_form';
    public static $createJS = 'create_stock_market.js';
    public static $editJS = 'create_stock_market.js';
    public static $orderBy = "[[2, 'asc']]";

    protected $appends = ['label_en_url', 'label_ar_url'];

    public function getLogoAttribute($value)
    {
    	if(!empty($value))
    	{
    		$logo = '<img src="'.asset('img/logo/r/'.$value).'" style="height: 35px;" />';
    		if(!empty($this->official_website_url))
    		{
    			$logo = '<a href="'.$this->official_website_url.'" target="_blank">'.$logo.'</a>';
    		}
        	return $logo;
    	}else
    	{
        	return '';
    	}
    }

    public function getLabelEnUrlAttribute()
    {
        $value = $this->attributes['label_en'];
		if(!empty($this->official_website_url))
		{
			$value = '<a href="'.$this->official_website_url.'" target="_blank">'.$this->attributes['label_en'].'</a>';
		}
    	return $value;
    }

    public function getLabelArUrlAttribute()
    {
        $value = $this->attributes['label_ar'];
        if(!empty($this->official_website_url))
        {
            $value = '<a href="'.$this->official_website_url.'" target="_blank">'.$this->attributes['label_ar'].'</a>';
        }
        return $value;
    }

    public function stocks()
    {
        return $this->hasMany('\App\Stock', 'stock_market_serial_no');
    }

    public function stock_closing_readings()
    {
        return $this->hasManyThrough('\App\StockClosingReading', '\App\Stock', 'stock_market_serial_no', 'stock_serial_no');
    }

    public function stock_closing_reading_for_results()
    {
        return $this->hasManyThrough('\App\StockClosingReadingForResult', '\App\Stock', 'stock_market_serial_no', 'stock_serial_no');
    }

    public function getLabelAttribute()
    {
        $lang = \Session::get('lang', 'en');
        if($lang == 'ar')
        {
            return $this->label_ar;
        }
        return $this->label_en;
    }
}
