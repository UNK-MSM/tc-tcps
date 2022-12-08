<?php

namespace App;

class Currency extends BaseModel
{
    //
    public static $fillableFields = [
            'label_en' => ['type' => 'text', 'validation'=> 'max:45|required'],
            'label_ar' => ['type' => 'text', 'validation'=> 'max:45|required'],
            'symbol' => ['type' => 'text', 'validation' => 'required|max:3'],
        ];
}
