<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    //
    public static $formVerticalSections = 2;
    protected $primaryKey = 'serial_no';
    public $timestamps = false;
    public static $customForm = true;
    public static $formId = 'generic_form';
    public static $deletable = true;
    public static $editable = true;

    protected $fillable;

    public function __construct(array $attributes = array())
	{
		
		//get Laravel fillable structure from my custom fillableField structure
        if(isset(static::$fillableFields))
        {
            $data = array();
            foreach(static::$fillableFields as $key => $value)
            {
                if(!((isset($value['type']) && in_array($value['type'], ['multiselect', 'tabular'])) || (isset($value['multiselect']) && $value['multiselect'])))
                {
                    if(isset($value['type']) && in_array($value['type'], ['range_slider', 'range_input']))
                    {
                        $data[] = $key.'_from';
                        $data[] = $key.'_to';
                    }else if(isset($value['type']) && in_array($value['type'], ['range_input_mx']))
                    {
                        $data[] = $key.'_min';
                        $data[] = $key.'_max';
                    }else
                    {
                        $data[] = $key;
                    }
                }
            }
        	$this->fillable = $data;
        }
	    parent::__construct($attributes);
	}

    public static function getLabel()
    {
        $lang = \Session::get('lang', 'en');
        return 'label_'.$lang;
    }
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
