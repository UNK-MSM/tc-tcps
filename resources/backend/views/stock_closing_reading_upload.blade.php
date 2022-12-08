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
    <link href="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />

@endsection
@section('page_level_styles')

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
    <script src="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
@endsection
@section('page_level_scripts')
    <script src="{{asset('assets/pages/scripts/components-select2.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/pages/scripts/form-validation.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('select[name="stock_serial_no"]').on('change', function(){

                var target = $(this).closest('.portlet-body');
                $('input[name="last_closing_date"]').val('');
                $('input[name="last_closing_date"]').attr('disabled', 'disabled');
                
                App.blockUI({
                    message: 'Loading',
                    target: target,
                    overlayColor: 'none',
                    cenrerY: true,
                    boxed: true
                });
                var stock_serial_no = $(this).val();
                var url = window.location.href;
                url = url.replace('/import', '/lcd/'+stock_serial_no);
                $.ajax({
                    url : url,
                    type: 'GET',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    $('input[name="last_closing_date"]').val(data.date);

                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
                    }else if(jqXHR.status === 400){
                        $('input[name="last_closing_date"]').removeAttr('disabled');
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

            $('button[type="submit"]').on('click', function(){
                var el = $('.portlet');
                App.blockUI({
                    target: el,
                    boxed: true,
                    message: 'Processing...'
                });
                setInterval(function(){
                    var el = $('.portlet');
                    var stock_serial_no = $('select[name="stock_serial_no"]').val();
                    var url = window.location.href;
                    url = url.replace('/import', '/lcd/'+stock_serial_no);
                    $.ajax({
                        url : url,
                        type: 'GET',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }}).done(function(data){
                        //App.unblockUI(el);
                        $('.blockElement > .loading-message > span').html('  Reading: '+ data.date);
                    }).fail(function(jqXHR, textStatus, errorThrown){
                    }).always(function(data) {

                    });}, 1000
                );
            });
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
                <span>Import Closing Readings</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-red-sunglo bold uppercase">Import Closing Readings</span>
                        <span class="caption-helper">columns must have the headings 'closing_date' for the stock closing date and 'closing_reading' for the closing reading of that day</span>
                    </div>
                    <div class="tools">
                        <a href="/tcps/public/sample.xlsx" data-original-title="Download Sample File" title="Download Sample File"><i class="fa fa-download font-grey-silver"></i></a>
                    </div>
                </div>
                <div class="portlet-body form">
                    @if(!empty($errors))
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {{$error}}
                    </div>
                    @endforeach
                    @endif
                    <!-- BEGIN FORM-->
                    <form id="upload_form" action="{{url('stock_closing_reading/import')}}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">Stock</label>
                                <div class="col-md-3">
                                    <select type="select" class="form-control select2" name="stock_serial_no" data-placeholder="Choose a Selection" tabindex="1">
                                        <option></option>
                                        @foreach($stocks as $stock)
                                        <option value="{{ $stock->serial_no }}">{{ $stock->stock_name_en }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Last Closing Date</label>
                                <div class="col-md-3">
                                    <input type="text" name="last_closing_date" class="form-control input-sm date-picker" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Excel File</label>
                                <div class="col-md-3">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="input-group input-large">
                                            <div class="form-control uneditable-input input-fixed input-sm" data-trigger="fileinput">
                                                <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                <span class="fileinput-filename"> </span>
                                            </div>
                                            <span class="input-group-addon btn default btn-file">
                                                <span class="fileinput-new"> Select file </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" name="file"> </span>
                                            <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                    <span class="help-block" style="font-size: 11px;"> {{trans('messages.max_file_size')}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions right">
                            <a href="{{back()->getTargetUrl()}}" class="btn default">Cancel</a>
                            <button type="submit" class="btn green"><i class="fa fa-spinner fa-spin hidden"> </i> Submit</button>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>



        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT BODY -->
<!-- END CONTENT BODY -->
@endsection