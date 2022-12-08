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
    <style type="text/css">
        table th, table td {
            border: 1px dotted gray;
            padding: 5px;
            background-color: white;
            text-align: center;
        }         
        table th {
            font-weight: bold;
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
        <script src="{{asset('assets/global/plugins/highstock/js/highstock.js')}}" type="text/javascript"></script>
@endsection
@section('page_level_scripts')
    <script src="{{asset('assets/pages/scripts/components-select2.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/pages/scripts/form-validation.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            var target = $('#readings_comparison');
            App.blockUI({
                message: 'Loading',
                target: target,
                overlayColor: 'none',
                cenrerY: true,
                boxed: true,
                textOnly: true
            });
            var seriesOptions = [],
                seriesCounter = 0,
                // create the chart when all data is loaded
                createChart = function () {

                    $('#readings_comparison').highcharts('StockChart', {
                        chart : {
                            style: {
                                fontFamily: 'Open Sans'
                            }
                        },

                        rangeSelector: {
                            selected: 4
                        },

                        yAxis: {
                            labels: {
                                formatter: function () {
                                    return (this.value > 0 ? ' + ' : '') + this.value + '%';
                                }
                            },
                            plotLines: [{
                                value: 0,
                                width: 2,
                                color: 'silver'
                            }]
                        },

                        plotOptions: {
                            series: {
                                compare: 'percent'
                            }
                        },

                        tooltip: {
                            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
                            valueDecimals: 2
                        },

                        series: seriesOptions
                    });
                };

                $.getJSON('{{route("stock.readings", [$stock->serial_no, $type, $date])}}?callback=?',    function (data) {

                    var actual = [];
                    var predicted_calculation = [];
                    $.each(data['ACTUAL'], function(key, value){
                        var close_selling_price = value.close_selling_price;//parseFloat(value.close_selling_price);
                        actual.push([value.date_closed_milliseconds, close_selling_price]);

                        var predicted_general_selling_closing_price = value.predicted_general_selling_closing_price;
                        predicted_calculation.push([value.date_closed_milliseconds, predicted_general_selling_closing_price]);
                    });
                    seriesOptions[0] = {
                        name: 'Actual',
                        data: actual
                    };
                    if(data['type'] == 'calibration')
                    {
                        var predicted_general = [];
                        $.each(data['PREDICTED'], function(key, value){
                            var predicted_general_selling_closing_price = value.predicted_general_selling_closing_price;
                            predicted_general.push([value.date_closed_milliseconds, predicted_general_selling_closing_price]);
                        });
                        seriesOptions[1] = {
                            name: 'Predicted[Calibration]',
                            data: predicted_general
                        };
                    }else
                    {
                        seriesOptions[1] = {
                            name: 'Predicted[Calculated]',
                            data: predicted_calculation
                        };
                    }

                    // As we're loading the data asynchronously, we don't know what order it will arrive. So
                    // we keep a counter and create the chart when all the data is loaded.
                        createChart();
                        App.unblockUI(target);
                        /*$('input[name="min"]').change(function(){
                            var url = '{{route("stock.results", [$stock->serial_no, $type, "DATE"])}}';
                            var value = $(this).val();
                            window.location = url.replace('DATE', value);
                        });*/
                });


            $('body').on('confirmed.bs.confirmation', '[data-toggle="confirmation"].apply_recalculate', function () {
                var currentElement = $(this);
                $(currentElement).attr('disabled', 'disabled');
                var currentClass = $(currentElement).find('i').attr('class');
                $(currentElement).find('i').removeClass(currentClass).addClass('fa fa-spin fa-spinner');

                var url = $(currentElement).data('url');

                $.ajax({
                    url : url,
                    type: 'POST',
                    cache: false,
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    //toastr['success']("Stock readings was recalculated successfully", "Success");
                    setTimeout(function() {
                        calculationProgress(data.calculation_progress_url, currentElement, currentClass);
                    }, 5000);
                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
                    }else
                    {
                        var data = jqXHR.responseJSON;
                        toastr['error'](data, "Error")
                    }
                    $(currentElement).removeAttr('disabled');
                    $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass(currentClass);
                }).always(function(data) {
                });
            });
        });

        function calculationProgress(calculation_progress_url, currentElement, currentClass)
        {

            $.ajax({
                url : calculation_progress_url,
                type: 'GET',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }}).done(function(data){
                if(data.recalculations_job_c == 'FINISHED')
                {
                    $(currentElement).removeAttr('disabled');
                    $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass(currentClass).addClass('font-green-jungle');
                    $(currentElement).attr('title', 'Stock readings was recalculated successfully');
                    toastr['success']("Stock readings was recalculated successfully", "Success");

                }

                if(data.recalculations_job_c != 'FINISHED')
                {
                    setTimeout(function() {
                        calculationProgress(calculation_progress_url, currentElement, currentClass);
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
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-paper-plane font-yellow-casablanca"></i>
                        <span class="caption-subject bold font-yellow-casablanca uppercase"> {{ $title }} </span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="portlet-body">

                    <div class="form-body form">
                        <div class="row">
                            <div class="col-md-12">
                                <table>
                                    <thead>
                                        <tr>
                                            <th colspan="2" style="background-color: #c2d69b; color: #4d5d2c;">نتيجة الاحتمال الاكبر</th>
                                            <th colspan="2" style="background-color: #b2a1c7; color: #403251;">نتيجة احتمال الارتفاع العام</th>
                                            <th colspan="2" style="background-color: #d99594; color: #602826;">نتيجة احتمال الانخفاض العام</th>
                                            <th colspan="2" style="background-color: #a6a6a6; color: #000;">نتيجة احتمال الاتجاه العام</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #c2d69b; color: #4d5d2c;">صحة الاتجاه</th>
                                            <th style="background-color: #c2d69b; color: #4d5d2c;">نسبة الخطأ في القيمة</th>
                                            <th style="background-color: #b2a1c7; color: #403251;">صحة الاتجاه</th>
                                            <th style="background-color: #b2a1c7; color: #403251;">نسبة الخطأ في القيمة</th>
                                            <th style="background-color: #d99594; color: #602826;">صحة الاتجاه</th>
                                            <th style="background-color: #d99594; color: #602826;">نسبة الخطأ في القيمة</th>
                                            <th style="background-color: #a6a6a6; color: #000;">صحة الاتجاه</th>
                                            <th style="background-color: #a6a6a6; color: #000;">نسبة الخطأ في القيمة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        @if($total_records_count != 0)
                                            <td style="background-color: #d6e3bc; color: #4d5d2c;">{{sprintf("%.2f%%", ($summary['greatest_probability_validity_total']/$total_records_count) * 100)}}</td>
                                            <td style="background-color: #d6e3bc; color: #4d5d2c;">{{sprintf("%.2f%%", ($summary['greatest_probability_error_rate_total']/$total_records_count) * 100)}}</td>
                                            <td style="background-color: #ccc0d9; color: #403251;">{{sprintf("%.2f%%", ($summary['predicted_rising_selling_closing_price_validity_total']/$total_records_count) * 100)}}
                                            </td>
                                            <td style="background-color: #ccc0d9; color: #403251;">{{sprintf("%.2f%%", ($summary['predicted_rising_selling_closing_price_error_rate_total']/$total_records_count) * 100)}}</td>
                                            <td style="background-color: #e5b8b7; color: #602826;">{{sprintf("%.2f%%", ($summary['predicted_falling_selling_closing_price_validity_total']/$total_records_count) * 100)}}</td>
                                            <td style="background-color: #e5b8b7; color: #602826;">{{sprintf("%.2f%%", ($summary['predicted_falling_selling_closing_price_error_rate_total']/$total_records_count) * 100)}}</td>
                                            <td style="background-color: #bfbfbf; color: #000;">{{sprintf("%.2f%%", ($summary['predicted_general_selling_closing_price_validity_total']/$total_records_count) * 100)}}</td>
                                            <td style="background-color: #bfbfbf; color: #000;">{{sprintf("%.2f%%", ($summary['predicted_general_selling_closing_price_error_rate_total']/$total_records_count) * 100)}}</td>
                                        @else
                                            <td colspan="8" style="color: #000;">No Records Found</td>
                                        @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--/span-->
                        </div>

                        <div class="row">
                        <div class="col-md-12">
                            <div id="readings_comparison" style="height:500px;"></div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                        </div>
                        <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="form-actions right">
                            <a href="{{back()->getTargetUrl()}}" class="btn default">Back</a>
                            <a href="{{route('stock.export_results', [$stock->serial_no, $type, $file_id])}}" class="btn green">Export</a>
                            @if($type == 'calibration')
                            <a href="#" class="btn blue apply_recalculate" title="Are you sure? This will copy current settings to stock and recalculate its readings"  data-toggle="confirmation" data-popout="true" data-singleton="true" data-url="{{route('stock.apply_calibration', [$stock->serial_no, $date])}}"><i class="fa fa-check"></i> Save & Recalculate</a>
                            @endif
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