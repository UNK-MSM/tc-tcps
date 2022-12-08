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

{{trans('labels.empty')}}
    <script type="text/javascript">
        var json = $.parseJSON('<?php echo $prediction_text_result_out == ''? '{}': $prediction_text_result_out; ?>');
        $(document).ready(function() {

            $('select[name="stock_serial_no"]').on('change', function(){

                var target = $(this).closest('.portlet-body');
                $('#closing-date').val('');
                
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
                    $('#closing-date').val(data.next_date);
                    $('#closing-date').next().html('{{trans("labels.last_closing_date")}}: <span class="font-green-sharp">'+data.date+'</span>');
                    $('#closing-price').next().html('{{trans("labels.last_closing_price")}}: <span class="font-green-sharp">'+data.price+'</span>');

                    $('#closing-date').datepicker('remove');
                    $('#closing-date').datepicker({
                        rtl: App.isRTL(),
                        orientation: "left",
                        autoclose: true,
                        format: 'yyyy-mm-dd',
                        endDate: data.next_date
                    });
                    $('#closing-date').datepicker('update', data.next_date);

                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
                    }else if(jqXHR.status === 400){
                        $('#closing-date').removeAttr('disabled');
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
            if($('#closing-date').val()=='')
            {
                $('select[name="stock_serial_no"]').trigger("change");
            }



                var rowSpanBook = {
                    "1": {
                        "top_positive": {
                            "i": -1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle_positive": {
                            "i": -1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "bottom_positive": {
                            "i": 0,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle": {
                            "i": 1
                        },
                        "bottom_negative": {
                            "i": 2,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle_negative": {
                            "i": -1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "top_negative": {
                            "i": -1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        }
                    },
                    "2": {
                        "top_positive": {
                            "i": -1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle_positive": {
                            "i": 0,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "bottom_positive": {
                            "i": 1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle": {
                            "i": 2
                        },
                        "bottom_negative": {
                            "i": 3,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle_negative": {
                            "i": 4,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "top_negative": {
                            "i": -1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        }
                    },
                    "3": {
                        "top_positive": {
                            "i": 0,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle_positive": {
                            "i": 1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "bottom_positive": {
                            "i": 2,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle": {
                            "i": 3
                        },
                        "bottom_negative": {
                            "i": 4,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle_negative": {
                            "i": 5,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "top_negative": {
                            "i": 6,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        }
                    },
                    "4": {
                        "top_positive": {
                            "i": 0,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle_positive": {
                            "i": 1,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "bottom_positive": {
                            "i": 2,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle": {
                            "i": 4
                        },
                        "bottom_negative": {
                            "i": 5,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle_negative": {
                            "i": 7,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "top_negative": {
                            "i": 8,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        }
                    },
                    "5": {
                        "top_positive": {
                            "i": 0,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        },
                        "middle_positive": {
                            "i": 1,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "bottom_positive": {
                            "i": 3,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle": {
                            "i": 5
                        },
                        "bottom_negative": {
                            "i": 6,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle_negative": {
                            "i": 8,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "top_negative": {
                            "i": 10,
                            "prob_rowspan": 0,
                            "price_rowspan": 0
                        }
                    },
                    "6": {
                        "top_positive": {
                            "i": 0,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle_positive": {
                            "i": 2,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "bottom_positive": {
                            "i": 4,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle": {
                            "i": 6
                        },
                        "bottom_negative": {
                            "i": 7,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle_negative": {
                            "i": 9,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "top_negative": {
                            "i": 11,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        }
                    },
                    "7": {
                        "top_positive": {
                            "i": 0,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle_positive": {
                            "i": 2,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "bottom_positive": {
                            "i": 4,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "middle": {
                            "i": 7
                        },
                        "bottom_negative": {
                            "i": 8,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "middle_negative": {
                            "i": 11,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "top_negative": {
                            "i": 13,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        }
                    },
                    "8": {
                        "top_positive": {
                            "i": 0,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        },
                        "middle_positive": {
                            "i": 3,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "bottom_positive": {
                            "i": 5,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "middle": {
                            "i": 8
                        },
                        "bottom_negative": {
                            "i": 9,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "middle_negative": {
                            "i": 12,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "top_negative": {
                            "i": 15,
                            "prob_rowspan": 2,
                            "price_rowspan": 2
                        }
                    },
                    "9": {
                        "top_positive": {
                            "i": 0,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "middle_positive": {
                            "i": 3,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "bottom_positive": {
                            "i": 6,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "middle": {
                            "i": 9
                        },
                        "bottom_negative": {
                            "i": 10,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "middle_negative": {
                            "i": 13,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        },
                        "top_negative": {
                            "i": 16,
                            "prob_rowspan": 3,
                            "price_rowspan": 3
                        }
                    }
                }
                if (json.stocks !== undefined) {
                    $("#actual-price").html(json['close_selling_price']);
                    $("#calcualtion-details").append('<tr><th></th><th></th><th>' + json.presets.last_irp_1 + '</th><th></th><th>' + json.presets.last_irp_2 + '</th><th></th><th>{{trans("labels.average")}}</th><th>{{trans("labels.average_2_irp")}}</th><th>{{trans("labels.average_3_irp")}}</th><th>{{trans("labels.price_after_calculation")}}</th></tr>');
                                        
                    $("#closing-date").on("change", function() {
                        $("#closing-price").val("");
                    });
                                        
                    var details = "";
                    var i = 0;

                    $.each(json.stocks, function(key, value) {
                        details += "<tr>";
                        details += "<td>" + value.addition + "</td>";
                        details += "<td>" + Math.round(value.price_prob * 100) + "%</td>";
                        details += "<td>" + json.presets.last_irp_1 + value.addition + "</td>";
                        details += "<td>" + value.probability_rate_1.toFixed(3) + "</td>";
                        details += "<td>" + json.presets.last_irp_2 + value.addition + "</td>";
                        details += "<td>" + value.probability_rate_2.toFixed(3) + "</td>";
                        details += "<td>" + (100 * (value.average_rate_1 + value.average_rate_2) / 2).toFixed(2) + "%</td>";
                        details += "<td>" + value.average_rate_1.toFixed(3) + "</td>";
                        details += "<td>" + value.average_rate_2.toFixed(3) + "</td>";
                        details += "<td>" + value.price.toFixed(3) + "</td>";

                        if (i === rowSpanBook[json.presets.up_levels_count].top_positive.i) {
                            details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].top_positive.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.positive_top_price_prob * 100) + "%</td>";
                            details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].top_positive.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.positive_top_price.toFixed({{$decimal_places}}) + "</td>";    
                            details += '<td rowspan="' + json.presets.up_levels_count + '">' + json.prediction_totals.predicted_up_price.toFixed({{$decimal_places}}) + "</td>";                          
                        } else if (i === rowSpanBook[json.presets.up_levels_count].middle_positive.i) {
                            details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].middle_positive.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.positive_middle_price_prob * 100) + "%</td>";
                            details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].middle_positive.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.positive_middle_price.toFixed({{$decimal_places}}) + "</td>";                      
                        } else if (i === rowSpanBook[json.presets.up_levels_count].bottom_positive.i) {
                            details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].bottom_positive.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.positive_bottom_price_prob * 100) + "%</td>";
                            details += '<td rowspan="' + rowSpanBook[json.presets.up_levels_count].bottom_positive.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.positive_bottom_price.toFixed({{$decimal_places}}) + "</td>";              
                        } else if (i === rowSpanBook[json.presets.up_levels_count].middle.i) {
                            details += '<td style="background-color: yellow; font-weight: bold;">' + Math.round(value.price_prob * 100) + "%</td>";
                            details += '<td style="background-color: green; color: white; font-weight: bold;">' + value.price.toFixed({{$decimal_places}}) + "</td>";
                            details += '<td>' + value.price.toFixed({{$decimal_places}}) + "</td>";                               
                        } else if (i === rowSpanBook[json.presets.down_levels_count].bottom_negative.i) {
                            details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].bottom_negative.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.negative_bottom_price_prob * 100) + "%</td>";
                            details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].bottom_negative.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.negative_bottom_price.toFixed({{$decimal_places}}) + "</td>";
                            details += '<td rowspan="' + json.presets.down_levels_count + '">' + json.prediction_totals.predicted_down_price.toFixed({{$decimal_places}}) + "</td>";      
                        } else if (i === rowSpanBook[json.presets.down_levels_count].middle_negative.i) {
                            details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].middle_negative.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.negative_middle_price_prob * 100) + "%</td>";
                            details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].middle_negative.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.negative_middle_price.toFixed({{$decimal_places}}) + "</td>";

                        }else if (i === rowSpanBook[json.presets.down_levels_count].top_negative.i) {
                            details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].top_negative.prob_rowspan + '" style="background-color: yellow; font-weight: bold;">' + Math.round(json.prediction_totals.negative_top_price_prob * 100) + "%</td>";
                            details += '<td rowspan="' + rowSpanBook[json.presets.down_levels_count].top_negative.price_rowspan + '" style="background-color: green; color: white; font-weight: bold;">' + json.prediction_totals.negative_top_price.toFixed({{$decimal_places}}) + "</td>";   
                        }


                        details += "</tr>";

                        i++;
                    });

                    $("#calcualtion-details").append(details);
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
                <th>{{trans('labels.predicted_rising_price')}}</th>
                <th>{{trans('labels.predicted_falling_price')}}</th>
                <th>{{trans('labels.predicted_stable_price')}}</th>
                <th>{{trans('labels.actual_closing_price')}}</th>
            </tr>
            <tr>
                <td style="<?php echo ($predicted_up_price_rate_out > $predicted_stable_price_rate_out && $predicted_up_price_rate_out > $predicted_down_price_rate_out) ? "background-color: green; color: white;" : "" ?>"><?php echo number_format($predicted_up_price_out, $decimal_places); ?><span>(<?php echo round($predicted_up_price_rate_out * 100, 0); ?>%)</span></td>
                <td style="<?php echo ($predicted_down_price_rate_out > $predicted_stable_price_rate_out && $predicted_down_price_rate_out > $predicted_up_price_rate_out) ? "background-color: #e43a45; color: white;" : "" ?>"><?php echo number_format($predicted_down_price_out, $decimal_places); ?><span>(<?php echo round($predicted_down_price_rate_out * 100, 0); ?>%)</span></td>
                <td style="<?php echo ($predicted_stable_price_rate_out > $predicted_up_price_rate_out && $predicted_stable_price_rate_out > $predicted_down_price_rate_out) ? "background-color: #afc6d0; color: white;" : "" ?>"><?php echo number_format($predicted_stable_price_out, $decimal_places); ?><span>(<?php echo round($predicted_stable_price_rate_out * 100, 0); ?>%)</span></td>
                <td id="actual-price" style="background-color: yellow; font-weight: bold;">-</td>
            </tr>
        </table>
        <table id="calcualtion-details">
            <tr>
                <th colspan="6">% {{trans('labels.irp_probabilities')}}</th>
            </tr>
            <tr>
                <th colspan="2">{{trans('labels.irp_probabilities_2')}}</th>
                <th colspan="2">{{trans('labels.last_irp')}}</th>
                <th colspan="2">{{trans('labels.last_2_irps')}}</th>
            </tr>
        </table>
        <?php } ?>


        <form method="POST" style="text-align: center;">
        {{ csrf_field() }}
            <div class="row">
                <div class="col-md-offset-1 col-md-3">
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
                    <label for="select2-single-input-sm" class="control-label">{{trans('labels.prediction_date')}}</label>
                    <input type="text" id="closing-date" name="date" class="form-control input-sm date-picker" placeholder="{{trans('labels.date_in_format')}}" value="{{$date}}"  />
                    <p class="help-block"></p>
                </div>
                <div class="col-md-2">
                    <label for="bootstrap-input-sm" class="control-label">{{trans('labels.closing_price')}}</label>
                    <input type="text" id="closing-price" name="closing-price" class="form-control input-sm" placeholder="{{trans('labels.stock_prior_closing_price')}}" value="<?php echo ($closing_price == "" || $closing_price == 0) ? round($predicted_stable_price_out, $decimal_places): $closing_price; ?>"/>
                    <p class="help-block"></p>
                </div>
                <div class="col-md-3">
                    <label class="control-label"></label>
                    <div class="input-group" style="margin-top: 6px;">
                        <input type="submit" value="{{trans('actions.calculate')}}" style="margin-right:5px;" class="btn blue-madison input-sm" />
                        <input type="submit" name="two-pass" value="{{trans('actions.calculate_twice')}}" class="btn green-meadow input-sm" />
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
