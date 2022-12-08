<?php

namespace App;

class News extends BaseModel
{
    //
    public static $fillableFields = [
    //xview: dont show on datatable
    //list_reference to override system convention of listing
	    	'title_ar' => ['type' => 'text', 'validation'=> 'required|max:145'],
	    	'title_en' => ['type' => 'text', 'validation'=> 'required|max:145'],
	    	'body_ar' => ['type' => 'textarea'],
	    	'body_en' => ['type' => 'textarea'],
	    	'related_market_serial_no' => ['type' => 'list', 'list_reference' => 'StockMarket'],
            
            'related_stocks' => ['type' => 'group_list', 'multiselect' => true, 'list_reference'=>'Stock', 'list_group_parent'=>'StockMarket', 'relation' => 'many-to-many', 'xview'=>true],
    	];

    public static $relationalFields = ['related_stocks'];

    public static $createJS = 'create_news.js';
    public static $editJS = 'create_news.js';

    public function related_stocks()
    {
        return $this->belongsToMany('\App\Stock', 'news_stock', 'news_serial_no', 'stock_serial_no');
    }

    public static function boot()
    {
    	parent::boot();

    	static::creating(function($instance){
    		$instance->added_date = \Carbon\Carbon::now();
            if($instance->related_market_serial_no == '')
            {
                $instance->related_market_serial_no = null;
            }
    	});

        static::updating(function ($instance) {
            if($instance->related_market_serial_no == '')
            {
                $instance->related_market_serial_no = null;
            }
            
        });
    }
}
