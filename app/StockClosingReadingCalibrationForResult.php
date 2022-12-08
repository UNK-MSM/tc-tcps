<?php

namespace App;

class StockClosingReadingCalibrationForResult extends BaseModel
{
    //
    protected $table = "stock_closing_readings_calibration";

	protected $hidden = [
	    	'direction',
	    	'greatest_probability', 'greatest_probability_direction',
	    	'greatest_probability_error_rate',
	    	'predicted_rising_selling_closing_price_error_rate',
	    	'predicted_falling_selling_closing_price_error_rate',
	    	'greatest_probability_validity',
	    	'predicted_rising_selling_closing_price_validity',
	    	'predicted_falling_selling_closing_price_validity',
		];
    protected $appends = [
	    	'direction',
	    	'date_closed_milliseconds',
	    	'greatest_probability', 'greatest_probability_direction',
	    	'greatest_probability_error_rate',
	    	'predicted_rising_selling_closing_price_error_rate',
            'predicted_falling_selling_closing_price_error_rate',
	    	'predicted_general_selling_closing_price_error_rate',
	    	'greatest_probability_validity',
	    	'predicted_rising_selling_closing_price_validity',
            'predicted_falling_selling_closing_price_validity',
            'predicted_general_selling_closing_price_validity',
            'predicted_general_selling_closing_price',
	    ];
    protected $dates = ['date_closed'];


    public function getPredictedGeneralSellingClosingPriceAttribute()
    {
        $value = $this->predicted_rising_selling_closing_price;
        $rate = $this->predicted_rising_selling_closing_price_rate;
        if($this->predicted_stable_selling_closing_price_rate > $rate)
        {
            $value = $this->predicted_stable_selling_closing_price;
            $rate = $this->predicted_stable_selling_closing_price_rate;
        }
        if($this->predicted_falling_selling_closing_price_rate > $rate)
        {
            $value = $this->predicted_falling_selling_closing_price;
            $rate = $this->predicted_falling_selling_closing_price_rate;
        }
        return floatval($value);
    }

    public function getCloseSellingPriceAttribute($value)
    {
    	return floatval($value);
    }
    public function getDateClosedMillisecondsAttribute()
    {
    	return $this->date_closed->timestamp * 1000;
    }

    public function getDirectionAttribute()
    {
    	if($this->open_selling_price == $this->close_selling_price)
    	{
    		return 'لاتغيير';
    	}
    	return $this->open_selling_price > $this->close_selling_price? 'انخفاض':'ارتفاع';
    }

    public function getGreatestProbabilityAttribute()
    {
        $max_rate = $this->top_positive_rate;
        $max_value = $this->top_positive_value;
        if($this->mid_positive_rate > $max_rate)
        {
            $max_rate = $this->mid_positive_rate;
            $max_value = $this->mid_positive_value;
        }
        if($this->bottom_positive_rate > $max_rate)
        {
            $max_rate = $this->bottom_positive_rate;
            $max_value = $this->bottom_positive_value;
        }
        if($this->stable_rate > $max_rate)
        {
            $max_rate = $this->stable_rate;
            $max_value = $this->stable_value;
        }
        if($this->bottom_negative_rate > $max_rate)
        {
            $max_rate = $this->bottom_negative_rate;
            $max_value = $this->bottom_negative_value;
        }
        if($this->mid_negative_rate > $max_rate)
        {
            $max_rate = $this->mid_negative_rate;
            $max_value = $this->mid_negative_value;
        }
        if($this->top_negative_rate > $max_rate)
        {
            $max_rate = $this->top_negative_rate;
            $max_value = $this->top_negative_value;
        }
        $this->max_value = $max_value;
    	return $max_value;
    }

    public function getGreatestProbabilityDirectionAttribute()
    {
    	$greatest_probability = $this->max_value;
    	if($this->open_selling_price == $greatest_probability)
    	{
    		return 'لاتغيير';
    	}
    	return $greatest_probability < $this->open_selling_price? 'انخفاض':'ارتفاع';
    }

    public function getGreatestProbabilityErrorRateAttribute()
    {
    	$greatest_probability = $this->max_value;
    	if(empty($greatest_probability))
    	{
    		return 1;
    	}
    	return ($greatest_probability - $this->close_selling_price)/$greatest_probability;
    	//return round((($greatest_probability - $this->close_selling_price)/$greatest_probability)*100, 2);
    }

    public function getPredictedRisingSellingClosingPriceErrorRateAttribute()
    {
    	if(empty($this->predicted_rising_selling_closing_price))
    	{
    		return 1;
    	}
    	return ($this->predicted_rising_selling_closing_price - $this->close_selling_price)/$this->predicted_rising_selling_closing_price;
    	//return round((($this->predicted_rising_selling_closing_price - $this->close_selling_price)/$this->predicted_rising_selling_closing_price)*100, 2);
    }

    public function getPredictedFallingSellingClosingPriceErrorRateAttribute()
    {
    	if(empty($this->predicted_falling_selling_closing_price))
    	{
    		return 1;
    	}
    	return ($this->predicted_falling_selling_closing_price - $this->close_selling_price)/$this->predicted_falling_selling_closing_price;
    	//return round((($this->predicted_falling_selling_closing_price - $this->close_selling_price)/$this->predicted_falling_selling_closing_price)*100, 2);
    }

    public function getPredictedGeneralSellingClosingPriceErrorRateAttribute()
    {
        $value = $this->predicted_falling_selling_closing_price;
        if($this->predicted_rising_selling_closing_price_rate > $this->predicted_falling_selling_closing_price_rate)
        {
            $value = $this->predicted_rising_selling_closing_price;
        }
        if(empty($value))
        {
            return 1;
        }
        return ($value - $this->close_selling_price)/$value;
        //return round((($this->predicted_falling_selling_closing_price - $this->close_selling_price)/$this->predicted_falling_selling_closing_price)*100, 2);
    }

    public function getGreatestProbabilityValidityAttribute()
    {
    	$greatest_probability = $this->max_value;
    	return ($greatest_probability > $this->open_selling_price && $this->close_selling_price > $this->open_selling_price) || ($greatest_probability < $this->open_selling_price && $this->close_selling_price < $this->open_selling_price) || ($greatest_probability == $this->open_selling_price && $this->close_selling_price == $this->open_selling_price);
    }

    public function getPredictedRisingSellingClosingPriceValidityAttribute()
    {
    	return ($this->predicted_rising_selling_closing_price > $this->open_selling_price && $this->close_selling_price > $this->open_selling_price) || ($this->predicted_rising_selling_closing_price < $this->open_selling_price && $this->close_selling_price < $this->open_selling_price) || ($this->predicted_rising_selling_closing_price == $this->open_selling_price && $this->close_selling_price == $this->open_selling_price);
    }

    public function getPredictedFallingSellingClosingPriceValidityAttribute()
    {
        return ($this->predicted_falling_selling_closing_price > $this->open_selling_price && $this->close_selling_price > $this->open_selling_price) || ($this->predicted_falling_selling_closing_price < $this->open_selling_price && $this->close_selling_price < $this->open_selling_price) || ($this->predicted_falling_selling_closing_price == $this->open_selling_price && $this->close_selling_price == $this->open_selling_price);
    }

    public function getPredictedGeneralSellingClosingPriceValidityAttribute()
    {
        $value = $this->predicted_falling_selling_closing_price;
        if($this->predicted_rising_selling_closing_price_rate > $this->predicted_falling_selling_closing_price_rate)
        {
            $value = $this->predicted_rising_selling_closing_price;
        }
        return ($value > $this->open_selling_price && $this->close_selling_price > $this->open_selling_price) || ($value < $this->open_selling_price && $this->close_selling_price < $this->open_selling_price) || ($value == $this->open_selling_price && $this->close_selling_price == $this->open_selling_price);
    }

}
