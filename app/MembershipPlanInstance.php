<?php

namespace App;

class MembershipPlanInstance extends BaseModel
{
    //
    protected $table = "v_membership_plans_instances";
    public static $fillableFields = [
	    	'membership_serial_no' => ['type' => 'list', 'updateable' => false, 'insertable' => false],
	    	'username' => ['type' => 'text', 'updateable' => false, 'insertable' => false],
	    	'mobile_no' => ['type' => 'text', 'updateable' => false, 'insertable' => false],
	    	'membership_plan_serial_no' => ['type' => 'list', 'updateable' => false, 'insertable' => false],
	    	'demo' => ['type' => 'boolean', 'updateable' => false, 'insertable' => false],
	    	'payment_reference' => ['type' => 'text', 'updateable' => false, 'insertable' => false],
	    	'plan_price' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'total_price' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'discount' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'promotion_discount' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'period_in_days' => ['type' => 'integer', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'status' => ['type' => 'list', 'list_reference' => 'membership_plan_instance_state', 'validation'=> 'required'],
	    	'monthly_fees' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'monthly_fees_3_months' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'monthly_fees_6_months' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'monthly_fees_12_months' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'monthly_fees_24_months' => ['type' => 'float', 'validation'=> 'numeric', 'updateable' => false, 'insertable' => false],
	    	'date_added' => ['type' => 'date', 'updateable' => false, 'insertable' => false],
	    	'start_date' => ['type' => 'date', 'updateable' => false, 'insertable' => false,],
	    	'expire_date' => ['type' => 'date'],
    	];

    public static $insertable = false;
    public static $deletable = false;
    
   	public static $customActions = [
   		//'stock_urgent_calculations' => ['type' => 'redirect', 'icon' => 'icon-shield', 'route' => 'stock.stock_urgent_calculation.index', 'title' => 'Urgent Calculations'],
   		'membership_plan_instance_detail' => ['type' => 'redirect', 'class' => 'membership_plan_instance_detail font-purple', 'icon' => 'fa fa-list-ul', 'route' => 'membership_plan_instance.membership_plan_instance_detail.index', 'title' => 'Details']
   	];

   	public function membership_plan_instance_details()
   	{
   		return $this->hasMany('\App\MembershipPlanInstanceDetail', 'membership_plan_instance_serial_no');
   	}

   	public function membership_plan()
   	{
   		return $this->belongsTo('\App\MembershipPlan', 'membership_plan_serial_no');
   	}
}
