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
    <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.skinFlat.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />

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

    <script src="{{asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>

    <script src="{{asset('assets/global/plugins/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/ion.rangeslider/js/ion.rangeSlider.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-markdown/lib/markdown.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
@endsection
@section('page_level_scripts')
    <script src="{{asset('assets/pages/scripts/components-select2.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/pages/scripts/form-validation.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
    var settings = {!!\App\Setting::find(1)!!};
        $(document).ready(function(){
            $('#switch_invert_tuning_enabled_2').next().remove();
            $('#switch_push_tuning_enabled_2').next().remove();
            var lastInvTunVal = $("input:radio[name='invert_tuning_enabled']:checked").val();
            var lastPushTunVal = $('input:radio[name="push_tuning_enabled"]:checked').val();
            $('#switch_invert_tuning_enabled_1, #switch_invert_tuning_enabled_0').click(function(){
                if(lastInvTunVal == $(this).val())
                {
                    $("#switch_invert_tuning_enabled_1").removeAttr('checked');
                    $("#switch_invert_tuning_enabled_0").removeAttr('checked');
                    $("#switch_invert_tuning_enabled_2").prop("checked", true); 
                }else
                {
                    $("#switch_invert_tuning_enabled_2").removeAttr('checked');
                }
                lastInvTunVal = $("input:radio[name='invert_tuning_enabled']:checked").val();
            });
            $('#switch_push_tuning_enabled_1, #switch_push_tuning_enabled_0').click(function(){
                if(lastPushTunVal == $(this).val())
                {
                    $("#switch_push_tuning_enabled_1").removeAttr('checked');
                    $("#switch_push_tuning_enabled_0").removeAttr('checked');
                    $("#switch_push_tuning_enabled_2").prop("checked", true); 
                }else
                {
                    $("#switch_push_tuning_enabled_2").removeAttr('checked');
                }
                lastPushTunVal = $("input:radio[name='push_tuning_enabled']:checked").val();
            });
            var refreshSettings = '<a style="font-size: 24px;" class="font-green-sharp refresh-settings" data-toggle="confirmation" data-popout="true" data-singleton="true" title="Reset to Default Settings?"><i class="icon-refresh"></i></a> ';
            //$('.calculation-settings-label').html($('.calculation-settings-label').html()+'    '+refreshSettings);
            $('.refresh-settings').confirmation(
            {
                container: 'body',
                btnOkClass: 'btn btn-sm btn-success',
                btnCancelClass: 'btn btn-sm btn-danger'
            });
            $.each(settings, function(k, v){
                //console.log($('input[name="'+k+'"'));
                //console.log(v);
            });
            $('body').on('confirmed.bs.confirmation', '[data-toggle="confirmation"].refresh-settings', function () {
                console.log(settings);
                GenereicFormValidation.populateDataInForm($('#generic_form'), settings);
            });

            $('.tabular').closest('.col-md-6').addClass('col-md-12').removeClass('col-md-6');
            $('.tabular').closest('.form-group').find('.control-label').addClass('col-md-2').removeClass('col-md-3');
            $('.table-scrollable').removeClass('table-scrollable');
            var oTable = $('.tabular').dataTable({
                searching: false,
                ordering:  false,
                select: false,
                paging: true,
                info: false,
                pageLength: 5,
                "pagingType": "bootstrap_extended", // pagination type(bootstrap, bootstrap_full_number or bootstrap_extended)
                "autoWidth": false, // disable fixed width and enable fluid table
                dom: "t<'row'<'col-md-5 col-sm-12'><'col-md-7 col-sm-12'p>>",
                //dom: 'Bfrtip',
                //dom: "B<'table-responsive table-scrollable flip-scroll't>", // datatable layout


                // setup responsive extension: http://datatables.net/extensions/responsive/
                //responsive: true,

            });

            /*$(".range_slider").ionRangeSlider({
                type: "double",
                grid: true,
                from: 0,
                to: 0.3,
                values: [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
            });*/
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

            $('select[name="stock_market_serial_no"]').on('change', function(){

                var target = $(this).closest('.portlet-body');
                App.blockUI({
                    message: 'Loading',
                    target: target,
                    overlayColor: 'none',
                    cenrerY: true,
                    boxed: true
                });
                var stock_market_serial_no = $(this).val();
                var url = $(this).data('url');

                $.ajax({
                    url : '{{url("stock_market/decimal-places")}}/'+stock_market_serial_no,
                    type: 'GET',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    $('input[name="stock_price_decimal_places"]').val(data);

                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
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
            //if($('input[name="stock_price_decimal_places"]').val() == '')
            if('{{$action_type}}' == 'edit' && $('input[name="stock_price_decimal_places"]').val() == '')
            {
                $('select[name="stock_market_serial_no"]').trigger("change");
            }


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


            $('select[name="urgent_calculation_cause_serial_no"]').on('change', function(){
                var el = $(this).closest(".modal-content");
                var url = $(this).data('url');
                var selectedItem = $(this).val();
                url = url + '/' + selectedItem;

                App.blockUI({
                    message: 'Loading',
                    target: el,
                    overlayColor: 'black',
                    cenrerY: true,
                    boxed: true
                });
                $.ajax({
                    type: "GET",
                    cache: false,
                    url: url,
                    dataType: "json",
                    success: function(res) {
                        App.unblockUI(el);
                        $('input[name="from_value"]').val(res.from_value);
                        $('input[name="to_value"]').val(res.to_value);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        App.unblockUI(el);
                        var msg = 'Error on reloading the content. Please check your connection and try again.';
                        if (error == "toastr" && toastr) {
                            toastr.error(msg);
                        } else if (error == "notific8" && $.notific8) {
                            $.notific8('zindex', 11500);
                            $.notific8(msg, {
                                theme: 'ruby',
                                life: 3000
                            });
                        } else {
                            alert(msg);
                        }
                    }
                });
            });
            $('select[name="urgent_calculation_cause_serial_no"]').trigger('change');
        });
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
                //if one of down range sides is empty, then the other is empty too (case of new stock)
                if($('input[name="d'+(i)+'_from"]').val()=='')
                {
                    $('input[name="d'+(i)+'_from"]').val('-');
                    $('input[name="d'+(i)+'_to"]').val('-');
                }
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


        
        @include('Admin::forms.generic', ['type' => 'portlet'])



<div id="copy-stock-levels" class="modal fade container" data-backdrop="copy-stock-levels" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Copy Stock Levels and Ranges</h4>
            </div>
            <!-- BEGIN FORM-->
            <form id="copy_form" action="{{url('stock/levels')}}" method="GET" class="form-horizontal modal-form">
            <div class="modal-body"> 

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Stock</label>
                                    <div class="col-md-9">
                                        <select class="form-control select2" name="stock_serial_no" placeholder="Select a Stock">
                                            <option></option>
                                            @foreach(\App\StockMarket::all() as $market)
                                            <optgroup label="{{$market->label_en}}">
                                            @foreach($market->stocks as $stock)
                                            <option value="{{$stock->serial_no}}">{{$stock->stock_name_en}}</option>
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
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button type="submit" class="btn green"><i class="fa fa-spinner fa-spin hidden"> </i> Submit</button>
            </div>
            </form>
            <!-- END FORM-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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