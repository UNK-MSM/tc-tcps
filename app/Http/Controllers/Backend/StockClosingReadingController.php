<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GenericRequest;

class StockClosingReadingController extends GenericController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $routeNameWithAction = \Request::route()->getName();

        $model = $this->model;

        $controls = [
                'stock_serial_no' => ['type' => 'list', 'updatable' => false, 'validation' => 'required_without:urgent_calculation_serial_no'],
                'stock_market_serial_no' => ['type' => 'list', 'updatable' => false,],
                'date_closed' => ['type' => 'date', 'updatable' => false, 'width' => '15%', 'validation'=> 'required_with:file'],
                'open_selling_price' => ['type' => 'float', 'updatable' => false, 'validation'=> 'numeric'],
                'close_selling_price' => ['type' => 'float', 'updatable' => false, 'validation'=> 'required_without:urgent_calculation_serial_no|numeric'],
                'urgent_calculation_serial_no' => ['type' => 'list', 'updatable' => false, 'list_reference' => 'stock_urgent_calculation'],
        ];
        $urgent_calculations = \App\StockUrgentCalculation::with('urgent_cause')->get();
        $urgent_calculations_list = array();
        $urgent_calculations_list[] = "";
        foreach($urgent_calculations as $urgent_calculation)
        {
            $urgent_calculations_list[$urgent_calculation->serial_no] = $urgent_calculation->urgent_cause->label_en;
        }

        $controls['urgent_calculation_serial_no']['list'] = $urgent_calculations_list;

        $columns = [
        //xview: dont show on datatable
        //list_reference to override system convention of listing
                'stock_serial_no' => ['type' => 'list', 'updatable' => false, 'validation' => 'required_without:urgent_calculation_serial_no'],
                'stock_market_serial_no' => ['type' => 'list', 'updatable' => false,],
                'date_closed' => ['type' => 'date', 'updatable' => false, 'width' => '15%', 'validation'=> 'required_with:file'],
                'open_selling_price' => ['type' => 'float', 'insertable' => false, 'validation'=> 'numeric'],
                'close_selling_price' => ['type' => 'float', 'updatable' => false, 'validation'=> 'required_without:urgent_calculation_serial_no|numeric'],
                'urgent_calculation_serial_no' => ['type' => 'list', 'insertable' => false, 'list_reference' => 'stock_urgent_calculation'],
        ];
        $columns['stock_serial_no']['list'] = \App\Stock::lists('stock_name_en', 'serial_no');
        $columns['urgent_calculation_serial_no']['list'] = \App\UrgentCause::lists('label_en', 'serial_no');
        $columns['stock_market_serial_no']['list'] = \App\StockMarket::lists('label_en', 'serial_no');


        if($request->ajax())
        {
            $queryBuilder = $model::whereRaw('1=1');
            if(\Auth::user()->user_type->serial_no!==1){
                $queryBuilder->whereRaw('entry_date >= DATE(NOW()) - INTERVAL 7 DAY');
            }

            if(isset($this->parentRouteName))
            {
                $parentModel = $this->parentModel;
                $parentInstance = $parentModel::findOrFail($request->route($this->parentRouteName));
                //$requestData[$this->parentRouteName.'_serial_no'] = $request->route($this->parentRouteName);
                $entity = str_plural($this->routeName);
                $queryBuilder = $parentInstance->$entity();
            }

            $filterParams = $request->get('filter_params');
            if(!empty($filterParams))
            {
                foreach($filterParams as $key => $value)
                {
                    if(!empty($value))
                    {
                        if($key === 'urgent_calculation_serial_no')
                        {
                            $listValues = \App\StockUrgentCalculation::select('serial_no')->where('urgent_calculation_cause_serial_no', $value)->get()->pluck('serial_no')->toArray();
                            $queryBuilder->whereIn('urgent_calculation_serial_no', $listValues);
                            continue;
                        }
                        $type = $controls[$key]['type'];
                        if($type === 'text')
                        {
                            $queryBuilder->where($key, 'like', "%$value%");
                        }else if($type === 'list')
                        {
                            $queryBuilder->where($key, $value);
                        }else if(in_array($type, ['integer', 'float', 'date']))
                        {
                            $dataRange = explode(';', $value);
                            if(isset($dataRange[0]) && $dataRange[0] != '' && isset($dataRange[1]) && $dataRange[1] != '')
                            {
                                $queryBuilder->whereBetween($key, $dataRange);
                            }else if(isset($dataRange[0]) && $dataRange[0] != '')
                            {
                                $queryBuilder->where($key, '>=', $dataRange[0]);
                            }else if(isset($dataRange[1]) && $dataRange[1] != '')
                            {
                                $queryBuilder->where($key, '<=', $dataRange[1]);
                            }
                        }
                    }
                }
            }
            $orderParameter = $request->get('order');
            if(!empty($orderParameter))
            {
                $orderByColumn = $request->get('columns')[$orderParameter[0]['column']]['name'];
                $orderByDirection = $orderParameter[0]['dir'];
                $queryBuilder->orderBy($orderByColumn, $orderByDirection);

            }
            $allRecordsCount = $queryBuilder->count();
            $iTotalRecords = intval($allRecordsCount);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $records = array();

            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            $records["data"] = array();
            $selectColumns = array_merge(['serial_no'], array_keys($columns));
            $dataRecords = $queryBuilder->select($selectColumns)->with('urgent_calculation')->skip($iDisplayStart)->take($iDisplayLength)->get();
            foreach ($dataRecords as $key => $record) {
                //$recordSegment = json_decode($record, true);
                //var_dump($recordSegment);
                $record->DT_RowId = $record->serial_no;
                $record->select_item = '<input type="checkbox" name="id[]" value="'.$record->serial_no.'">';

                $rowData = [];
                $rowData['serial_no'] = $record->serial_no;
                $rowData['urgent_calculation_serial_no'] = '';
                if(!empty($record->urgent_calculation))
                {
                    $record['urgent_calculation_serial_no'] = $record->urgent_calculation->urgent_calculation_cause_serial_no;
                }

                $record->DT_RowData = json_encode($rowData);

                if(isset($this->parentRouteName))
                {
                    $parametersArray = [$request->route($this->parentRouteName), $record->serial_no];
                }else
                {
                    $parametersArray = [$record->serial_no];
                }
                $destroyRouteUrl = route(str_replace('index', 'destroy', $routeNameWithAction), $parametersArray);
                $editRouteUrl = route(str_replace('index', 'edit', $routeNameWithAction), $parametersArray);
                $showRouteUrl = route(str_replace('index', 'show', $routeNameWithAction), $parametersArray);
                $updateRouteUrl = route(str_replace('index', 'update', $routeNameWithAction), $parametersArray);
                //<a class="btn btn-circle btn-icon-only btn-default" href="javascript:;"><i class="icon-magnifier"></i></a>
                //<a class="btn btn-circle btn-icon-only btn-default edit" href="#add-new" data-url="'.$editRouteUrl.'" data-update-url="'.$updateRouteUrl.'"><i class="icon-pencil"></i></a>
                $record->actions = '';
                $record->actions .= '<a class=" font-blue-sharp" href="'.$editRouteUrl.'" title="Edit"><i class="icon-pencil"></i></a> | ';
                $record->actions .= '<a class=" font-green-haze view" href="#" data-url="'.$showRouteUrl.'" title="View"><i class="icon-magnifier"></i></a>';
                if(!empty($model::$customActions))
                {
                    foreach($model::$customActions as $key => $value)
                    {
                        $customRoutUrl = route($value['route'], $parametersArray);
                        $record->actions .= ' | <a class="font-dark" href="'.$customRoutUrl.'" title="'.(isset($value['title'])? $value['title']:'').'"><i class="'.$value['icon'].'"></i></a>';
                    }
                }

                $records['data'][] = $record;//array_values($recordSegment);
            }

            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                //$records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                //$records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }


            return  json_encode($records);
        } else
        {
            $columns['actions'] = ['width' => '100'];

            $data = generateFormFields($controls, null, $model::$formVerticalSections);

            $data['orderBy'] = "[[1, 'asc']]";
            if(isset($model::$orderBy))
            {
                $data['orderBy'] = $model::$orderBy;
            }

            //$routeUrl = route('complaint.table');
            $data['routeUrl'] = $this->indexRouteUrl;

            if(isset($model::$customForm) && $model::$customForm === true)
            {
                $data['createRouteUrl'] = $this->createRouteUrl;
            }
            $data['columns'] = $columns;

            $data['title'] = str_plural($this->modelTitle);
            $data['description'] = "";


            /*
            $data['title'] = "Add New ".$this->modelTitle;
            $data['description'] = "create new ".$this->modelTitle;
            */
            $data['storeRouteUrl'] = $this->storeRouteUrl;

            if(isset($model::$viewJS))
            {
                $data['page_scripts'] = [$model::$viewJS];
            }
            $data['insertable'] = true;
            if(isset($model::$insertable) && $model::$insertable === false)
            {
                $data['insertable'] = false;
            }
            $data['form_id'] = $model::$formId;

            return view('Admin::generic_viewer', $data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $model = $this->model;
        $instance = $model::whereRaw('1=1');
        if(\Auth::user()->user_type->serial_no!==1){
            $instance->whereRaw('entry_date >= DATE(NOW()) - INTERVAL 7 DAY');
        }

        $instance = $instance->findOrFail($request->route($this->routeName));
        if($request->ajax())
        {
            return $instance;
        }else
        {
            $controls = [
            //xview: dont show on datatable
            //list_reference to override system convention of listing
                    'stock_serial_no' => ['type' => 'list', 'updatable' => false, 'validation' => 'required'],
                    'date_closed' => ['type' => 'date', 'updatable' => false, 'width' => '15%', 'validation'=> 'required_with:file'],
                    'close_selling_price' => ['type' => 'float', 'validation'=> 'required|numeric'],
                    'open_selling_price' => ['type' => 'float', 'validation'=> 'numeric'],

            ];

            $data = generateFormFields($controls, $instance->toArray(), $model::$formVerticalSections);
            /*$urgent_calculations = \App\Stock::findOrFail($instance->stock_serial_no)->stock_urgent_calculations()->with('urgent_cause')->get();
            $urgent_calculations_list = array();
            $urgent_calculations_list[0] = "";
            foreach($urgent_calculations as $urgent_calculation)
            {
                $urgent_calculations_list[$urgent_calculation->serial_no] = $urgent_calculation->urgent_cause->label_en;
            }

            $data['rows'][1]['urgent_calculation_serial_no']['field']['list'] = $urgent_calculations_list;
            */
            $data['title'] = "Change opening price ";//.$this->modelTitle;
            $data['description'] = "";

            $data['storeRouteUrl'] = $this->updateRouteUrl;
            $data['method'] = 'PUT';
            $data['action_type'] = 'edit';

            if(isset($model::$editJS))
            {
                $data['page_scripts'] = [$model::$editJS];
            }
            $data['form_id'] = $model::$formId;

            return view('Admin::generic_form', $data);
        }
    }
    public function update(GenericRequest $request)
    {
        $model = $this->model;
        //$urgent_calculation_serial_no = $request->get('urgent_calculation_serial_no');
        $open_selling_price = $request->get('open_selling_price');
        $close_selling_price = $request->get('close_selling_price');

        $instance = $model::findOrFail($request->route($this->routeName));
        $stock_serial_no = $instance->stock_serial_no;
        $date_closed = $instance->date_closed;

        $bindings = [
            'stock_serial_in' => $stock_serial_no,
            'for_date_in' => $date_closed,
            'opening_reading_in' => $open_selling_price
        ];
        if(isset($close_selling_price))
        {
            $bindings['closing_reading_in'] = $close_selling_price;
        }

        try {
            //$dbResponse = \DB::statement('call change_stock_record_opening_price(?, ?, ?, ?)', $bindings);

            //\DB::enableQueryLog();
            $dbResponse = \DB::statement("call change_stock_record_opening_price($stock_serial_no, '$date_closed', '$open_selling_price', '$close_selling_price')");
            //\Log::debug(\DB::getQueryLog());
            if(!$dbResponse)
            {
                \Log::debug("Unable to change stock prices", $bindings);
                return response()->json("Unable to change stock prices", 400);
            }

            $responesData = ['message' => 'Item was updated successfully', 'redirect_url' => $this->indexRouteUrl];
            return response()->json($responesData, 200);
        } catch ( \Exception $e) {
            \Log::error($e);
            return response()->json("Unable to update this item", 400);
        }
        /*$instance->urgent_calculation_serial_no = $urgent_calculation_serial_no;
        if($urgent_calculation_serial_no == 0){
            $instance->urgent_calculation_serial_no = null;
        }*/

        /*if($instance->save())
        {

            $responesData = ['message' => 'Item was updated successfully', 'redirect_url' => $this->indexRouteUrl];
            return response()->json($responesData, 200);
        }else
        {
            return response()->json("Unable to update this item", 400);
        }*/
        
    }

    public function getImport(Request $request)
    {
        $data['title'] = 'Import closing readings';
        $data['stocks'] = \App\Stock::all();
        return view('Admin::stock_closing_reading_upload', $data);
    }

    public function getMarketImport(Request $request)
    {
        $data['title'] = 'Import market closing readings';
        $data['markets'] = \App\StockMarket::all();
        return view('Admin::stock_closing_reading_market_upload', $data);
    }

    public function getLastClosingDate(Request $request, $id)
    {
        $stock = \App\Stock::findOrFail($id);
        $stock_last_reading = $stock->stock_closing_readings()->whereNotNull('close_selling_price')->max('date_closed');
        $stock_open_date = $stock->stock_closing_readings()->max('date_closed');
        if(!empty($stock_last_reading))
        {
            $stock_last_price = $stock->stock_closing_readings()->where('date_closed', $stock_last_reading)->first()->close_selling_price;
            $data['date'] = $stock_last_reading;
            $data['next_date'] = $stock_open_date;
            //TODO
            $data['price'] = $stock_last_price;
            $data['working_days'] = $stock->stock_market->regular_working_days;
            return response()->json($data, 200);
        }else
        {
            return response()->json('Last stock reading date is not available!', 400);
        }
    }

    public function getMarketLastDate(Request $request, $id)
    {
        $stock_market = \App\StockMarket::findOrFail($id);
        $stock_last_reading = $stock_market->stock_closing_readings()->withoutGlobalScopes()->max('date_closed');
        if(!empty($stock_last_reading))
        {
            $data['date'] = $stock_last_reading;
            return response()->json($data, 200);
        }else
        {
            return response()->json('Last stock reading date is not available!', 400);
        }
    }

    public function postImport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:500',
            'stock_serial_no' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $stock_serial_no = $request->get('stock_serial_no');
        $stock = \App\Stock::findOrFail($stock_serial_no);
        $stock_last_reading_r = $stock->stock_closing_readings()->max('date_closed');

        $stock_last_reading = \Carbon\Carbon::parse($request->get('last_closing_date'));
        if(!empty($stock_last_reading_r)){
            $stock_last_reading = \Carbon\Carbon::parse($stock_last_reading_r);
        }
        
        //dd($stock_last_reading);

        $file = \Request::file('file');

        if(!$file->isValid()){
            return response()->json('Last stock reading date is not available!', 400);
        }


        $extension = $file->getClientOriginalExtension();
        $currentTimestamp = \Carbon\Carbon::now()->timestamp;
        $file_name = $currentTimestamp.'_'.$file->getClientOriginalName();
        $result = \Storage::disk('local')->put($file_name,  \File::get($file));


        $file_id = storage_path('app/'.$file_name);
        //dd($file_id);

        /*\Excel::filter('chunk')->load($fileId)->chunk(250, function($results)
        {
            foreach($results as $row)
            {
                $data[] = $row;
            }
        });*/
        global $counter;
        $counter = 0;
        echo '<span style="color: #fff">';


        \Log::info($currentTimestamp.' - Importing Stock ('.$stock_serial_no.') Readings STARTED');
        \Excel::selectSheetsByIndex(0)->load($file_id, function($reader) use ($stock_serial_no, $stock_last_reading, $counter){

            global $counter;
            $results = $reader->get();
            //\DB::transaction(function () use ($results, $stock_serial_no, $stock_last_reading, $counter) {
                global $counter;
                foreach($results as $row)
                {
                    if(!empty($row->closing_date))
                    {
                        if(!$row->closing_date->lt($stock_last_reading))
                        {
                            $closing_date = $row->closing_date->toDateString();
                            echo $row->closing_reading;
                            if($row->closing_reading != 0)
                            {
                                try {
                                    \DB::statement('call save_stock_closing_readings('.$stock_serial_no.', \''.$closing_date.'\', '.$row->closing_reading.')');
                                    $counter++;
                                } catch ( \Exception $e) {
                                    \Log::error($e);
                                }
                            }

                        }
                    }else
                    {
                        break;
                    }
                }
            //});
        });
        \Log::info($currentTimestamp.' - Importing Stock ('.$stock_serial_no.') Readings FINISHED');
        echo '</span>';


        $flashMessage = array();
        \Session::flash('status', 'success');
        \Session::flash('message', $counter.' Closing reading records were imported successfully!');
        return redirect()->route('stock_closing_reading.index');
    }

    public function postMarketImport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:500',
            'market_serial_no' => 'required',
            'last_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        

        $market_serial_no = $request->get('market_serial_no');
        $market = \App\StockMarket::findOrFail($market_serial_no);
        $last_date = $request->get('last_date');
        $last_date = \Carbon\Carbon::parse($last_date);
        //dd($stock_last_reading);

        $file = \Request::file('file');

        if(!$file->isValid()){
            return response()->json('Invalid file uploaded!', 400);
        }



        $extension = $file->getClientOriginalExtension();
        $currentTimestamp = \Carbon\Carbon::now()->timestamp;
        $file_name = $currentTimestamp.'_'.$file->getClientOriginalName();
        $result = \Storage::disk('local')->put($file_name,  \File::get($file));


        $file_id = storage_path('app/'.$file_name);

        /*\Excel::filter('chunk')->load($fileId)->chunk(250, function($results)
        {
            foreach($results as $row)
            {
                $data[] = $row;
            }
        });*/
        global $counter;
        $counter = 0;
        global $logs;
        echo '<span style="color: #fff">';
        \Log::info($currentTimestamp.' - Importing Market Stocks ('.$market_serial_no.') Readings STARTED');
        \Excel::selectSheetsByIndex(0)->load($file_id, function($reader) use ($market_serial_no, $last_date, $counter, $logs){

            global $counter;
            global $logs;
            $results = $reader->get();
            //\DB::transaction(function () use ($results, $market_serial_no, $last_date, $counter, $logs) {
                global $counter;
                global $logs;
                foreach($results as $index => $row)
                {
                    if(!empty($row->stock_symbol))
                    {
                        $stock = \App\Stock::where('stock_symbol', $row->stock_symbol)->where('stock_market_serial_no', $market_serial_no)->first();
                        if(!empty($stock))
                        {
                            $closing_date = $last_date->toDateString();
                            echo $row->closing_reading;
                            if($row->closing_reading != 0)
                            {
                                try {
                                    \DB::statement('call save_stock_closing_readings('.$stock->serial_no.', \''.$closing_date.'\', '.$row->closing_reading.')');
                                    $counter++;
                                } catch ( \Illuminate\Database\QueryException $e) {
                                    $logs[$index+1] = "Duplicate entry for stock '".$row->stock_symbol."' on ".$closing_date;
                                    \Log::error($e);
                                }
                            }else
                            {
                                $logs[$index+1] = "Stock '".$row->stock_symbol."' has ZERO reading on ".$closing_date;
                            }
                        }else
                        {
                            $logs[$index+1] = "Stock of symbol ".$row->stock_symbol." not found";
                        }
                        //if(!$row->closing_date->lt($last_date))
                        //{
                        //}
                    }else
                    {
                        break;
                    }
                }
            //});
        });
        \Log::info($currentTimestamp.' - Importing Market Stocks ('.$market_serial_no.') Readings FINISHED');
        echo '</span>';

        $flashMessage = array();
        $message = $counter.' Closing reading records was imported successfully!';
        $status = 'success';
        if(!empty($logs))
        {
            $message .= '<div> with following warnings: <ul>';
            $status = 'warning';
            foreach($logs as $log_id => $log)
            {
                $message .= '<li>Row #'.$log_id.': '.$log.'</li>';
            }
            $message .= '</ul></div>';
        }
        \Session::flash('status', $status);
        \Session::flash('message', $message);
        return redirect()->route('stock_closing_reading.index');
    }


    public function store(GenericRequest $request)
    {
        $stock_serial_no = $request->get('stock_serial_no');
        $date_closed = $request->get('date_closed');
        $close_selling_price = $request->get('close_selling_price');
        $submit_type = $request->get('submit_type');

        $stock = \App\Stock::findOrFail($stock_serial_no);
        $stock_last_reading = $stock->stock_closing_readings()->max('date_closed');
        $stock_last_reading = \Carbon\Carbon::parse($stock_last_reading);
        $parsed_date_closed = \Carbon\Carbon::parse($date_closed);

        if($parsed_date_closed->lt($stock_last_reading))
        {
            return response()->json("You can not add reading for date before ".$stock_last_reading->toDateString(), 400);
        }
        if($close_selling_price == 0)
        {
            return response()->json("You can not add ZERO reading", 400);
        }

        \DB::statement('call save_stock_closing_readings('.$stock_serial_no.', \''.$date_closed.'\', '.$close_selling_price.')');

        if($request->ajax())
        {
            $routeNameWithAction = \Request::route()->getName();
            $routeUrl = route(str_replace('store', 'index', $routeNameWithAction));
            if(isset($submit_type) && $submit_type=='submit_and_new')
            {
                $routeUrl = route('stock_closing_reading.create');
            }

            $responesData = ['message' => 'Item was added successfully', 'redirect_url' => $routeUrl];
            return response()->json($responesData, 200);
        }else
        {

        }
    }

    public function destroy(Request $request)
    {
        $model = $this->model;

        $ids = explode(',', $request->route($this->routeName));

        $counter = 0;
        foreach($ids as $id)
        {
            try {
                $instance = \App\StockClosingReading::find($id);

                $stock_serial_no = $instance->stock_serial_no;
                $date_closed = $instance->date_closed;

                \DB::statement('call delete_stock_closing_reading('.$stock_serial_no.', \''.$date_closed.'\')');
                $counter++;
            } catch ( \Exception $e) {
                \Log::error($e);
            }
        }

        $message = "Item was deleted successfully";
        if($counter > 1)
        {
            $message = $counter." items were deleted successfully";
        }else if($counter == 0)
        {
            return response()->json("Unable to delete selected items", 400);
        }

        if($request->ajax())
        {
            return response()->json($message, 200);
        }else
        {

        }
    }
}
