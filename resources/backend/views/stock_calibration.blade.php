@extends('Admin::layouts.master')

@section('page_level_plugins_styles')
    <link href="{{asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.skinFlat.css')}}" rel="stylesheet" type="text/css" />

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

    <script src="{{asset('assets/global/plugins/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/ion.rangeslider/js/ion.rangeSlider.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-markdown/lib/markdown.js')}}" type="text/javascript"></script>
@endsection
@section('page_level_scripts')
    <script src="{{asset('assets/pages/scripts/components-select2.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/pages/scripts/form-validation.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        var calibrationRoute = "{{route('stock.calibration', 'stock_serial_no')}}";
        $(document).ready(function(){
            /*$(".range_slider").ionRangeSlider({
                type: "double",
                grid: true,
                from: 0,
                to: 0.3,
                values: [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
            });*/
            $('select[name="stock_serial_no"]:not(.copy-stock-serial-no)').change(function(){

                var target = $(this).closest('.portlet');
                App.blockUI({
                    message: 'Loading',
                    target: target,
                    overlayColor: 'none',
                    cenrerY: true,
                    boxed: true
                });
                var stock_serial_no = $(this).val();
                window.location = calibrationRoute.replace('stock_serial_no', stock_serial_no);
            });
            
            $('.range_input_row').parents('.row').addClass('hidden');
            var activeLevels = $('input[name="activated_up_levels_count"]').val();
            if(activeLevels === undefined || activeLevels === '')
            {
                activeLevels = 0;
            }

            changeUpDownLevels(activeLevels);
            
            $('input[name="s_from"]').closest('.row:not(.range_input_row)').removeClass('hidden');
            $('input[name="activated_up_levels_count"]').on('change', function(){
                var upLevels = $(this).val();
                changeUpDownLevels(upLevels);
            });
            $('input[name="activated_down_levels_count"]').on('change', function(){
                var downLevels = $(this).val();
                changeUpDownLevels(downLevels);
            });

            $('input[name$="_to"]').keyup(function(){
                //'.row:not(.range_input_row)').removeClass('hidden');
                if($(this).is('[name^="u"]'))
                {
                    $(this).closest('.row:not(.range_input_row)').prev().find('input[name$="_from"]').val($(this).val());
                }else if($(this).is('[name^="d"]'))
                {
                    $(this).closest('.row:not(.range_input_row)').next().find('input[name$="_from"]').val($(this).val());
                }
            });


            var copyStockLevelsButton = $('<a class="dt-button buttons-html5 btn green btn-outline" href="#copy-stock-levels" data-toggle="modal" style="font-size: 10px; margin-top: -1px; position: absolute;"><i class="fa fa-copy"></i> Copy Levels</a>');
            $('input[name="activated_up_levels_count"]').focus(function(){
                $(this).after(copyStockLevelsButton);
            });
            /*$('input[name="activated_up_levels_count"]').focusout(function(){
                $(copyStockLevelsButton).detach();
            });*/
            $('input[name="activated_down_levels_count"]').focus(function(){
                $(this).after(copyStockLevelsButton);
            });
            $('input[name="activated_down_levels_count"]').focusout(function(){
                $('input[name="s_from"]').focus();
            });
            /*$(copyStockLevelsButton).tooltip({
                container: 'body',
                title: 'You can copy "Up" and "Down" levels of other stocks to this stock',
                placement: 'bottom'
            });*/
            $('#copy_form').submit(function(event){
                event.preventDefault();
                var postData = $(this).serialize();
                var url = $(this).attr('action');
                var postMethod = $(this).attr('method');

                var target = $(this);
                App.blockUI({
                    message: 'Loading',
                    target: target,
                    overlayColor: 'none',
                    cenrerY: true,
                    boxed: true
                });

                $.ajax({
                    url : url,
                    data: postData,
                    type: postMethod,
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    $('input[name="activated_up_levels_count"]').val(data.activated_up_levels_count);
                    $('input[name="activated_up_levels_count"]').trigger("change");

                    for(var i = 1; i<=data.activated_up_levels_count; i++){
                        $('input[name="u'+(i)+'_from"]').val(data['u'+(i)+'_from']);
                        $('input[name="d'+(i)+'_from"]').val(data['d'+(i)+'_from']);
                        $('input[name="u'+(i)+'_to"]').val(data['u'+(i)+'_to']);
                        $('input[name="d'+(i)+'_to"]').val(data['d'+(i)+'_to']);
                    }
                    $('input[name="s_from"]').val(data['s_from']);
                    $('input[name="s_to"]').val(data['s_to']);

                    toastr['info']('Stock level was copied successfully', "Info");
                    $('#copy-stock-levels').modal('toggle');
                    App.scrollTo($('input[name="activated_up_levels_count"]'));

                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
                    }else
                    {
                        var data = jqXHR.responseJSON;
                        data = convertErrorMessage(data);
                        toastr['error'](data, "Error")
                    }
                }).always(function(data) {
                    App.unblockUI(target);
                });
            });


            $('#custom_form').submit(function(event){
                event.preventDefault();
                var form = $(this);
                var postData = $(form).serialize();
                var url = $(form).attr('action');
                var postMethod = $(form).attr('method');

                var target = $(this).closest('.portlet');
                App.blockUI({
                    message: 'Loading',
                    target: target,
                    overlayColor: 'none',
                    cenrerY: true,
                    boxed: true
                });

                $.ajax({
                    url : url,
                    data: postData,
                    type: postMethod,
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    $('#calibration_status').html('<i class="fa fa-spin fa-spinner"></i><span class="label label-success hidden"> Finished </span>');
                    $('#prediction_status').html('<i class="fa fa-spin fa-spinner hidden"></i><span class="label label-warning"> Waiting </span>');
                    $('button.calibration-close-button').addClass('hidden');
                    $('#status').modal('toggle');
                    $('a.submit-button').attr('href', data.redirect_url);
                    var cal = calibrationProgress();
                    /*$.ajax({
                        url : '{{url("run")}}',
                        type: 'POST',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }});*/
                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
                    }else if(jqXHR.status === 504)
                    {
                        toastr['error']("Connection timeout", "Error")
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

            $('a.submit-button').click(function(ev){
                ev.preventDefault();
                if(!$(this).hasClass('disabled'))
                {
                    window.location = $(this).attr('href');
                }
            })
        });
        function calibrationProgress()
        {

            $.ajax({
                url : '{{url("stock/".$stock_serial_no."/calibration_progress")}}',
                type: 'GET',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }}).done(function(data){
                if(data.calibration_job_c == 'FINISHED')
                {
                    $('#calibration_status i').addClass('hidden');
                    $('#calibration_status span').removeClass('hidden');
                    if(data.calibration_job_p == 'FINISHED')
                    {
                        $('#prediction_status i').addClass('hidden');
                        $('#prediction_status span').html('Finished');
                        $('#prediction_status span').addClass('label-success');
                        $('#prediction_status span').removeClass('label-warning hidden');
                        $('a.submit-button').removeAttr('disabled');
                        $('a.submit-button').removeClass('disabled');
                    }else if(data.calibration_job_p == 'ERROR')
                    {
                        $('#prediction_status i').addClass('hidden');
                        $('#prediction_status span').html('Error');
                        $('#prediction_status span').addClass('label-danger');
                        $('#prediction_status span').removeClass('label-warning hidden');
                        $('button.calibration-close-button').removeClass('hidden');
                    }else
                    {
                        $('#prediction_status i').removeClass('hidden');
                        $('#prediction_status span').addClass('hidden');
                    }
                }else if(data.calibration_job_c == 'ERROR')
                {
                    $('#calibration_status i').addClass('hidden');
                    $('#calibration_status span').html('Error');
                    $('#calibration_status span').addClass('label-danger');
                    $('#calibration_status span').removeClass('label-success hidden');
                    $('button.calibration-close-button').removeClass('hidden');
                }

                if(data.calibration_job_p != 'FINISHED' && data.calibration_job_p != 'ERROR')
                {
                    setTimeout(function() {
                        calibrationProgress();
                    }, 3000);
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                if(jqXHR.status === 500)
                {
                    toastr['error']("Internal Error code: 500", "Error")
                }else if(jqXHR.status === 504)
                {
                    toastr['error']("Connection timeout", "Error")
                }else
                {
                    var data = jqXHR.responseJSON;
                    //data = convertErrorMessage(data);
                    toastr['error'](data, "Error")
                }
            }).always(function(data) {
            });
        }
        function changeUpDownLevels(levels)
        {
            levels = parseInt(levels);
            $('input[name^="u"]').closest('.row:not(.range_input_row)').removeClass('hidden');
            $('input[name^="d"]').closest('.row:not(.range_input_row)').removeClass('hidden');
            $('input[name^="u"]').removeAttr('disabled');
            $('input[name^="d"]').removeAttr('disabled');
            for(var i = (levels+1); i<=9; i++){
                $('input[name="u'+(i)+'_from"]').closest('.row:not(.range_input_row)').addClass('hidden');
                $('input[name="d'+(i)+'_from"]').closest('.row:not(.range_input_row)').addClass('hidden');
            }

            $('input[name="s_from"]').attr('tabindex', 1);
            $('input[name="s_to"]').attr('tabindex', 1);
            for(var i = 0; i<9; i++){
                $('input[name="u'+(i)+'_from"]').attr('tabindex', (i*2)+1);
                $('input[name="u'+(i)+'_to"]').attr('tabindex', (i*2)+2);

                $('input[name="d'+(i)+'_from"]').attr('tabindex', (i*2)+1+18);
                $('input[name="d'+(i)+'_to"]').attr('tabindex', (i*2)+2+18);
            }
            $('input[name="u'+levels+'_to"]').attr('disabled', 'disabled');
            $('input[name="d'+levels+'_to"]').attr('disabled', 'disabled');
        }
    </script>
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
            
            @include('Admin::forms.generic', ['type' => 'portlet', 'form_id' => 'custom_form'])

            <div id="copy-stock-levels" class="modal fade container" data-backdrop="copy-stock-levels" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">{{trans('titles.copy_levels_and_ranges')}}</h4>
                        </div>
                        <!-- BEGIN FORM-->
                        <form id="copy_form" action="{{url('stock/levels')}}" method="GET" class="form-horizontal modal-form">
                        <div class="modal-body"> 
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">{{trans('labels.stock')}}</label>
                                                <div class="col-md-9">
                                                    <select class="form-control select2 copy-stock-serial-no" name="stock_serial_no" placeholder="Select a Stock">
                                                        <option></option>
                                                        @foreach(\App\StockMarket::all() as $market)
                                                        <optgroup label="{{$market->label}}">
                                                        @foreach($market->stocks as $stock)
                                                        <option value="{{$stock->serial_no}}">{{$stock->stock_name}}</option>
                                                        @endforeach
                                                        </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{trans('actions.close')}}</button>
                            <button type="submit" class="btn green"><i class="fa fa-spinner fa-spin hidden"> </i> {{trans('actions.submit')}}</button>
                        </div>
                        </form>
                        <!-- END FORM-->
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>


            <div id="status" class="modal fade" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{trans('titles.calibration_status')}}</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{trans('labels.process')}}</th>
                                        <th>{{trans('labels.status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{trans('labels.calibrating_stock_readings')}}</td>
                                        <td id="calibration_status"><i class="fa fa-spin fa-spinner"></i><span class="label label-success hidden"> {{trans('labels.finished')}} </span></td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('labels.predicting_readings')}}</td>
                                        <td id="prediction_status"><i class="fa fa-spin fa-spinner hidden"></i><span class="label label-warning"> {{trans('labels.waiting')}} </span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline calibration-close-button hidden" data-dismiss="modal">{{trans('actions.close')}}</button>
                            <a href="" class="btn green submit-button disabled" disabled="disabled">{{trans('actions.continue')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT BODY -->
<!-- END CONTENT BODY -->
@endsection

@section('modals')
@endsection