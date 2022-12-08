<?php

namespace App;

class UrgentCause extends BaseModel
{
    //
    public static $fillableFields = [
	    	'label_en' => ['type' => 'text', 'validation'=> 'required|max:245'],
	    	'label_ar' => ['type' => 'text', 'validation'=> 'required|max:245'],
	    	'description_en' => ['type' => 'textarea'],
	    	'description_ar' => ['type' => 'textarea'],
	    	'from_value' => ['type' => 'float', 'validation'=> 'required|numeric'],
	    	'to_value' => ['type' => 'float', 'validation'=> 'required|numeric'],
            'positive_effect' => ['type' => 'boolean', 'value'=>0, 'switch' => ['YES', 'NO']],
    	];

    protected $appends = ['label'];
    public static function boot()
    {
    	parent::boot();

    	static::created(function($instance){
    		foreach(\App\Stock::all() as $stock)
    		{
    			$data = array();
    			$data['urgent_calculation_cause_serial_no'] = $instance->serial_no;
    			$data['positive_effect'] = $instance->positive_effect;
    			$data['from_value'] = $instance->from_value;
    			$data['to_value'] = $instance->to_value;
    			$data['is_active'] = true;
    			$stock->stock_urgent_calculations()->create($data);
    		}
    	});
    }

    public function stock_urgent_calculations()
    {
    	return $this->hasMany('\App\StockUrgentCalculation', 'urgent_calculation_cause_serial_no');
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
