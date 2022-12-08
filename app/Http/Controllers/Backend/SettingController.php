<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GenericRequest;

class SettingController extends GenericController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function update(GenericRequest $request)
    {
        $model = $this->model;
        $requestData = $request->all();

        $instance = $model::findOrFail($request->route($this->routeName));
        
        if($instance->update($requestData))
        {

            $responesData = ['message' => 'Settings was updated successfully', 'redirect_url' => $this->editRouteUrl];
            return response()->json($responesData, 200);
        }else
        {
            return response()->json("Unable to update this item", 400);
        }
    }

    public function clearStockLevelSettings(GenericRequest $request)
    {
        $model = $this->model;
        $requestData = $request->all();

        /*foreach ($requestData as $key => $value) {
            if ($value === "true")
            {
                $requestData[$key] = 1;
            }else
            {
                $requestData[$key] = null;
            }
        }

        \DB::statement('call clear_stock_level_settings(?, ?, ?, ?, ?, ?, ?, ?, ?)', $requestData);*/

        $params = '';
        foreach ($requestData as $key => $value) {
            if ($value === "true")
            {
                $params .= '1,';
            }else
            {
                $params .= 'null,';
            }
        }
        $params = substr($params, 0, -1);
        $status = \DB::statement('call clear_stock_level_settings('.$params.')');
        
        if($status)
        {

            $responesData = ['message' => 'Stock Level Settings was cleared successfully', 'redirect_url' => route('setting.edit', 1)];
            return response()->json($responesData, 200);
        }else
        {
            return response()->json("Unable to clear stock level settings!", 400);
        }
    }
}
