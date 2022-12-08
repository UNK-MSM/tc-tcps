<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GenericRequest;

class StockController extends GenericController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $controls = \App\Stock::$fillableFields;

        $data = generateFormFields($controls);
        unset($data['rows'][6]['stock_urgent_calculations']);

        $data['title'] = "Add New Stock";
        $data['description'] = "create new stock";

        $routeNameWithAction = \Request::route()->getName();
        $routeUrl = route(str_replace('create', 'store', $routeNameWithAction));
        $data['storeRouteUrl'] = $routeUrl;
        $data['action_type'] = 'create';

        return view('Admin::stock_create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GenericRequest $request)
    {
        $data = $request->all();
        if(!isset($data['active']))
        {
            $data['active'] = 0;
        }
        if(!isset($data['regular_stock']))
        {
            $data['regular_stock'] = 0;
        }
        if(!isset($data['irp_calculation_window']) || $data['irp_calculation_window'] == '')
        {
            $data['irp_calculation_window'] = null;
        }
        $data['activated_down_levels_count'] = $data['activated_up_levels_count'];
        /*for($i = 1; $i <= $data['activated_up_levels_count']; $i++)
        {
            $upRange = explode(';', $data['u'.$i]);
            $data['u'.$i.'_from'] = $upRange[0];
            $data['u'.$i.'_to'] = $upRange[1];

            $upRange = explode(';', $data['d_u'.$i]);
            $data['d_u'.$i.'_from'] = $upRange[0];
            $data['d_u'.$i.'_to'] = $upRange[1];
        }*/
        $data["u".$data['activated_up_levels_count']."_to"] = null;
        $data["d".$data['activated_up_levels_count']."_to"] = null;
        for($i = ($data['activated_up_levels_count']+1); $i <= 9; $i++)
        {
            //$upRange = explode(';', $data['d'.$i]);
            unset($data["u".$i."_from"]);
            unset($data["u".$i."_to"]);
            unset($data["d_u".$i."_from"]);
            unset($data["d_u".$i."_to"]);

            unset($data["d".$i."_from"]);
            unset($data["d".$i."_to"]);
            unset($data["d_d".$i."_from"]);
            unset($data["d_d".$i."_to"]);
        }
        /*
        $upRange = explode(';', $data['s']);
        $data['s_from'] = $upRange[0];
        $data['s_to'] = $upRange[1];

        $upRange = explode(';', $data['d_s']);
        $data['d_s_from'] = $upRange[0];
        $data['d_s_to'] = $upRange[1];
        */
        $file = \Request::file('logo');
        if ($request->hasFile('logo')) {
            if(!$file->isValid()){
                return response()->json('Logo file is not valid!', 400);
            }

            $extension = $file->getClientOriginalExtension();
            $file_name = \ Carbon\Carbon::now()->timestamp.'_'.str_random(5);
            $file_full_name = $file_name.'.'.$extension;
            $result = \Storage::disk('logos')->put($file_full_name,  \File::get($file));
            $result = \Image::make(public_path('img/logo/d/'.$file_full_name))
                                ->resize(null, 35, function ($constraint) {$constraint->aspectRatio();})
                                ->save(public_path('img/logo/r/'.$file_full_name));

            $data["logo"] = $file_full_name;
        }

        \DB::beginTransaction();
        try
        {

            $instance = \App\Stock::create($data);
            //\Cache::put('stock_decimals_'.$instance->serial_no, $instance->stock_price_decimal_places, 86400);

            foreach(\App\UrgentCause::all() as $urgentCause)
            {
                $stockUrgentCalculation = new \App\StockUrgentCalculation;
                $stockUrgentCalculation->stock_serial_no = $instance->serial_no;
                $stockUrgentCalculation->urgent_calculation_cause_serial_no = $urgentCause->serial_no;
                $stockUrgentCalculation->positive_effect = $urgentCause->positive_effect;
                $stockUrgentCalculation->from_value = $urgentCause->from_value;
                $stockUrgentCalculation->to_value = $urgentCause->to_value;
                $stockUrgentCalculation->is_active = true;

                $stockUrgentCalculation->save();
            }

            if(!empty(\App\Stock::$relationalFields))
            {
                $relationalFields = $request->only(\App\Stock::$relationalFields);
                foreach($relationalFields as $key => $value)
                {
                    if(isset($value))
                    {
                        if(\App\Stock::$fillableFields[$key]['relation'] === 'one-to-many')
                        {
                            foreach($value as $valueKey => $valueValue)
                            {
                                if(is_string($valueValue))
                                {
                                    $valueValue = [\App\Stock::$fillableFields[$key]['list_key'] => $valueValue];
                                }
                                $instance->$key()->create($valueValue);
                            }
                        }else
                        {
                            $instance->$key()->attach($value);
                        }
                    }
                }
            }
            \DB::commit();
        }catch(\Exception $ex)
        {
            \DB::rollback();
            \Log::error($ex);
            return response()->json('Unable to create stock and attach urgent causes', 403);
        }


        $flashMessage = array();
        \Session::flash('status', 'success');
        \Session::flash('message', 'Item was added successfully!');
        return redirect()->route('stock.index');
        /*if($request->ajax())
        {
            $routeNameWithAction = \Request::route()->getName();
            $routeUrl = route(str_replace('store', 'index', $routeNameWithAction));

            $responesData = ['message' => 'Item was added successfully', 'redirect_url' => $routeUrl];
            return response()->json($responesData, 200);
        }else
        {

        }*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $controls = \App\Stock::$fillableFields;
        $instance = \App\Stock::whereRaw('1=1');
        $instance = $instance->with('stock_urgent_calculations')->with('stock_urgent_calculations.urgent_cause')->findOrFail($request->route('stock'));

        $data = generateFormFields($controls, $instance->toArray());

        $data['title'] = "Edit Stock ".$instance->stock_name_en;
        $data['description'] = "";

        $routeNameWithAction = \Request::route()->getName();
        $routeUrl = route(str_replace('edit', 'update', $routeNameWithAction), $request->route('stock'));
        $data['storeRouteUrl'] = $routeUrl;
        $data['method'] = 'PUT';
        $data['action_type'] = 'edit';

        //$data['page_scripts'] = [\App\Stock::$editJS];

        return view('Admin::stock_create', $data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GenericRequest $request)
    {
        $model = $this->model;

        $instance = $model::findOrFail($request->route('stock'));

        $data = $request->all();
        if(isset($data['push_tuning_enabled']) && !in_array($data['push_tuning_enabled'], [1, 0]))
        {
            $data['push_tuning_enabled'] = null;
        }

        if(isset($data['invert_tuning_enabled']) && !in_array($data['invert_tuning_enabled'], [1, 0]))
        {
            $data['invert_tuning_enabled'] = null;
        }

        if(!isset($data['active']))
        {
            $data['active'] = 0;
        }
        if(!isset($data['regular_stock']))
        {
            $data['regular_stock'] = 0;
        }
        if(!isset($data['irp_calculation_window']) || $data['irp_calculation_window'] == '')
        {
            $data['irp_calculation_window'] = null;
        }
        $data['activated_down_levels_count'] = $data['activated_up_levels_count'];

        $data["u".$data['activated_up_levels_count']."_to"] = null;
        $data["d".$data['activated_up_levels_count']."_to"] = null;
        for($i = ($data['activated_up_levels_count']+1); $i <= 9; $i++)
        {
            $data["u".$i."_from"] = null;
            $data["u".$i."_to"] = null;
            $data["d_u".$i."_from"] = null;
            $data["d_u".$i."_to"] = null;

            $data["d".$i."_from"] = null;
            $data["d".$i."_to"] = null;
            $data["d_d".$i."_from"] = null;
            $data["d_d".$i."_to"] = null;
        }

        $file = \Request::file('logo');
        if ($request->hasFile('logo')) {
            if(!$file->isValid()){
                return response()->json('Logo file is not valid!', 400);
            }

            $extension = $file->getClientOriginalExtension();
            $file_name = \ Carbon\Carbon::now()->timestamp.'_'.str_random(5);
            $file_full_name = $file_name.'.'.$extension;
            $result = \Storage::disk('logos')->put($file_full_name,  \File::get($file));
            $result = \Image::make(public_path('img/logo/d/'.$file_full_name))
                                ->resize(null, 35, function ($constraint) {$constraint->aspectRatio();})
                                ->save(public_path('img/logo/r/'.$file_full_name));

            $data["logo"] = $file_full_name;
        }

        if(!empty($model::$relationalFields))
        {
            $relationalFields = $request->only($model::$relationalFields);
            foreach($relationalFields as $key => $value)
            {
                if(isset($value))
                {
                    if(\App\Stock::$fillableFields[$key]['relation'] === 'one-to-many')
                    {
                        //TODO delete current records
                        foreach($value as $valueKey => $valueValue)
                        {
                            if(is_string($valueValue))
                            {
                                $valueValue = [\App\Stock::$fillableFields[$key]['list_key'] => $valueValue];
                            }
                            $instance->$key()->create($valueValue);
                        }
                    }else
                    {
                        $instance->$key()->sync($value);
                    }
                }
            }
        }

        if($instance->update($data))
        {
            \DB::statement('call refresh_last_stock_prediction('.$instance->serial_no.')');

            //\Cache::put('stock_decimals_'.$instance->serial_no, $instance->stock_price_decimal_places, 86400);

            $flashMessage = array();
            \Session::flash('status', 'success');
            \Session::flash('message', 'Item was updated successfully!');
            return redirect()->route('stock.index');

            /*$responesData = ['message' => 'Item was updated successfully', 'redirect_url' => route('stock.index')];
            return response()->json($responesData, 200);*/
        }else
        {
            $flashMessage = array();
            \Session::flash('status', 'danger');
            \Session::flash('message', 'Unable to update stock!');
            return redirect()->back();
            //return response()->json("Unable to update this item", 400);
        }
    }

    public function getStockLevels(Request $request)
    {
        $id = $request->get('stock_serial_no');
        $stock = \App\Stock::findOrFail($id);
        return $stock;
    }

    public function refreshLastStockPrediction(Request $request, $stock_serial_no)
    {
        $dbResponse = \DB::statement('call refresh_last_stock_prediction('.$stock_serial_no.')');
        if($dbResponse)
        {
            return response()->json('Stock refreshed successfully', 200);
        }else
        {
            return response()->json("Unable to complete recalculation process!", 400);
        }
    }

    public function recalculateStockReadings(Request $request, $stock_serial_no, $date = null)
    {
        try {

            $currentStatus = \Cache::get(\Auth::user()->serial_no.'_'.$stock_serial_no.'_recalculations_job_c');
            //if($currentStatus != 'STARTED')
            {
                \Cache::forget($request->user()->serial_no.'_'.$stock_serial_no.'_recalculations_job_c');
                $nam = $request->user()->serial_no.'_'.$stock_serial_no.'_recalculations_job_c';
                \Log::info($nam);
                dispatch(new \App\Jobs\RecalculateStockReadings($request->user(), \App\Stock::findOrFail($stock_serial_no), $date));
            }
            //\DB::statement('call recalculate_stock_readings('.$stock_serial_no.')');
        } catch ( \Exception $e) {
            \Log::error($e);
            return response()->json("Unable to complete recalculation process!", 400);
        }

        $calculation_progress_url = route('stock.calculation_progress', $stock_serial_no);
        $responesData = ['message' => 'Recalculation job scheduled', 'calculation_progress_url' => $calculation_progress_url];
        return response()->json($responesData, 200);
    }

    public function getStockUrgentCalculation(Request $request, $stock)
    {
        $stock = \App\Stock::findOrFail($stock)->stock_urgent_causes;
        //$stock = \App\UrgentCause::all();
        return $stock;
    }

    public function calibration(Request $request, $stock)
    {
        $controls = \App\StockCalibration::$fillableFields;
        $controls['stock_serial_no']['value'] = $stock;
        $instance = \App\Stock::findOrFail($stock);
        $calibration = $instance->calibration;
        if(isset($calibration))
        {
            $data = generateFormFields($controls, $calibration->toArray());
            $data['rows'][] = [
                "is_unreal" => [
                    "width" => "col-md-6",
                    "field" => [
                        "type" => "hidden",
                        "value" => 1
                    ]
                ]
            ];
        }else
        {
            $data = generateFormFields($controls, $instance->toArray());
        }

        $label = \App\Stock::getLabel();
        $data['title'] = $instance->$label." Stock Calibration";
        $data['description'] = "";

        $routeNameWithAction = \Request::route()->getName();
        $routeUrl = route('stock.calibrate', $stock);
        $data['storeRouteUrl'] = $routeUrl;
        $data['action_type'] = 'create';
        $data['stock_serial_no'] = $stock;

        return view('Admin::stock_calibration', $data);
    }

    public function calibrate(Request $request, $stock)
    {
        \Cache::forget($request->user()->serial_no.'_'.$stock.'_calibration_job_c');
        \Cache::forget($request->user()->serial_no.'_'.$stock.'_calibration_job_p');

        \App\StockCalibration::where('stock_serial_no', $stock)->delete();

        $data = $request->except('start_from_date');
        $data['activated_down_levels_count'] = $data['activated_up_levels_count'];

        $data["u".$data['activated_up_levels_count']."_to"] = null;
        $data["d".$data['activated_up_levels_count']."_to"] = null;
        for($i = ($data['activated_up_levels_count']+1); $i <= 9; $i++)
        {
            unset($data["u".$i."_from"]);
            unset($data["u".$i."_to"]);
            unset($data["d_u".$i."_from"]);
            unset($data["d_u".$i."_to"]);

            unset($data["d".$i."_from"]);
            unset($data["d".$i."_to"]);
            unset($data["d_d".$i."_from"]);
            unset($data["d_d".$i."_to"]);
        }

        $instance = \App\Stock::findOrFail($stock)->calibration()->create($data);
        \Log::info('Stock Calibration Created');
        $date = $request->get('start_from_date');
        try {
            \Log::info('Calling CalibrateStockReadings');
            \Cache::put($request->user()->serial_no.'_'.$stock.'_calibration_job_c', 'PROCESSING', 1440);
            \Cache::put($request->user()->serial_no.'_'.$stock.'_calibration_job_p', 'PENDING', 1440);
            //\DB::table('jobs')->delete();
            dispatch(new \App\Jobs\CalibrateStockReadings($request->user(), \App\Stock::findOrFail($stock), $date));

            \Log::info('CalibrateStockReadings dispatched');
        } catch ( \Exception $e) {
            \Log::error($e);
            return response()->json("Unable to complete calibration process!", 400);
        }

        if($request->ajax())
        {
            $routeNameWithAction = \Request::route()->getName();
            $routeUrl = route('stock.results', [$stock, 'calibration', $date]);

            $responesData = ['message' => 'Calibration stock readings updated', 'redirect_url' => $routeUrl];
            return response()->json($responesData, 200);
        }else
        {

        }
    }

    public function applyCalibrationSettings(Request $request, $stock_serial_no, $date = null)
    {
        $stockCalibration = \App\StockCalibration::where('stock_serial_no', $stock_serial_no)->first();
        $stockData = array();
        $stockData['activated_up_levels_count'] = $stockCalibration->activated_up_levels_count;
        $stockData['activated_down_levels_count'] = $stockCalibration->activated_down_levels_count;

        $stockData['u9_from']   = $stockCalibration->u9_from;
        $stockData['u9_to']     = $stockCalibration->u9_to;
        $stockData['u8_from']   = $stockCalibration->u8_from;
        $stockData['u8_to']     = $stockCalibration->u8_to;
        $stockData['u7_from']   = $stockCalibration->u7_from;
        $stockData['u7_to']     = $stockCalibration->u7_to;
        $stockData['u6_from']   = $stockCalibration->u6_from;
        $stockData['u6_to']     = $stockCalibration->u6_to;
        $stockData['u5_from']   = $stockCalibration->u5_from;
        $stockData['u5_to']     = $stockCalibration->u5_to;
        $stockData['u4_from']   = $stockCalibration->u4_from;
        $stockData['u4_to']     = $stockCalibration->u4_to;
        $stockData['u3_from']   = $stockCalibration->u3_from;
        $stockData['u3_to']     = $stockCalibration->u3_to;
        $stockData['u2_from']   = $stockCalibration->u2_from;
        $stockData['u2_to']     = $stockCalibration->u2_to;
        $stockData['u1_from']   = $stockCalibration->u1_from;
        $stockData['u1_to']     = $stockCalibration->u1_to;
        $stockData['s_from']   = $stockCalibration->s_from;
        $stockData['s_to']     = $stockCalibration->s_to;
        $stockData['d1_from']   = $stockCalibration->d1_from;
        $stockData['d1_to']     = $stockCalibration->d1_to;
        $stockData['d2_from']   = $stockCalibration->d2_from;
        $stockData['d2_to']     = $stockCalibration->d2_to;
        $stockData['d3_from']   = $stockCalibration->d3_from;
        $stockData['d3_to']     = $stockCalibration->d3_to;
        $stockData['d4_from']   = $stockCalibration->d4_from;
        $stockData['d4_to']     = $stockCalibration->d4_to;
        $stockData['d5_from']   = $stockCalibration->d5_from;
        $stockData['d5_to']     = $stockCalibration->d5_to;
        $stockData['d6_from']   = $stockCalibration->d6_from;
        $stockData['d6_to']     = $stockCalibration->d6_to;
        $stockData['d7_from']   = $stockCalibration->d7_from;
        $stockData['d7_to']     = $stockCalibration->d7_to;
        $stockData['d8_from']   = $stockCalibration->d8_from;
        $stockData['d8_to']     = $stockCalibration->d8_to;
        $stockData['d9_from']   = $stockCalibration->d9_from;
        $stockData['d9_to']     = $stockCalibration->d9_to;
        $stockStatus = \App\Stock::findOrFail($stock_serial_no)->update($stockData);
        try {

            $currentStatus = \Cache::get(\Auth::user()->serial_no.'_'.$stock_serial_no.'_recalculations_job_c');
            if($currentStatus != 'STARTED')
            {
                \Cache::forget($request->user()->serial_no.'_'.$stock_serial_no.'_recalculations_job_c');
                $nam = $request->user()->serial_no.'_'.$stock_serial_no.'_recalculations_job_c';
                \Log::info($nam);
                dispatch(new \App\Jobs\RecalculateStockReadings($request->user(), \App\Stock::findOrFail($stock_serial_no), $date));
            }
            //\DB::statement('call recalculate_stock_readings('.$stock_serial_no.')');
        } catch ( \Exception $e) {
            \Log::error($e);
            return response()->json("Unable to complete recalculation process!", 400);
        }

        $calculation_progress_url = route('stock.calculation_progress', $stock_serial_no);
        $responesData = ['message' => 'Recalculation job scheduled', 'calculation_progress_url' => $calculation_progress_url];
        return response()->json($responesData, 200);
    }
    public function results(Request $request, $stock, $type='prediction', $date = null)
    {
        //$controls = \App\StockCalibration::$fillableFields;
        $stock = \App\Stock::findOrFail($stock);

        $label = \App\Stock::getLabel();
        if($type == 'calibration')
        {
            $data['title'] = $stock->$label." Stock Calibration Results";
        }else
        {
            $data['title'] = $stock->$label." Stock Prediction Results";
        }
        $data['description'] = "";

        $data['stock'] = $stock;
        $data['type'] = $type;
        $data['date'] = $date;


        $now = \Carbon\Carbon::now();
        global $summary;
        global $total_records_count;
        $total_records_count = 0;
        $summary = array();
        $xlsFile = \Excel::load(storage_path('templates/DFM Report Template.xlsx'), function($reader) use ($stock, $now, $summary, $total_records_count, $type, $date)  {
            $reader->setTitle('['.$now->format('Y-m-d H-i').'] DFM Report');

            global $summary;
            global $total_records_count;

            if($type == 'calibration')
            {
                $query = \App\StockClosingReadingCalibrationForResult::where('stock_serial_no', $stock->serial_no)->whereNotNull('close_selling_price');
            }else
            {
                $query = \App\StockClosingReadingForResult::where('stock_serial_no', $stock->serial_no)->whereNotNull('close_selling_price');
            }

            if(isset($date) && $date != '')
            {
                $date = \Carbon\Carbon::parse($date);
                $query->where('date_closed', '>=', $date);
            }
            $predictedReadings = $query->get();
            $reader->sheet(0, function($sheet) use ($stock, $predictedReadings, $summary, $total_records_count, $type)
            {
                if($type == 'calibration')
                {
                    $sheet->setCellValue('A1', '[Calibration] '.$stock->stock_name_en);
                }else
                {
                    $sheet->setCellValue('A1', '[Prediction] '.$stock->stock_name_en);
                }
                    $dataStartIndex = 4;
                    $index = 0;

                    global $summary;
                    global $total_records_count;
                    $summary['greatest_probability_validity_total'] = 0;
                    $summary['predicted_rising_selling_closing_price_validity_total'] = 0;
                    $summary['predicted_general_selling_closing_price_validity_total'] = 0;
                    $summary['predicted_falling_selling_closing_price_validity_total'] = 0;
                    $summary['greatest_probability_error_rate_total'] = 0;
                    $summary['predicted_rising_selling_closing_price_error_rate_total'] = 0;
                    $summary['predicted_falling_selling_closing_price_error_rate_total'] = 0;
                    $summary['predicted_general_selling_closing_price_error_rate_total'] = 0;
                    $total_records_count = $predictedReadings->count();
                    foreach($predictedReadings as $predictedReading)
                    {
                        $rowIndex = $index+$dataStartIndex;

                        $sheet->setCellValue('A'.$rowIndex, $index+1);
                        $sheet->setCellValue('B'.$rowIndex, $predictedReading->date_closed->toDateString());
                        $sheet->setCellValue('C'.$rowIndex, $predictedReading->open_selling_price);
                        $sheet->setCellValue('D'.$rowIndex, $predictedReading->close_selling_price);
                        $sheet->setCellValue('E'.$rowIndex, $predictedReading->direction);
                        $sheet->setCellValue('F'.$rowIndex, $predictedReading->greatest_probability);
                        $sheet->setCellValue('G'.$rowIndex, $predictedReading->greatest_probability_direction);
                        $sheet->setCellValue('H'.$rowIndex, $predictedReading->predicted_rising_selling_closing_price);
                        $sheet->setCellValue('I'.$rowIndex, $predictedReading->predicted_rising_selling_closing_price_rate);
                        $sheet->setCellValue('J'.$rowIndex, $predictedReading->predicted_falling_selling_closing_price);
                        $sheet->setCellValue('K'.$rowIndex, $predictedReading->predicted_falling_selling_closing_price_rate);

                        $greatest_probability_validity = $predictedReading->greatest_probability_validity;
                        $greatest_probability_error_rate = $predictedReading->greatest_probability_error_rate;
                        $sheet->setCellValue('L'.$rowIndex, $greatest_probability_validity);
                        $sheet->setCellValue('M'.$rowIndex, $greatest_probability_error_rate);
                        $summary['greatest_probability_validity_total'] += $greatest_probability_validity;
                        $summary['greatest_probability_error_rate_total'] += abs($greatest_probability_error_rate);

                        $predicted_rising_selling_closing_price_validity = $predictedReading->predicted_rising_selling_closing_price_validity;
                        $predicted_rising_selling_closing_price_error_rate = $predictedReading->predicted_rising_selling_closing_price_error_rate;
                        $sheet->setCellValue('N'.$rowIndex, $predicted_rising_selling_closing_price_validity);
                        $sheet->setCellValue('O'.$rowIndex, $predicted_rising_selling_closing_price_error_rate);
                        $summary['predicted_rising_selling_closing_price_validity_total'] += $predicted_rising_selling_closing_price_validity;
                        $summary['predicted_rising_selling_closing_price_error_rate_total'] += abs($predicted_rising_selling_closing_price_error_rate);

                        $predicted_falling_selling_closing_price_validity = $predictedReading->predicted_falling_selling_closing_price_validity;
                        $predicted_falling_selling_closing_price_error_rate = $predictedReading->predicted_falling_selling_closing_price_error_rate;
                        $sheet->setCellValue('P'.$rowIndex, $predicted_falling_selling_closing_price_validity);
                        $sheet->setCellValue('Q'.$rowIndex, $predicted_falling_selling_closing_price_error_rate);
                        $summary['predicted_falling_selling_closing_price_validity_total'] += $predicted_falling_selling_closing_price_validity;
                        $summary['predicted_falling_selling_closing_price_error_rate_total'] += abs($predicted_falling_selling_closing_price_error_rate);

                        $general_price_validity = $predictedReading->predicted_general_selling_closing_price_validity;
                        $general_price_error_rate = $predictedReading->predicted_general_selling_closing_price_error_rate;
                        $sheet->setCellValue('R'.$rowIndex, $general_price_validity);
                        $sheet->setCellValue('S'.$rowIndex, $general_price_error_rate);
                        $summary['predicted_general_selling_closing_price_validity_total'] += $general_price_validity;
                        $summary['predicted_general_selling_closing_price_error_rate_total'] += abs($general_price_error_rate);

                        for ($c = 'A'; $c != 'T'; ++$c) {
                            $cell_from = $sheet->getCell($c.$dataStartIndex);
                            $cell_to = $sheet->getCell($c.$rowIndex);
                            $cell_to->setXfIndex($cell_from->getXfIndex()); // black magic here
                            //$cell_to->setValue($cell_from->getValue());
                        }

                        /*$sheet->duplicateStyle(
                            $sheet->getStyle('A'.$dataStartIndex.':Q'.$dataStartIndex),
                            'A'.$rowIndex.':Q'.$rowIndex
                            );*/

                        $index++;
                    }
                    $rowIndex = $index+$dataStartIndex;
                    if($total_records_count != 0)
                    {
                        $sheet->setCellValue('L'.$rowIndex, $summary['greatest_probability_validity_total']/$total_records_count);
                        $sheet->setCellValue('M'.$rowIndex, $summary['greatest_probability_error_rate_total']/$total_records_count);

                        $sheet->setCellValue('N'.$rowIndex, $summary['predicted_rising_selling_closing_price_validity_total']/$total_records_count);
                        $sheet->setCellValue('O'.$rowIndex, $summary['predicted_rising_selling_closing_price_error_rate_total']/$total_records_count);

                        $sheet->setCellValue('P'.$rowIndex, $summary['predicted_falling_selling_closing_price_validity_total']/$total_records_count);
                        $sheet->setCellValue('Q'.$rowIndex, $summary['predicted_falling_selling_closing_price_error_rate_total']/$total_records_count);

                        $sheet->setCellValue('R'.$rowIndex, $summary['predicted_general_selling_closing_price_validity_total']/$total_records_count);
                        $sheet->setCellValue('S'.$rowIndex, $summary['predicted_general_selling_closing_price_error_rate_total']/$total_records_count);
                    }

                    /*$rowIndex = $index+$dataStartIndex+2;

                    $sheet->insertNewRowBefore($rowIndex);*/
            });

        });

        $file_id = $now->timestamp.$request->user()->serial_no;
        $file_name = 'DFM Report_'.$file_id.'.xlsx';
        $xlsFile->file = $file_name;
        $xlsFile->store('xlsx');

        if($type == 'calibration')
        {
            \Cache::put($request->user()->serial_no.'_'.$stock->serial_no.'_last_calibration_file', $file_name, 86400);
        }else
        {
            \Cache::put($request->user()->serial_no.'_'.$stock->serial_no.'_last_prediction_file', $file_name, 86400);
        }

        $data['summary'] = $summary;
        $data['total_records_count'] = $total_records_count;
        $data['file_id'] = $file_id;

        return view('Admin::stock_calibration_results', $data);

    }

    public function readings(Request $request, $stock, $type='prediction', $date = null)
    {
        $results['type'] = $type;
        $query = \App\StockClosingReadingForResult::select('date_closed', 'close_selling_price', 'predicted_rising_selling_closing_price', 'predicted_stable_selling_closing_price', 'predicted_falling_selling_closing_price', 'predicted_rising_selling_closing_price_rate', 'predicted_stable_selling_closing_price_rate', 'predicted_falling_selling_closing_price_rate')->where('stock_serial_no', $stock);
        if(isset($date))
        {
            $date = \Carbon\Carbon::parse($date);
            $query->where('date_closed', '>=', $date);
        }

        $results['ACTUAL'] = $query->get()->toArray();

        if($type == 'calibration')
        {
            $calibrationQuery = \App\StockClosingReadingCalibrationForResult::select('date_closed', 'close_selling_price', 'predicted_rising_selling_closing_price', 'predicted_stable_selling_closing_price', 'predicted_falling_selling_closing_price', 'predicted_rising_selling_closing_price_rate', 'predicted_stable_selling_closing_price_rate', 'predicted_falling_selling_closing_price_rate')->where('stock_serial_no', $stock);
            if(isset($date))
            {
                $calibrationQuery->where('date_closed', '>=', $date);
            }
            $results['PREDICTED'] = $calibrationQuery->get()->toArray();
        }
        return response()
            ->json($results)
            ->setCallback($request->input('callback'));;
    }


    public function export_results(Request $request, $stock, $type='prediction', $file_id='')
    {

        if($type == 'calibration')
        {
            $file_name = \Cache::get($request->user()->serial_no.'_'.$stock.'_last_calibration_file');
        }else
        {
            $file_name = \Cache::get($request->user()->serial_no.'_'.$stock.'_last_prediction_file');
        }


        $file_name = 'DFM Report_'.$file_id.'.xlsx';
        $file_path = storage_path() .'/exports/'. $file_name;
        $file_name = \Carbon\Carbon::now()->format('Y-m-d H-i').' '.$file_name;
            // Send Download
        return response()->download($file_path, $file_name);

    }

    public function analytical_report_ui(Request $request)
    {
        $model = $this->model;
        $controls = [
            'start_date' => ['type' => 'date', 'validation' => 'required'],
            'end_date' => ['type' => 'date', 'validation' => 'required'],
            'related_market_serial_no' => ['type' => 'multiselect', 'multiselect' => true, 'list_reference' => 'StockMarket'],
            'related_stocks' => ['type' => 'group_list', 'multiselect' => true, 'list_reference'=>'Stock', 'list_group_parent'=>'StockMarket', 'relation' => 'many-to-many', 'xview'=>true],
        ];

        $data = generateFormFields($controls, null, $model::$formVerticalSections);

        $data['title'] = "Stocks Report";
        $data['description'] = "";

        $data['storeRouteUrl'] = route('stock.analytical_report');
        $data['action_type'] = 'create';

        $data['page_scripts'] = ['stocks_report.js'];
        $data['form_id'] = 'stocks_report_form';

        return view('Admin::generic_form', $data);
    }

    public function analytical_report(Request $request)
    {
        \Validator::extend('before_equal', function($attribute, $value, $parameters, $validator) {
            return strtotime($validator->getData()[$parameters[0]]) >= strtotime($value);
        });
        \Validator::extend('after_equal', function($attribute, $value, $parameters, $validator) {
            return strtotime($validator->getData()[$parameters[0]]) <= strtotime($value);
        });
        $validator = \Validator::make($request->all(), [
            'start_date' => 'required|date|before_equal:end_date',
            'end_date' => 'required|date|after_equal:start_date',
            'related_market_serial_no' => 'required_without:related_stocks',
            'related_stocks' => 'required_without:related_market_serial_no',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        //$controls = \App\StockCalibration::$fillableFields;
        $markets = $request->get('related_market_serial_no');
        $stocks = $request->get('related_stocks');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        if(isset($stocks))
        {
            if(!is_array($stocks))
            {
                $stocks = [$stocks];
            }
        }else
        {
            $stocks = [];
        }
        if(isset($markets))
        {
            if(!is_array($markets))
            {
                $markets = [$markets];
            }
        }else
        {
            $markets = [];
        }
        $markets = array_merge($markets, \App\Stock::whereIn('serial_no', $stocks)->pluck('stock_market_serial_no')->all());
        $markets = \App\StockMarket::select('stock_markets.serial_no', 'stock_markets.label_en', 'stock_markets.label_ar')
                    ->with(
                        [
                            'stock_closing_reading_for_results.stock',
                            'stock_closing_reading_for_results' => function ($query) use ($start_date, $end_date, $stocks)
                            {
                                $query->whereBetween('date_closed', [$start_date, $end_date])
                                    ->whereNotNull('close_selling_price');
                                if(!empty($stocks))
                                {
                                    $query->whereIn('stock_serial_no', $stocks);
                                }
                                $query->orderBy('date_closed', 'asc');
                            }
                        ]
                    )
                    ->whereIn('serial_no', $markets)
                    ->get();

        $now = \Carbon\Carbon::now();
        global $summary;
        global $total_records_count;
        $total_records_count = 0;
        $summary = array();
        $xlsFile = \Excel::load(storage_path('templates/Stocks Report Template.xlsx'), function($reader) use ($markets, $now, $summary, $total_records_count)  {
            $reader->setTitle('['.$now->format('Y-m-d H-i').'] Stock Closing Report');

            global $summary;
            global $total_records_count;

            $reader->sheet(0, function($sheet) use ($markets, $summary, $total_records_count)
            {
                    $sheet->setCellValue('A1', 'Stock Closing Report');
                    $dataStartIndex = 2;
                    $index = 0;
                    $serial_no = 0;
                    $currentMarket = '';
                    foreach($markets as $market)
                    {

                        $rowIndex = $index+$dataStartIndex;
                        $index += 3;

                        $sheet->setCellValue('A'.$rowIndex, $market->label_en);
                        $sheet->mergeCells('A'.$rowIndex.':L'.$rowIndex);

                        for ($c = 'A'; $c != 'M'; ++$c) {
                            $cell_from = $sheet->getCell($c.$dataStartIndex);
                            $cell_to = $sheet->getCell($c.$rowIndex);
                            $cell_to->setXfIndex($cell_from->getXfIndex());

                            $cell_from = $sheet->getCell($c.($dataStartIndex+1));
                            $cell_to = $sheet->getCell($c.($rowIndex+1));
                            $cell_to->setXfIndex($cell_from->getXfIndex());

                            $cell_to->setValue($cell_from->getValue());

                            $cell_from = $sheet->getCell($c.($dataStartIndex+2));
                            $cell_to = $sheet->getCell($c.($rowIndex+2));
                            $cell_to->setXfIndex($cell_from->getXfIndex());

                            $cell_to->setValue($cell_from->getValue());
                        }


                        $sheet->cells('A'.$rowIndex.':L'.($rowIndex+2), function($cells) {
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('A'.($rowIndex+1).':A'.($rowIndex+2));
                        $sheet->mergeCells('B'.($rowIndex+1).':B'.($rowIndex+2));
                        $sheet->mergeCells('C'.($rowIndex+1).':C'.($rowIndex+2));
                        $sheet->mergeCells('D'.($rowIndex+1).':F'.($rowIndex+1));
                        $sheet->mergeCells('G'.($rowIndex+1).':H'.($rowIndex+1));
                        $sheet->mergeCells('I'.($rowIndex+1).':J'.($rowIndex+1));
                        $sheet->mergeCells('K'.($rowIndex+1).':L'.($rowIndex+1));

                        global $summary;
                        global $total_records_count;
                        $summary['open_selling_price_total'] = 0;
                        $summary['close_selling_price_total'] = 0;
                        $summary['predicted_general_price_total'] = 0;
                        $summary['greatest_probability_total'] = 0;
                        $summary['predicted_general_price_validity_total'] = 0;
                        $summary['predicted_general_price_error_rate_total'] = 0;
                        $readingsSet = $market->stock_closing_reading_for_results;
                        $total_records_count = 0;

                        $lastDate = '';
                        foreach($readingsSet as $predictedReading)
                        {

                            $tempLastDate = $predictedReading->date_closed->toDateString();
                            if($lastDate != $tempLastDate && $lastDate != '')
                            {

                                $rowIndex = $index+$dataStartIndex;
                                if($total_records_count != 0)
                                {
                                    $sheet->setCellValue('D'.$rowIndex, round($summary['open_selling_price_total']/$total_records_count, 2));
                                    $sheet->setCellValue('E'.$rowIndex, round($summary['close_selling_price_total']/$total_records_count, 2));
                                    $sheet->setCellValue('G'.$rowIndex, round($summary['predicted_general_price_total']/$total_records_count, 2));
                                    $sheet->setCellValue('I'.$rowIndex, round($summary['greatest_probability_total']/$total_records_count, 2));
                                    $sheet->setCellValue('K'.$rowIndex, round(($summary['predicted_general_price_validity_total']/$total_records_count)*100, 2).'%');
                                    $sheet->setCellValue('L'.$rowIndex, round(($summary['predicted_general_price_error_rate_total']/$total_records_count)*100, 2).'%');

                                    $sheet->cells('D'.$rowIndex.':L'.$rowIndex, function($cells) {
                                        $cells->setAlignment('center');
                                    });
                                }

                                $summary['open_selling_price_total'] = 0;
                                $summary['close_selling_price_total'] = 0;
                                $summary['predicted_general_price_total'] = 0;
                                $summary['greatest_probability_total'] = 0;
                                $summary['predicted_general_price_validity_total'] = 0;
                                $summary['predicted_general_price_error_rate_total'] = 0;

                                $total_records_count = 0;

                                $index++;
                            }
                            $total_records_count++;

                            $lastDate = $tempLastDate;

                            $rowIndex = $index+$dataStartIndex;


                            $open_selling_price = $predictedReading->open_selling_price;
                            $close_selling_price = $predictedReading->close_selling_price;

                            $predicted_general_price = $predictedReading->predicted_general_selling_closing_price;

                            $greatest_probability = $predictedReading->greatest_probability;

                            $predicted_general_price_validity = $predictedReading->predicted_general_selling_closing_price_validity;
                            $predicted_general_price_error_rate = $predictedReading->predicted_general_selling_closing_price_error_rate;

                            $sheet->setCellValue('A'.$rowIndex, $serial_no+1);
                            $sheet->setCellValue('B'.$rowIndex, $predictedReading->stock->stock_name_en);
                            $sheet->setCellValue('C'.$rowIndex, $lastDate);
                            $sheet->setCellValue('D'.$rowIndex, $open_selling_price);
                            $sheet->setCellValue('E'.$rowIndex, $close_selling_price);
                            $sheet->setCellValue('F'.$rowIndex, $predictedReading->direction);

                            $sheet->setCellValue('G'.$rowIndex, $predicted_general_price);
                            $sheet->setCellValue('H'.$rowIndex, $predictedReading->predicted_general_direction);

                            $sheet->setCellValue('I'.$rowIndex, $greatest_probability);
                            $sheet->setCellValue('J'.$rowIndex, $predictedReading->greatest_probability_direction);

                            $sheet->setCellValue('K'.$rowIndex, $predicted_general_price_validity);
                            $sheet->setCellValue('L'.$rowIndex, $predicted_general_price_error_rate);

                            $summary['open_selling_price_total'] += $open_selling_price;
                            $summary['close_selling_price_total'] += $close_selling_price;

                            $summary['predicted_general_price_total'] += $predicted_general_price;
                            $summary['greatest_probability_total'] += $greatest_probability;

                            $summary['predicted_general_price_validity_total'] += $predicted_general_price_validity;
                            $summary['predicted_general_price_error_rate_total'] += abs($predicted_general_price_error_rate);

                            for ($c = 'A'; $c != 'M'; ++$c) {
                                $cell_from = $sheet->getCell($c.($dataStartIndex+3));
                                $cell_to = $sheet->getCell($c.$rowIndex);
                                $cell_to->setXfIndex($cell_from->getXfIndex()); // black magic here
                                //$cell_to->setValue($cell_from->getValue());
                            }

                            $sheet->cells('G'.$rowIndex.':L'.$rowIndex, function($cells) {
                                $cells->setFontColor('#003300');
                            });
                            if($predicted_general_price_validity === false)
                            {
                                $sheet->cells('G'.$rowIndex.':H'.$rowIndex, function($cells) {
                                    $cells->setFontColor('#ff0000');
                                });
                                $sheet->cells('K'.$rowIndex.':L'.$rowIndex, function($cells) {
                                    $cells->setFontColor('#ff0000');
                                });
                            }
                            /*$sheet->duplicateStyle(
                                $sheet->getStyle('A'.$dataStartIndex.':Q'.$dataStartIndex),
                                'A'.$rowIndex.':Q'.$rowIndex
                                );*/

                            $index++;
                            $serial_no++;
                        }

                        $rowIndex = $index+$dataStartIndex;
                        if($total_records_count != 0)
                        {
                            $sheet->setCellValue('D'.$rowIndex, round($summary['open_selling_price_total']/$total_records_count, 2));
                            $sheet->setCellValue('E'.$rowIndex, round($summary['close_selling_price_total']/$total_records_count, 2));
                            $sheet->setCellValue('G'.$rowIndex, round($summary['predicted_general_price_total']/$total_records_count, 2));
                            $sheet->setCellValue('I'.$rowIndex, round($summary['greatest_probability_total']/$total_records_count, 2));
                            $sheet->setCellValue('K'.$rowIndex, round(($summary['predicted_general_price_validity_total']/$total_records_count)*100, 2).'%');
                            $sheet->setCellValue('L'.$rowIndex, round(($summary['predicted_general_price_error_rate_total']/$total_records_count)*100, 2).'%');

                            $sheet->cells('D'.$rowIndex.':L'.$rowIndex, function($cells) {
                                $cells->setAlignment('center');
                            });
                        }
                        $summary['open_selling_price_total'] = 0;
                        $summary['close_selling_price_total'] = 0;
                                $summary['predicted_general_price_total'] = 0;
                        $summary['greatest_probability_total'] = 0;
                        $summary['predicted_general_price_validity_total'] = 0;
                        $summary['predicted_general_price_error_rate_total'] = 0;
                        $index++;
                    }

                    /*$rowIndex = $index+$dataStartIndex+2;

                    $sheet->insertNewRowBefore($rowIndex);*/
            });

        });

        $file_id = $now->timestamp.$request->user()->serial_no;
        $file_name = 'Stock Closing Report_'.$file_id.'.xlsx';
        $xlsFile->file = $file_name;
        $xlsFile->store('xlsx');


        $file_path = storage_path() .'/exports/'. $file_name;
        $file_name = \Carbon\Carbon::now()->format('Y-m-d H-i').' '.$file_name;
            // Send Download
        return response()->download($file_path, $file_name);
    }

    public function levels_report_ui(Request $request)
    {
        $model = $this->model;
        $controls = [
            'related_market_serial_no' => ['type' => 'multiselect', 'multiselect' => true, 'list_reference' => 'StockMarket'],
            'related_stocks' => ['type' => 'group_list', 'multiselect' => true, 'list_reference'=>'Stock', 'list_group_parent'=>'StockMarket', 'relation' => 'many-to-many', 'xview'=>true],
        ];

        $data = generateFormFields($controls, null, $model::$formVerticalSections);

        $data['title'] = "Stock Levels Report";
        $data['description'] = "";

        $data['storeRouteUrl'] = route('stock.levels_report');
        $data['action_type'] = 'create';

        $data['page_scripts'] = ['stocks_report.js'];
        $data['form_id'] = 'stocks_report_form';

        return view('Admin::generic_form', $data);
    }

    public function levels_report(Request $request)
    {
        // \Validator::extend('before_equal', function($attribute, $value, $parameters, $validator) {
        //     return strtotime($validator->getData()[$parameters[0]]) >= strtotime($value);
        // });
        // \Validator::extend('after_equal', function($attribute, $value, $parameters, $validator) {
        //     return strtotime($validator->getData()[$parameters[0]]) <= strtotime($value);
        // });
        $validator = \Validator::make($request->all(), [
            // 'start_date' => 'required|date|before_equal:end_date',
            // 'end_date' => 'required|date|after_equal:start_date',
            'related_market_serial_no' => 'required_without:related_stocks',
            'related_stocks' => 'required_without:related_market_serial_no',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        //$controls = \App\StockCalibration::$fillableFields;
        $markets = $request->get('related_market_serial_no');
        $stocks = $request->get('related_stocks');
        // $start_date = $request->get('start_date');
        // $end_date = $request->get('end_date');

        if(isset($stocks))
        {
            if(!is_array($stocks))
            {
                $stocks = [$stocks];
            }
        }else
        {
            $stocks = [];
        }
        if(isset($markets))
        {
            if(!is_array($markets))
            {
                $markets = [$markets];
            }
        }else
        {
            $markets = [];
        }
        $markets = array_merge($markets, \App\Stock::whereIn('serial_no', $stocks)->pluck('stock_market_serial_no')->all());
        $markets = \App\StockMarket::select('stock_markets.serial_no', 'stock_markets.label_en', 'stock_markets.label_ar')
                    ->with(
                        [
                            'stocks' => function ($query) use ($stocks)
                            {
                                $query->where('active', true);
                                if(!empty($stocks))
                                {
                                    $query->whereIn('serial_no', $stocks);
                                }
                            }
                        ]
                    )
                    ->whereIn('serial_no', $markets)
                    ->get();

        $now = \Carbon\Carbon::now();
        global $summary;
        global $total_records_count;
        $total_records_count = 0;
        $summary = array();
        $xlsFile = \Excel::load(storage_path('templates/Stock Levels Template.xlsx'), function($reader) use ($markets, $now, $summary, $total_records_count)  {
            $reader->setTitle('['.$now->format('Y-m-d H-i').'] Stock Levels Report');

            global $summary;
            global $total_records_count;

            $reader->sheet(0, function($sheet) use ($markets, $summary, $total_records_count)
            {
                    $sheet->setCellValue('A1', 'Stock Levels Report');
                    $dataStartIndex = 2;
                    $index = 0;
                    $serial_no = 0;
                    $currentMarket = '';
                    foreach($markets as $market)
                    {

                        $rowIndex = $index+$dataStartIndex;
                        $index += 3;

                        $sheet->setCellValue('A'.$rowIndex, $market->label_en);
                        $sheet->mergeCells('A'.$rowIndex.':I'.$rowIndex);

                        for ($c = 'A'; $c != 'J'; ++$c) {
                            $cell_from = $sheet->getCell($c.$dataStartIndex);
                            $cell_to = $sheet->getCell($c.$rowIndex);
                            $cell_to->setXfIndex($cell_from->getXfIndex());

                            $cell_from = $sheet->getCell($c.($dataStartIndex+1));
                            $cell_to = $sheet->getCell($c.($rowIndex+1));
                            $cell_to->setXfIndex($cell_from->getXfIndex());

                            $cell_to->setValue($cell_from->getValue());

                            $cell_from = $sheet->getCell($c.($dataStartIndex+2));
                            $cell_to = $sheet->getCell($c.($rowIndex+2));
                            $cell_to->setXfIndex($cell_from->getXfIndex());

                            $cell_to->setValue($cell_from->getValue());
                        }


                        $sheet->cells('A'.$rowIndex.':I'.($rowIndex+2), function($cells) {
                            $cells->setAlignment('center');
                        });
                        // $sheet->mergeCells('A'.($rowIndex+1).':A'.($rowIndex+2));
                        // $sheet->mergeCells('B'.($rowIndex+1).':B'.($rowIndex+2));
                        // $sheet->mergeCells('C'.($rowIndex+1).':C'.($rowIndex+2));
                        // $sheet->mergeCells('D'.($rowIndex+1).':F'.($rowIndex+1));
                        // $sheet->mergeCells('G'.($rowIndex+1).':H'.($rowIndex+1));
                        // $sheet->mergeCells('I'.($rowIndex+1).':J'.($rowIndex+1));
                        // $sheet->mergeCells('K'.($rowIndex+1).':L'.($rowIndex+1));

                        global $summary;
                        global $total_records_count;
                        $summary['open_selling_price_total'] = 0;
                        $summary['close_selling_price_total'] = 0;
                        $summary['predicted_general_price_total'] = 0;
                        $summary['greatest_probability_total'] = 0;
                        $summary['predicted_general_price_validity_total'] = 0;
                        $summary['predicted_general_price_error_rate_total'] = 0;
                        $stocks = $market->stocks;
                        $total_records_count = 0;

                        // $lastDate = '';
                        foreach($stocks as $stock)
                        {
                            $calculations = $this->getStockClosingReadings($stock->serial_no);
                            // \Log::debug("calculations=============", $calculations);
                            // $tempLastDate = $predictedReading->date_closed->toDateString();
                            // if($lastDate != $tempLastDate && $lastDate != '')
                            // {

                            //     $rowIndex = $index+$dataStartIndex;
                            //     if($total_records_count != 0)
                            //     {
                            //         $sheet->setCellValue('D'.$rowIndex, round($summary['open_selling_price_total']/$total_records_count, 2));
                            //         $sheet->setCellValue('E'.$rowIndex, round($summary['close_selling_price_total']/$total_records_count, 2));
                            //         $sheet->setCellValue('G'.$rowIndex, round($summary['predicted_general_price_total']/$total_records_count, 2));
                            //         $sheet->setCellValue('I'.$rowIndex, round($summary['greatest_probability_total']/$total_records_count, 2));
                            //         $sheet->setCellValue('K'.$rowIndex, round(($summary['predicted_general_price_validity_total']/$total_records_count)*100, 2).'%');
                            //         $sheet->setCellValue('L'.$rowIndex, round(($summary['predicted_general_price_error_rate_total']/$total_records_count)*100, 2).'%');

                            //         $sheet->cells('D'.$rowIndex.':L'.$rowIndex, function($cells) {
                            //             $cells->setAlignment('center');
                            //         });
                            //     }

                            //     $summary['open_selling_price_total'] = 0;
                            //     $summary['close_selling_price_total'] = 0;
                            //     $summary['predicted_general_price_total'] = 0;
                            //     $summary['greatest_probability_total'] = 0;
                            //     $summary['predicted_general_price_validity_total'] = 0;
                            //     $summary['predicted_general_price_error_rate_total'] = 0;

                            //     $total_records_count = 0;

                            //     $index++;
                            // }
                            $total_records_count++;

                            // $lastDate = $tempLastDate;

                            $rowIndex = $index+$dataStartIndex;

                            $nextReadings = $calculations['nextReading'][0];

                            $up_1_value = $nextReadings['top_positive_value'];
                            $up_2_value = $nextReadings['mid_positive_value'];
                            $up_3_value = $nextReadings['bottom_positive_value'];

                            $down_1_value = $nextReadings['top_negative_value'];
                            $down_2_value = $nextReadings['mid_negative_value'];
                            $down_3_value = $nextReadings['bottom_negative_value'];

                            $closeSellingPrice = $calculations['previousReadings']['close_selling_price'];
                            $closeSellingPrice = str_replace(',', '', $closeSellingPrice);

                            $sheet->setCellValue('A'.$rowIndex, $serial_no+1);
                            $sheet->setCellValue('B'.$rowIndex, $stock->stock_name_en);
                            $sheet->setCellValue('C'.$rowIndex, $closeSellingPrice);
                            $sheet->setCellValue('D'.$rowIndex, $up_1_value);

                            $sheet->setCellValue('E'.$rowIndex, $up_2_value);

                            $sheet->setCellValue('F'.$rowIndex, $up_3_value);

                            $sheet->setCellValue('G'.$rowIndex, $down_1_value);

                            $sheet->setCellValue('H'.$rowIndex, $down_2_value);

                            $sheet->setCellValue('I'.$rowIndex, $down_3_value);


                            for ($c = 'A'; $c != 'J'; ++$c) {
                                $cell_from = $sheet->getCell($c.($dataStartIndex+3));
                                $cell_to = $sheet->getCell($c.$rowIndex);
                                $cell_to->setXfIndex($cell_from->getXfIndex()); // black magic here
                                //$cell_to->setValue($cell_from->getValue());
                            }

                            // $sheet->cells('G'.$rowIndex.':P'.$rowIndex, function($cells) {
                            //     $cells->setFontColor('#003300');
                            // });
                            // if($predicted_general_price_validity === false)
                            // {
                            //     $sheet->cells('G'.$rowIndex.':H'.$rowIndex, function($cells) {
                            //         $cells->setFontColor('#ff0000');
                            //     });
                            //     $sheet->cells('K'.$rowIndex.':L'.$rowIndex, function($cells) {
                            //         $cells->setFontColor('#ff0000');
                            //     });
                            // }
                            /*$sheet->duplicateStyle(
                                $sheet->getStyle('A'.$dataStartIndex.':Q'.$dataStartIndex),
                                'A'.$rowIndex.':Q'.$rowIndex
                                );*/

                            $index++;
                            $serial_no++;
                        }

                        // $rowIndex = $index+$dataStartIndex;
                        // if($total_records_count != 0)
                        // {
                        //     $sheet->setCellValue('D'.$rowIndex, round($summary['open_selling_price_total']/$total_records_count, 2));
                        //     $sheet->setCellValue('E'.$rowIndex, round($summary['close_selling_price_total']/$total_records_count, 2));
                        //     $sheet->setCellValue('G'.$rowIndex, round($summary['predicted_general_price_total']/$total_records_count, 2));
                        //     $sheet->setCellValue('I'.$rowIndex, round($summary['greatest_probability_total']/$total_records_count, 2));
                        //     $sheet->setCellValue('K'.$rowIndex, round(($summary['predicted_general_price_validity_total']/$total_records_count)*100, 2).'%');
                        //     $sheet->setCellValue('L'.$rowIndex, round(($summary['predicted_general_price_error_rate_total']/$total_records_count)*100, 2).'%');

                        //     $sheet->cells('D'.$rowIndex.':L'.$rowIndex, function($cells) {
                        //         $cells->setAlignment('center');
                        //     });
                        // }
                        // $summary['open_selling_price_total'] = 0;
                        // $summary['close_selling_price_total'] = 0;
                        // $summary['predicted_general_price_total'] = 0;
                        // $summary['greatest_probability_total'] = 0;
                        // $summary['predicted_general_price_validity_total'] = 0;
                        // $summary['predicted_general_price_error_rate_total'] = 0;
                        // $index++;
                    }

                    /*$rowIndex = $index+$dataStartIndex+2;

                    $sheet->insertNewRowBefore($rowIndex);*/
            });

        });

        $file_id = $now->timestamp.$request->user()->serial_no;
        $file_name = 'Stock Levels Report_'.$file_id.'.xlsx';
        $xlsFile->file = $file_name;
        $xlsFile->store('xlsx');


        $file_path = storage_path() .'/exports/'. $file_name;
        $file_name = \Carbon\Carbon::now()->format('Y-m-d H-i').' '.$file_name;
            // Send Download
        return response()->download($file_path, $file_name);
    }

    public function getStockClosingReadings($stock_serial_no)
    {
        $nextStockReading = \App\StockClosingReading::select('date_closed', 
                                                        'predicted_rising_selling_closing_price',
                                                        'predicted_stable_selling_closing_price', 
                                                        'predicted_falling_selling_closing_price', 
                                                        'predicted_rising_selling_closing_price_rate',
                                                        'predicted_stable_selling_closing_price_rate', 
                                                        'predicted_falling_selling_closing_price_rate',
                                                        'predicted_rising_selling_closing_price',
                                                        'predicted_stable_selling_closing_price', 

                                                        'top_positive_rate', 
                                                        'top_positive_value', 
                                                        'mid_positive_rate', 
                                                        'mid_positive_value', 
                                                        'bottom_positive_rate', 
                                                        'bottom_positive_value', 
                                                        'top_negative_rate', 
                                                        'top_negative_value', 
                                                        'mid_negative_rate', 
                                                        'mid_negative_value', 
                                                        'bottom_negative_rate', 
                                                        'bottom_negative_value', 
                                                        
                                                        'top_positive_rate',
                                                        'mid_positive_rate',
                                                        'bottom_positive_rate', 
                                                        'top_positive_value',
                                                        'mid_positive_value',
                                                        'bottom_positive_value',
                                                        'top_negative_rate',
                                                        'mid_negative_rate',
                                                        'bottom_negative_rate', 
                                                        'top_negative_value',
                                                        'mid_negative_value',
                                                        'bottom_negative_value')
                                    ->whereNull('close_selling_price')
                                    ->where('stock_serial_no', $stock_serial_no)
                                    ->take(1)
                                    ->get();

        $start_date = date('Y-m-d', strtotime('-10 days' , strtotime($nextStockReading[0]->date_closed->toDateTimeString())));//max(strtotime($start_date), strtotime('-14 days')));

    	$stockClosingReadings = \App\StockClosingReading::select('date_closed', 'close_selling_price')
    								->where('date_closed', '>', $start_date)
                   					->whereNotNull('close_selling_price')
    								->where('close_selling_price', '>', '0')
    								->where('stock_serial_no', $stock_serial_no)
					                ->orderBy('serial_no', 'desc')
					                ->first();

        return array('previousReadings' => $stockClosingReadings, 'nextReading' => $nextStockReading);
    }

/*
    public function analytical_report(Request $request)
    {
        \Validator::extend('before_equal', function($attribute, $value, $parameters, $validator) {
            return strtotime($validator->getData()[$parameters[0]]) >= strtotime($value);
        });
        \Validator::extend('after_equal', function($attribute, $value, $parameters, $validator) {
            return strtotime($validator->getData()[$parameters[0]]) <= strtotime($value);
        });
        $validator = \Validator::make($request->all(), [
            'start_date' => 'required|date|before_equal:end_date',
            'end_date' => 'required|date|after_equal:start_date',
            'related_market_serial_no' => 'required_without:related_stocks',
            'related_stocks' => 'required_without:related_market_serial_no',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        //$controls = \App\StockCalibration::$fillableFields;
        $markets = $request->get('related_market_serial_no');
        $stocks = $request->get('related_stocks');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        if(isset($stocks))
        {
            if(!is_array($stocks))
            {
                $stocks = [$stocks];
            }
        }else
        {
            $stocks = [];
        }
        if(isset($markets))
        {
            if(!is_array($markets))
            {
                $markets = [$markets];
            }
        }else
        {
            $markets = [];
        }

        $stocks = \App\Stock::select('stocks.serial_no', 'stocks.stock_name_en', 'stocks.stock_name_ar', 'stock_market_serial_no')
                    ->with(
                        ['stock_market', 'stock_closing_reading_for_results' => function ($query) use ($start_date, $end_date) {
                            $query->whereBetween('date_closed', [$start_date, $end_date])
                                ->whereNotNull('close_selling_price');
                        }]
                    )
                    ->whereIn('serial_no', $stocks)
                    ->orWhereIn('stock_market_serial_no', $markets)
                    ->orderBy('stock_market_serial_no')
                    ->get();

        $now = \Carbon\Carbon::now();
        global $summary;
        global $total_records_count;
        $total_records_count = 0;
        $summary = array();
        $xlsFile = \Excel::load(storage_path('templates/Stocks Report Template.xlsx'), function($reader) use ($stocks, $now, $summary, $total_records_count)  {
            $reader->setTitle('['.$now->format('Y-m-d H-i').'] Stock Closing Report');

            global $summary;
            global $total_records_count;

            $reader->sheet(0, function($sheet) use ($stocks, $summary, $total_records_count)
            {
                    $sheet->setCellValue('A1', 'Stock Closing Report');
                    $dataStartIndex = 4;
                    $index = 0;
                    $serial_no = 0;
                    $currentMarket = '';
                    foreach($stocks as $stock)
                    {
                        global $summary;
                        global $total_records_count;
                        $summary['greatest_probability_validity_total'] = 0;
                        $summary['greatest_probability_error_rate_total'] = 0;
                        $readingsSet = $stock->stock_closing_reading_for_results;
                        $total_records_count = $readingsSet->count();

                        $tempStockMarketLabel = $stock->stock_market->label_en;
                        if($currentMarket != $tempStockMarketLabel)
                        {
                            $rowIndex = $index+$dataStartIndex;
                            $currentMarket = $tempStockMarketLabel;
                            $sheet->setCellValue('A'.$rowIndex, $currentMarket);
                            $sheet->mergeCells('A'.$rowIndex.':J'.$rowIndex);

                            //$cell_from = $sheet->getCell('A'.($dataStartIndex+1);
                            //$cell_to = $sheet->getCell('A'.$rowIndex);
                            //$cell_to->setXfIndex($cell_from->getXfIndex());

                            $index++;
                        }
                        foreach($readingsSet as $predictedReading)
                        {
                            $rowIndex = $index+$dataStartIndex;

                            $sheet->setCellValue('A'.$rowIndex, $serial_no+1);
                            $sheet->setCellValue('B'.$rowIndex, $stock->stock_name_en);
                            $sheet->setCellValue('C'.$rowIndex, $predictedReading->date_closed->toDateString());
                            $sheet->setCellValue('D'.$rowIndex, $predictedReading->open_selling_price);
                            $sheet->setCellValue('E'.$rowIndex, $predictedReading->close_selling_price);
                            $sheet->setCellValue('F'.$rowIndex, $predictedReading->direction);
                            $sheet->setCellValue('G'.$rowIndex, $predictedReading->greatest_probability);
                            $sheet->setCellValue('H'.$rowIndex, $predictedReading->greatest_probability_direction);

                            $greatest_probability_validity = $predictedReading->greatest_probability_validity;
                            $greatest_probability_error_rate = $predictedReading->greatest_probability_error_rate;
                            $sheet->setCellValue('I'.$rowIndex, $greatest_probability_validity);
                            $sheet->setCellValue('J'.$rowIndex, $greatest_probability_error_rate);
                            $summary['greatest_probability_validity_total'] += $greatest_probability_validity;
                            $summary['greatest_probability_error_rate_total'] += abs($greatest_probability_error_rate);

                            for ($c = 'A'; $c != 'K'; ++$c) {
                                $cell_from = $sheet->getCell($c.($dataStartIndex+1));
                                $cell_to = $sheet->getCell($c.$rowIndex);
                                $cell_to->setXfIndex($cell_from->getXfIndex()); // black magic here
                                //$cell_to->setValue($cell_from->getValue());
                            }
                            if($greatest_probability_validity === false)
                            {
                                $sheet->cells('G'.$rowIndex.':J'.$rowIndex, function($cells) {
                                    $cells->setFontColor('#ff0000');
                                });
                            }

                            $index++;
                            $serial_no++;
                        }

                        $rowIndex = $index+$dataStartIndex;
                        if($total_records_count != 0)
                        {
                            $sheet->setCellValue('I'.$rowIndex, $summary['greatest_probability_validity_total']/$total_records_count);
                            $sheet->setCellValue('J'.$rowIndex, $summary['greatest_probability_error_rate_total']/$total_records_count);
                        }
                        $index++;
                    }
            });

        });

        $file_id = $now->timestamp.$request->user()->serial_no;
        $file_name = 'Stock Closing Report_'.$file_id.'.xlsx';
        $xlsFile->file = $file_name;
        $xlsFile->store('xlsx');


        $file_path = storage_path() .'/exports/'. $file_name;
        $file_name = \Carbon\Carbon::now()->format('Y-m-d H-i').' '.$file_name;
            // Send Download
        return response()->download($file_path, $file_name);
    }*/
}
