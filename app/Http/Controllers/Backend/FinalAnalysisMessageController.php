<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GenericRequest;

class FinalAnalysisMessageController extends GenericController
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
	    	'message_en' => ['type' => 'text', ],
	    	'message_ar' => ['type' => 'text', ],
    	];
        $columns = array();
        foreach($controls as $controlName => $control)
        {
            if(!in_array($control['type'], ['url', 'editor', 'textarea', 'password', 'range_slider', 'range_input', 'label', 'empty', 'multiselect', 'tabular', 'urgent-calculation-tabular']))
            {
                if(!(isset($control['xview']) && $control['xview']))
                {
                    if($control['type'] === 'list' && empty($control['list']))
                    {
                        if(isset($control['list_reference']))
                        {
                            $modelName = $control['list_reference'];
                        }else
                        {
                            if(strpos($controlName, 'serial_no') !== FALSE)
                            {
                                $modelName = str_replace('_serial_no', '', $controlName);
                            }
                        }
                        $className = studly_case($modelName);
                        $cModel = 'App\\'.$className;
                        $control['list'] = $cModel::lists($cModel::getLabel(), 'serial_no');
                    }
                    if(isset($control['width']))
                    {
                        $control['width'] = $control['width'];
                    }
                    $columns[$controlName] = $control;
                }
            }
        }

        if($request->ajax())
        {
            $queryBuilder = $model::whereRaw('1=1');

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
                    if(isset($value))
                    {
                        $type = $controls[$key]['type'];
                        if($type === 'text')
                        {
                            $queryBuilder->where($key, 'like', "%$value%");
                        }else if(in_array($type, ['list', 'boolean', 'group_list']) && $value != '')
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
            $iDisplayLength = intval($request->length);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($request->start);
            $sEcho = intval($request->draw);
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $records = array();

            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            $records["data"] = array();
            $selectColumns = array_merge(['serial_no'], array_keys($columns));

            //\DB::enableQueryLog();
            $dataRecords = $queryBuilder->select($selectColumns)->skip($iDisplayStart)->take($iDisplayLength)->get();
            //\Log::debug(\DB::getQueryLog());
            foreach ($dataRecords as $key => $record) {
                //$recordSegment = json_decode($record, true);
                //var_dump($recordSegment);
                $record->DT_RowId = $record->serial_no;
                $record->select_item = '<input type="checkbox" name="id[]" value="'.$record->serial_no.'">';

                $rowData = [];
                $rowData['serial_no'] = $record->serial_no;
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
                if($model::$deletable !== false)
                {
                $record->actions .= '<a class="font-red-thunderbird destroy" data-toggle="confirmation" data-popout="true" data-singleton="true" data-url="'.$destroyRouteUrl.'" title="Delete"><i class="icon-trash"></i></a> | ';
                }
                $record->actions .= '<a class=" font-blue-sharp" href="'.$editRouteUrl.'" title="Edit"><i class="icon-pencil"></i></a> | ';
                $record->actions .= '<a class=" font-green-haze view" href="#" data-url="'.$showRouteUrl.'" title="View"><i class="icon-magnifier"></i></a>';
                if(!empty($model::$customActions))
                {
                    foreach($model::$customActions as $key => $value)
                    {
                        $customRoutUrl = route($value['route'], $parametersArray);
                        $extra = '';
                        if(isset($value['type']) && $value['type'] == 'ajax')
                        {
                            $extra = ' data-toggle="confirmation" data-popout="true" data-singleton="true" data-url="'.$customRoutUrl.'" ';
                        }else
                        {
                            $extra = ' href="'.$customRoutUrl.'"';
                        }
                        $class = '';
                        if(isset($value['class']))
                        {
                            $class = $value['class'];
                        }
                        $record->actions .= ' | <a '.$extra.' class="font-dark '.$class.'" title="'.(isset($value['title'])? $value['title']:'').'"><i class="'.$value['icon'].'"></i></a>';
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
    }}
