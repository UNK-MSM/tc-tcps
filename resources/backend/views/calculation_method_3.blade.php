@extends('Admin::layouts.master')

@section('page_level_plugins_styles')
    <link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
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
        .help-block
        {
            font-size: 11px;
        }
    </style>


    <style type="text/css">
        table th, table td {
            border: 1px dotted gray;
            padding: 5px;
            background-color: white;
        }         
        table th {
            font-weight: bold;
        }           
        table#calcualtion-details tr:nth-child(2n + 1) td {
            background-color: silver;
        }
        table tr span {
            display: inline-block;
            font-weight: normal;
            margin-left: 20px;
        }
        table {
            margin-bottom: 20px;
            margin-left: auto;
            margin-right: auto;
            text-align: left;
        }
        input, select {
            padding: 10px;
            border: 1px solid gray;
            border-radius: 15px;
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


{{trans('labels.empty')}}
    <script type="text/javascript">
        $(document).ready(function() {

            $('select[name="stock_serial_no"]').on('change', function(){

                var target = $(this).closest('.portlet-body');
                
                App.blockUI({
                    message: 'Loading',
                    target: target,
                    overlayColor: 'none',
                    cenrerY: true,
                    boxed: true
                });
                var stock_serial_no = $(this).val();

                $.ajax({
                    url : '../stock_closing_reading/lcd/'+stock_serial_no,
                    type: 'GET',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    $('#closing-price').next().html('{{trans("labels.last_closing_price")}}: <span class="font-green-sharp">'+data.price+'</span>');
                    $('#closing-price').val(data.price);

                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
                    }else if(jqXHR.status === 400){
                        var data = jqXHR.responseJSON;
                        //data = convertErrorMessage(data);
                        toastr['error'](data, "Error")
                    }else
                    {
                        var data = jqXHR.responseJSON;
                        //data = convertErrorMessage(data);
                        toastr['error'](data, "Error")
                    }
                }).always(function(data) {
                    App.unblockUI(target);
                });

                $.ajax({
                    url : '../stock/'+stock_serial_no+'/urgent_causes',
                    type: 'GET',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    var select_data = [];
                    $.each(data, function(key, value){
                        select_data[select_data.length] = { id: value.serial_no, text: value.label };
                    });
                    $('select[name="urgent_cause_serial_no"]').select2({
                        data: select_data
                    });

                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
                    }else if(jqXHR.status === 400){
                        var data = jqXHR.responseJSON;
                        //data = convertErrorMessage(data);
                        toastr['error'](data, "Error")
                    }else
                    {
                        var data = jqXHR.responseJSON;
                        //data = convertErrorMessage(data);
                        toastr['error'](data, "Error")
                    }
                }).always(function(data) {
                    App.unblockUI(target);
                });
            });
            if($('#closing-price').val()==0)
            {
                $('select[name="stock_serial_no"]').trigger("change");
            }
        });
    </script>
@endsection

@section('content')

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
        </div>
    </div>
    <div class="portlet-body">
        @if(\Session::has('message'))
        <div class="alert alert-{{\Session::get('status')}} alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            {!!\Session::get('message')!!}
        </div>
        @endif


        <?php if ($has_results) { ?>
        <table>
            <tr>
                <th>{{trans('labels.stock')}}</th>
                <th>{{trans('labels.generator')}}</th>
                <th>{{trans('labels.urgent_cause')}}</th>
                <th>{{trans('labels.last_closing_price')}}</th>
                <th>{{trans('labels.predicted_price')}}</th>
            </tr>
            <tr>
                <td>{{$stock->stock_name}}</td>
                <td>{{$generator->generator_name}}</td>
                <td>{{$urgent_cause->label}}</td>
                <td>{{$closing_price}}</td>
            @if($predicted_closing_price_out > $closing_price)
                <td style="background-color: green; color: white;">
            @elseif($predicted_closing_price_out < $closing_price)
                <td style="background-color: #e43a45; color: white;">
            @else
                <td style="background-color: #cedde3; color: white;">
            @endif
                    {{round($predicted_closing_price_out, $decimal_places)}}
                </td>
            </tr>
        </table>
        <?php } ?>


        <form method="POST" style="text-align: center;">
        {{ csrf_field() }}
            <div class="row">
                <div class="col-md-offset-1 col-md-2">
                    <label for="select2-multiple-input-sm" class="control-label">{{trans('labels.stock')}}</label>
                    <select name="stock_serial_no" class="form-control select2">
                    @foreach($markets as $market)
                        <optgroup label="{{$market->label}}">
                        @foreach($market->stocks as $stock)
                            <option <?php echo $stock_serial_no == $stock->serial_no ? 'selected' : ''; ?> value="{{ $stock->serial_no }}">{{ $stock->stock_name }}</option>
                        @endforeach
                        </optgroup>
                    @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="select2-single-input-sm" class="control-label">{{trans('labels.generator')}}</label>
                    <select name="generator_type" class="form-control select2">
                        @if(!empty($generators))
                        @foreach($generators as $generator)
                        <option <?php echo $generator_type == $generator->serial_no ? 'selected' : ''; ?> value="{{$generator->serial_no}}">{{$generator->generator_name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="select2-single-input-sm" class="control-label">{{trans('labels.urgent_cause')}}</label>
                    <select name="urgent_cause_serial_no" class="form-control select2">
                        @if(!empty($stock_urgent_causes))
                        @foreach($stock_urgent_causes as $stock_urgent_cause)
                        <option <?php echo $urgent_cause_serial_no == $stock_urgent_cause->serial_no ? 'selected' : ''; ?> value="{{$stock_urgent_cause->serial_no}}">{{$stock_urgent_cause->label}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="bootstrap-input-sm" class="control-label">{{trans('labels.last_closing_price')}}</label>
                    <input type="text" id="closing-price" name="closing-price" class="form-control input-sm" placeholder="{{trans('labels.stock_prior_closing_price')}}" value="<?php echo ($closing_price == "" || $closing_price == 0) ? round($predicted_closing_price_out, $decimal_places): $closing_price; ?>"/>
                    <p class="help-block"></p>
                </div>
                <div class="col-md-2">
                    <label class="control-label"></label>
                    <div class="input-group" style="margin-top: 6px;">
                        <input type="submit" value="{{trans('actions.calculate')}}" style="margin-right:5px;" class="btn blue-madison input-sm" />
                        <input type="hidden" name="requesting" value="true" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT BODY -->
<!-- END CONTENT BODY -->
@endsection
