<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GenericRequest;

class PointController extends GenericController
{
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $model = $this->model;

        $ids = explode(',', $request->route($this->routeName));

        $rows = 0;
        \DB::beginTransaction();
        try
        {

	        foreach($ids as $id)
	        {
	        	$instance = \App\Point::where('membership_notifications.serial_no', $id)->first();

	            $user = \App\User::findOrFail($instance->user_serial_no);
	            if($instance->param_2 == -1)
	            {
	                $user->increment('points_balance', $instance->param_1);
	            }else
	            {
	                $user->decrement('points_balance', $instance->param_1);
	            }
	            $instance->delete();
	            $rows += 1;
	            \DB::commit();
	        }
        }catch(\Exception $ex)
        {
	            \DB::rollback();
            return response()->json($rows." items were deleted, but system can not complete the process", 400);
        }

        $message = "Item was deleted successfully";
        if($rows > 1)
        {
            $message = $rows." items were deleted successfully";
        }

        if($rows>0)
        {
            return response()->json($message, 200);
        }else
        {
            return response()->json("Unable to delete this item", 400);
        }
    }
}
