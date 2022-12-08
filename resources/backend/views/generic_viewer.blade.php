@extends('Admin::layouts.master')

@section('page_level_plugins_styles')
    <link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="Stylesheet" href="{{asset('assets/global/plugins/weekLine-master/styles/cleanslate.css')}}" />
    <link rel="Stylesheet" href="{{asset('assets/global/plugins/weekLine-master/styles/jquery.weekLine.css')}}" />

@endsection
@section('page_level_styles')
    <style type="text/css">
        .tools > a > i
        {
            position: relative;
            top: -3px;
        }
    </style>
@endsection


@section('page_level_plugins_scripts')
    <script src="{{asset('assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>

    <script src="{{asset('assets/global/scripts/datatable.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>

    <script src="{{asset('assets/global/plugins/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/weekLine-master/scripts/jquery.weekLine.min.js')}}"></script>
@endsection
@section('page_level_scripts')
    <script src="{{asset('assets/pages/scripts/components-select2.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/pages/scripts/ui-extended-modals.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/pages/scripts/form-validation.js')}}" type="text/javascript"></script>

    @if(isset($page_scripts))
        @foreach($page_scripts as $script)
    <script src="{{asset('assets/pages/scripts/'.$script)}}" type="text/javascript"></script>
        @endforeach
    @endif
@endsection

@section('content')

<!--
<h3 class="page-title"> {{ $title }}
    <small>{{ $description }}</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>{{ $title }}</span>
        </li>
    </ul>
</div>
 END PAGE HEADER-->


<!-- BEGIN CONTENT BODY -->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="#">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>{{ $title }}</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">



<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-red-sunglo bold uppercase">{{ $title }}</span>
            <span class="caption-helper">{{ $description }}</span>
        </div>
        <div class="tools">
            <div class="dt-buttons" style="margin-top: -9px;">
                @if(isset($insertable) && $insertable)
                <a class="dt-button buttons-html5 btn red btn-outline" href="{{ $createRouteUrl or '#add-new'}}">   <i class="fa fa-plus"></i> {{trans('actions.new')}}
                </a>
                @endif
                <!--<a class="dt-button buttons-html5 btn green btn-outline search" href="#"><i class="fa fa-search"></i> Search</a>-->
                
                <div class="btn-group actions" >
                    <button type="button" class="dt-button buttons-html5 btn yellow btn-outline dropdown-toggle export" data-toggle="dropdown" data-original-title="Export" title="Export" aria-expanded="false">
                        <i class="fa fa-share"></i> {{trans('actions.export')}}
                    </button>
                    <ul class="dropdown-menu pull-right tool-actions">
                        <li>
                            <a href="javascript:;" data-action="3" class="tool-action">
                                <i class="icon-printer"></i> {{trans('actions.print')}}</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-action="0" class="tool-action">
                                <i class="icon-check"></i> {{trans('actions.copy')}}</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-action="1" class="tool-action">
                                <i class="icon-paper-clip"></i> {{trans('actions.excel')}}</a>
                        </li>
                    </ul>
                </div>

                <div class="btn-group">
                    <button type="button" class="dt-button buttons-html5 btn green btn-outline select" style="margin-right: 0px;"><i class="fa fa-check-square-o"></i> {{trans('actions.select')}}</button>
                    <button type="button" class="dt-button buttons-html5 btn green btn-outline dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="javascript:;" data-url="{{str_replace('?', '', $routeUrl)}}" class="bulk-delete"><i class="fa fa-trash"></i> {{trans('actions.delete')}} </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <div class="portlet-body">
        @if(\Session::has('message'))
        <div class="alert alert-{{\Session::get('status')}} alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            {!!\Session::get('message')!!}
        </div>
        @endif
        <div class="table-container">
            <table id="ajax_datatable" data-url="{{ $routeUrl }}" data-orders="{{ $orderBy }}" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr role="row" class="heading">
                        <th width="1%" data-name="select_item" data-orderable="false">
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                <span></span>
                            </label>
                        </th>
                    @foreach($columns as $column_name => $column)
                        <th width="{{$column['width'] or 0}}" data-name="{{$column_name}}" data-orderable="{{$column_name==='actions'? 'false':'true'}}"> {{ trans('labels.'.$column_name) }} </th>
                    @endforeach
                    </tr>
                    <tr role="row" class="filter">
                        <td> </td>
                    @foreach($columns as $column_name => $column)
                        <td>
                        @if($column_name === 'actions')
                            <div class="margin-bottom-5">
                                <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                                    <i class="fa fa-search"></i> {{trans('actions.search')}}</button>
                            </div>
                            <button class="btn btn-sm red btn-outline filter-cancel">
                                <i class="fa fa-times"></i> {{trans('actions.reset')}}</button>
                        @elseif(in_array($column['type'], ['text', 'textarea']))
                            <input type="text" class="form-control form-filter input-sm" name="{{$column_name}}">
                        @elseif($column['type'] === 'date')
                            <div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control form-filter input-sm filter_range" data-range="from" readonly name="{{$column_name}}" placeholder="{{trans('labels.from')}}">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm default" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control form-filter input-sm filter_range" data-range="to" readonly name="{{$column_name}}" placeholder="{{trans('labels.to')}}">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm default" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        @elseif(in_array($column['type'], ['integer', 'float']))
                            <div class="margin-bottom-5">
                                <input type="text" class="form-control form-filter input-sm filter_range" data-range="from" name="{{$column_name}}" placeholder="{{trans('labels.from')}}" />
                            </div>
                            <input type="text" class="form-control form-filter input-sm filter_range" data-range="to" name="{{$column_name}}" placeholder="{{trans('labels.to')}}" /> 
                        @elseif($column['type'] === 'list')
                            <select name="{{$column_name}}" class="form-control form-filter input-sm select2" data-placeholder="{{trans('messages.choose_a_selection')}}">
                                <option value="">{{trans('actions.select')}}...</option>
                                @foreach($column['list'] as $listKey => $listValue)
                                <option value="{{ $listKey }}">{{ $listValue }}</option>
                                @endforeach
                            </select>
                        @elseif($column['type'] === 'boolean')
                        <?php
                            $switchOnText = trans('labels.YES');
                            $switchOffText = trans('labels.NO');
                            if(isset($column['switch']))
                            {
                                $switchOnText = $column['switch'][0];
                                $switchOffText = $column['switch'][1];
                            }
                        ?>
                            <select name="{{$column_name}}" class="form-control form-filter input-sm select2 searchable" data-placeholder="{{trans('messages.choose_a_selection')}}">
                                <option value=""></option>
                                <option value="1">{{$switchOnText}}</option>
                                <option value="0">{{$switchOffText}}</option>
                            </select>
                            <!--<input type="checkbox" name="{{$column_name}}" class="make-switch" checked data-on-text="{{$switchOnText}}" data-off-text="{{$switchOffText}}" value="1">-->
                        @endif 
                        </td>
                    @endforeach
                    </tr>
                </thead>
                <tbody> </tbody>
            </table>
        </div>
    </div>
</div>

<div id="add-new" class="modal fade container" tabindex="-1" data-backdrop="add-new" data-keyboard="false">
    @include('Admin::forms.generic', ['type' => 'modal'])
</div>


<div id="view-item" class="modal fade container" tabindex="-1" data-backdrop="add-new" data-keyboard="false">
    @include('Admin::forms.generic', ['type' => 'modal'])
</div>


        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT BODY -->
<!-- END CONTENT BODY -->
@endsection
