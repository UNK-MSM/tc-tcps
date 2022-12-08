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
                    $('#closing-date').next().html("{{trans('labels.last_closing_date')}}: <span class='font-green-sharp'>"+data.date+"</span>");
                    $('#closing-price').next().html('{{trans("labels.last_closing_price")}}: <span class="font-green-sharp">'+data.price+'</span>');

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

            drawPredictionsTable($.parseJSON('<?php echo $pass_1_prediction_text_result_out == ''? '{}': $pass_1_prediction_text_result_out; ?>'), 1);
            drawPredictionsTable($.parseJSON('<?php echo $pass_2_prediction_text_result_out == ''? '{}': $pass_2_prediction_text_result_out; ?>'), 2);
        });
                        
        function drawPredictionsTable(json, pass) {
            if (json.stocks !== undefined) {
                                    $("#actual-price").html(json['close_selling_price']);
                $("#pass_" + pass + "_calcualtion-details").append('<tr><th></th><th></th><th>' + json.presets.last_irp_1 + '</th><th></th><th>' + json.presets.last_irp_2 + '</th><th></th><th>{{trans("labels.average")}}</th><th>{{trans("labels.average_2_irp")}}</th><th>{{trans("labels.average_3_irp")}}</th><th>{{trans("labels.price_after_calculation")}}</th></tr>');
                                    
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
                    details += "<td>" + value.probability_rate_1.toFixed({{$decimal_places}}) + "</td>";
                    details += "<td>" + json.presets.last_irp_2 + value.addition + "</td>";
                    details += "<td>" + value.probability_rate_2.toFixed({{$decimal_places}}) + "</td>";
                    details += "<td>" + (100 * (value.average_rate_1 + value.average_rate_2) / 2).toFixed(2) + "%</td>";
                    details += "<td>" + value.average_rate_1.toFixed({{$decimal_places}}) + "</td>";
                    details += "<td>" + value.average_rate_2.toFixed({{$decimal_places}}) + "</td>";
                    details += "<td>" + value.price.toFixed({{$decimal_places}}) + "</td>";

                    if (i === 0) {
                                                    details += '<td style="background-color: yellow; font-weight: bold;">' + Math.round(value.price_prob * 100) + "%</td>";
                        details += '<td style="background-color: green; color: white; font-weight: bold;">' + value.price.toFixed({{$decimal_places}}) + "</td>"; 
                        details += '<td rowspan="5">' + json.prediction_totals.predicted_up_price.toFixed({{$decimal_places}}) + "</td>";                         
                    } else if (i === 1) {
                        details += '<td style="background-color: yellow; font-weight: bold;" rowspan="2">' + Math.round(json.prediction_totals.u4_u3_price_prob * 100) + "%</td>";
                        details += '<td style="background-color: green; color: white; font-weight: bold;" rowspan="2">' + json.prediction_totals.u4_u3_price.toFixed({{$decimal_places}}) + "</td>";                      
                    } else if (i === 3) {
                        details += '<td style="background-color: yellow; font-weight: bold;" rowspan="2">' + Math.round(json.prediction_totals.u2_u1_price_prob * 100) + "%</td>";
                        details += '<td style="background-color: green; color: white; font-weight: bold;" rowspan="2">' + json.prediction_totals.u2_u1_price.toFixed({{$decimal_places}}) + "</td>";              
                    } else if (i === 5) {
                        details += '<td style="background-color: yellow; font-weight: bold;">' + Math.round(value.price_prob * 100) + "%</td>";
                        details += '<td style="background-color: green; color: white; font-weight: bold;">' + value.price.toFixed({{$decimal_places}}) + "</td>";
                        details += '<td>' + value.price.toFixed({{$decimal_places}}) + "</td>";                               
                    } else if (i === 6) {
                        details += '<td style="background-color: yellow; font-weight: bold;" rowspan="2">' + Math.round(json.prediction_totals.d2_d1_price_prob * 100) + "%</td>";
                        details += '<td style="background-color: green; color: white; font-weight: bold;" rowspan="2">' + json.prediction_totals.d2_d1_price.toFixed({{$decimal_places}}) + "</td>";
                        details += '<td rowspan="5">' + json.prediction_totals.predicted_down_price.toFixed({{$decimal_places}}) + "</td>";       
                    } else if (i === 8) {
                        details += '<td style="background-color: yellow; font-weight: bold;" rowspan="2">' + Math.round(json.prediction_totals.d4_d3_price_prob * 100) + "%</td>";
                        details += '<td style="background-color: green; color: white; font-weight: bold;" rowspan="2">' + json.prediction_totals.d4_d3_price.toFixed({{$decimal_places}}) + "</td>";              
                    } else if (i === 10) {
                        details += '<td style="background-color: yellow; font-weight: bold;">' + Math.round(value.price_prob * 100) + "%</td>";
                        details += '<td style="background-color: green; color: white; font-weight: bold;">' + value.price.toFixed({{$decimal_places}}) + "</td>";         
                    }


                    details += "</tr>";

                    i++;
                });

                $("#pass_" + pass + "_calcualtion-details").append(details);
            }
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
                <td style="<?php echo ($pass_2_predicted_up_price_rate_out > $pass_2_predicted_stable_price_rate_out && $pass_2_predicted_up_price_rate_out > $pass_2_predicted_down_price_rate_out) ? "background-color: green; color: white;" : "" ?>"><?php echo round($pass_2_predicted_up_price_out, $decimal_places); ?><span>(<?php echo round($pass_2_predicted_up_price_rate_out * 100, 0); ?>%)</span></td>
                <td style="<?php echo ($pass_2_predicted_down_price_rate_out > $pass_2_predicted_stable_price_rate_out && $pass_2_predicted_down_price_rate_out > $pass_2_predicted_up_price_rate_out) ? "background-color: #e43a45; color: white;" : "" ?>"><?php echo round($pass_2_predicted_down_price_out, $decimal_places); ?><span>(<?php echo round($pass_2_predicted_down_price_rate_out * 100, 0); ?>%)</span></td>
                <td style="<?php echo ($pass_2_predicted_stable_price_rate_out > $pass_2_predicted_up_price_rate_out && $pass_2_predicted_stable_price_rate_out > $pass_2_predicted_down_price_rate_out) ? "background-color: #cedde3; color: white;" : "" ?>"><?php echo round($pass_2_predicted_stable_price_out, $decimal_places); ?><span>(<?php echo round($pass_2_predicted_stable_price_rate_out * 100, 0); ?>%)</span></td>
                <td id="actual-price" style="background-color: yellow; font-weight: bold;">-</td>
            </tr>
        </table>
        <table id="pass_1_calcualtion-details">
            <tr>
                <th colspan="6">{{trans('labels.pass_1')}}</th>
            </tr>
            <tr>
                <th colspan="6">% {{trans('labels.irp_probabilities')}}</th>
            </tr>
            <tr>
                <th colspan="2">{{trans('labels.irp_probabilities_2')}}</th>
                <th colspan="2">{{trans('labels.last_irp')}}</th>
                <th colspan="2">{{trans('labels.last_2_irps')}}</th>
            </tr>
        </table>
        <table id="pass_2_calcualtion-details">
                    <tr>
                <th colspan="6">{{trans('labels.pass_2')}}</th>
            </tr>
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
                <div class="col-md-offset-2 col-md-3">
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
                    <input type="text" id="closing-price" name="closing-price" class="form-control input-sm" placeholder="{{trans('labels.stock_prior_closing_price')}}" value="<?php echo ($closing_price == "" || $closing_price == 0) ? round($pass_2_predicted_stable_price_out, $decimal_places): $closing_price; ?>"/>
                    <p class="help-block"></p>
                </div>
                <div class="col-md-3">
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
