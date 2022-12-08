<?php

namespace App;

class Setting extends BaseModel
{

    protected $table = "system_settings";
    //
    public static $fillableFields = [
            'emergency_range' => ['type' => 'range_input_mx',],
            'normal_range' => ['type' => 'range_input_mx',],
            'difference_in_percent' => ['type' => 'integer', 'validation'=> 'numeric'],
            'addition_in_percent' => ['type' => 'integer', 'validation'=> 'numeric'],
            'minimum_in_percent' => ['type' => 'integer', 'validation'=> 'numeric'],
            'emtpy1' => ['type' => 'empty'],
            'invert_tuning_enabled' => ['type' => 'boolean', 'switch' => ['YES', 'NO'], ],
            'push_tuning_enabled' => ['type' => 'boolean', 'switch' => ['YES', 'NO'], ],

    	];

    public static $editJS = 'edit_settings.js';

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
            
        });
    }
}
