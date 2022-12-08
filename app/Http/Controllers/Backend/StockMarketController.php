<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GenericRequest;

class StockMarketController extends GenericController
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
        $controls = $model::$fillableFields;
        $columns = array();
        $columns = [
                'logo' => ['type' => 'image', 'validation' => 'image|max:200'],
                'label_en' => ['type' => 'text', 'validation'=> 'max:245'],
                'label_ar' => ['type' => 'text', 'validation'=> 'max:245'],
                'stock_rate' => ['type' => 'float', 'validation'=> 'required|numeric'],
                'stock_price_decimal_places' => ['type' => 'integer', 'validation'=> 'required|numeric'],
                'market_price' => ['type' => 'float', 'validation'=> 'numeric'],
                'maximum_limit_up' => ['type' => 'float', 'validation'=> 'required|numeric'],
                'maximum_limit_down' => ['type' => 'float', 'validation'=> 'required|numeric'],
                'active' => ['type' => 'boolean', 'switch' => ['YES', 'NO'], ],
            ];

        if($request->ajax())
        {
            $queryBuilder = $model::whereRaw('1=1');

            $filterParams = $request->get('filter_params');
            if(!empty($filterParams))
            {
                foreach($filterParams as $key => $value)
                {
                    if(!empty($value))
                    {
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
                            if(!empty($dataRange[0]) && !empty($dataRange[1]))
                            {
                                $queryBuilder->whereBetween($key, $dataRange);
                            }else if(!empty($dataRange[0]))
                            {
                                $queryBuilder->where($key, '>=', $value);
                            }else if(!empty($dataRange[1]))
                            {
                                $queryBuilder->where($key, '<=', $value);
                            }
                        }
                    }
                }
            }
            $orderParameters = $request->get('order');
            if(!empty($orderParameters))
            {
                foreach($orderParameters as $orderParameter)
                {
                    $orderByColumn = $request->get('columns')[$orderParameter['column']]['name'];
                    $orderByDirection = $orderParameter['dir'];
                    $queryBuilder->orderBy($model::getTableName().'.'.$orderByColumn, $orderByDirection);
                }

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
            $selectColumns = array_merge(['serial_no'], array_keys($columns), ['official_website_url']);
            $dataRecords = $queryBuilder->select($selectColumns)->skip($iDisplayStart)->take($iDisplayLength)->get();
            foreach ($dataRecords as $key => $record) {
                //$recordSegment = json_decode($record, true);
                //var_dump($recordSegment);
                $record->DT_RowId = $record->serial_no;
                $record->select_item = '<input type="checkbox" name="id[]" value="'.$record->serial_no.'">';
                if(!empty($record->official_website_url))
                {
                    $record->label_en = $record->label_en_url;
                    $record->label_ar = $record->label_ar_url;
                }

                $rowData = [];
                $rowData['serial_no'] = $record->serial_no;
                $record->DT_RowData = json_encode($rowData);

                $parametersArray = [$record->serial_no];
                $destroyRouteUrl = route(str_replace('index', 'destroy', $routeNameWithAction), $parametersArray);
                $editRouteUrl = route(str_replace('index', 'edit', $routeNameWithAction), $parametersArray);
                $showRouteUrl = route(str_replace('index', 'show', $routeNameWithAction), $parametersArray);
                $updateRouteUrl = route(str_replace('index', 'update', $routeNameWithAction), $parametersArray);
                //<a class="btn btn-circle btn-icon-only btn-default" href="javascript:;"><i class="icon-magnifier"></i></a>
                //<a class="btn btn-circle btn-icon-only btn-default edit" href="#add-new" data-url="'.$editRouteUrl.'" data-update-url="'.$updateRouteUrl.'"><i class="icon-pencil"></i></a>
                $record->actions = '<a class="font-red-thunderbird destroy" data-toggle="confirmation" data-popout="true" data-singleton="true" data-url="'.$destroyRouteUrl.'" title="Delete"><i class="icon-trash"></i></a> | <a class=" font-blue-sharp" href="'.$editRouteUrl.'" title="Edit"><i class="icon-pencil"></i></a> | <a class=" font-green-haze view" href="#" data-url="'.$showRouteUrl.'" title="View"><i class="icon-magnifier"></i></a>';
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GenericRequest $request)
    {
        //
        $instance = new \App\StockMarket();
        $instance->label_en = $request->get('label_en');
        $instance->label_ar = $request->get('label_ar');
        $instance->description_en = $request->get('description_en');
        $instance->description_ar = $request->get('description_ar');
        $instance->regular_working_days = $request->get('regular_working_days');
        $instance->official_date_format = $request->get('official_date_format');
        $instance->official_website_url = $request->get('official_website_url');
        $instance->close_prices_rss_url = $request->get('close_prices_rss_url');
        $instance->rss_pulling_time = $request->get('rss_pulling_time');
        $instance->stock_rate = $request->get('stock_rate');
        $instance->stock_price_decimal_places = $request->get('stock_price_decimal_places');
        $instance->market_price = $request->get('market_price');
        $instance->maximum_limit_up = $request->get('maximum_limit_up');
        $instance->maximum_limit_down = $request->get('maximum_limit_down');

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

            $instance->logo = $file_full_name;
        }

        $instance->save();

        $flashMessage = array();
        \Session::flash('status', 'success');
        \Session::flash('message', 'Item was added successfully!');
        return redirect()->route('stock_market.index');
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
        //
        $instance = \App\StockMarket::findOrFail($request->route('stock_market'));
        $instance->label_en = $request->get('label_en');
        $instance->label_ar = $request->get('label_ar');
        $instance->description_en = $request->get('description_en');
        $instance->description_ar = $request->get('description_ar');
        $instance->regular_working_days = $request->get('regular_working_days');
        $instance->official_date_format = $request->get('official_date_format');
        $instance->official_website_url = $request->get('official_website_url');
        $instance->close_prices_rss_url = $request->get('close_prices_rss_url');
        $instance->rss_pulling_time = $request->get('rss_pulling_time');
        $instance->stock_rate = $request->get('stock_rate');
        $instance->stock_price_decimal_places = $request->get('stock_price_decimal_places');
        $instance->market_price = $request->get('market_price');
        $instance->maximum_limit_up = $request->get('maximum_limit_up');
        $instance->maximum_limit_down = $request->get('maximum_limit_down');
        $instance->active = $request->get('active');

        if($instance->active === '' || !isset($instance->active))
        {
            $instance->active = false;
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

            $instance->logo = $file_full_name;
        }

        $instance->save();

        $flashMessage = array();
        \Session::flash('status', 'success');
        \Session::flash('message', 'Item was updated successfully!');
        return redirect()->route('stock_market.index');
    }

    public function getMarketDecimalPlaces($stock_market)
    {
        $stock_market = \App\StockMarket::findOrFail($stock_market);
        return $stock_market->stock_price_decimal_places;
    }
}
