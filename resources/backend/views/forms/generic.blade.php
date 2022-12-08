
    @if($type == 'portlet')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-red-sunglo bold uppercase">{{ $title }}</span>
            <span class="caption-helper">{{ $description }}</span>
        </div>
        <div class="tools">
            <!--<a href="" class="edit" data-original-title="" title=""><i class="fa fa-edit font-grey-silver"></i></a>
            <a href="" class="reload" data-original-title="" title=""> </a>-->
        </div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form id="{{$form_id or 'generic_form'}}" action="{{ $storeRouteUrl }}" class="form-horizontal" method="POST" enctype="{{$enctype}}">
        <input type="hidden" name="_method" value="{{ $method or 'POST'}}" />
        {{ csrf_field() }}
            <div class="form-body">
    @elseif($type == 'modal')
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">{{ $title }}</h4>
    </div>
    <!-- BEGIN FORM-->
    <form id="{{$form_id or 'generic_form'}}" action="{{ $storeRouteUrl }}" class="form-horizontal" method="POST" enctype="{{$enctype}}">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="{{ $method or 'POST'}}" />
        <div class="modal-body">
    @endif

        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button> You have some form errors. Please check below.
        </div>
        <div class="alert alert-success display-hide">
            <button class="close" data-close="alert"></button> Your form validation is successful!
        </div>
        @if(!empty($errors))
        @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            {{$error}}
        </div>
        @endforeach
        @endif

            @foreach($rows as $row)
                <div class="row">
                @foreach($row as $field_name => $control)

                <?php
                    $value = isset($control['field']['value'])? $control['field']['value']:'';
                    $oldValue = old($field_name);
                    if(isset($oldValue))
                    {
                        $value = $oldValue;
                    }
                    $maxLengthValidation = "";
                    $maxLangthClass = "";
                    $validationRules = "";
                    $classes = isset($control['field']['class'])? $control['field']['class'] : "";
                    $inputMask = isset($control['field']['mask'])? (' data-inputmask-mask='.$control['field']['mask'].' '):"";
                    if(isset($control['field']['validation']))
                    {
                        $validationRules = $control['field']['validation'];
                        if(strpos($control['field']['validation'], 'max') !== FALSE)
                        {
                            $rules = explode('|', $control['field']['validation']);
                            foreach($rules as $rule)
                            {
                                if(substr($rule, 0, 4) === 'max:')
                                {
                                    $maxLengthValidation = "maxlength=".str_replace('max:', '', $rule);
                                    $maxLangthClass = " maxlength ";
                                    break;
                                }
                            }
                        }
                    }
                    $unUpdatable = '';
                    if(isset($control['field']['updatable']) && $control['field']['updatable'] === false)
                    {
                        $unUpdatable = ' data-updatable=false ';
                        if(isset($action_type) && $action_type === 'edit')
                        {
                            $unUpdatable .= ' disabled ';
                        }
                    }
                    $classes = $classes." ".$maxLangthClass;

                    $control_data = '';
                    if(isset($control['field']['control_data']))
                    {
                        foreach($control['field']['control_data'] as $dataKey => $dataValue)
                        {
                            $control_data .= ' data-'.$dataKey.'="'.$dataValue.'"';
                        }
                    }
                    $control_data .= $unUpdatable.$maxLengthValidation.$inputMask;
                ?>
                    <div class="{{ $control['width'] }}">
                        <div class="form-group">
                            <label class="control-label col-md-3">
                            @if(!in_array($control['field']['type'], ['label', 'hidden']))
                                {{ $control['field']['label'] or trans('labels.'.$field_name) }}
                            
                                @if(isset($control['field']['validation']) && strpos($control['field']['validation'], 'required') !== FALSE)
                                <span class="required" aria-required="true"> * </span>
                                @endif
                            @endif
                            </label>


                            <div class="col-md-9">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                @if(isset($control['field']['input-group']) && $control['field']['type'] != 'time')
                                <div class="input-group select2-bootstrap-prepend">
                                    <span class="input-group-addon">
                                        <i class="fa fa-{{ $control['field']['input-group'] }} font-grey-cascade"></i>
                                    </span>
                                @endif

                                @if($control['field']['type'] === 'text')
                                    <input type="text" name="{{$field_name}}" {!!$control_data!!} class="form-control input-sm {{$classes}}" data-validation="{{$validationRules}}" value="{{$value}}" />
                                @elseif($control['field']['type'] === 'label')
                                    <h1 class="{{$classes}}">{{ $control['field']['value'] or 'asd' }}</h1>
                                @elseif($control['field']['type'] === 'hidden')
                                    <input type="hidden" name="{{$field_name}}" {!!$control_data!!} class="form-control input-sm {{$classes}}" data-validation="{{$validationRules}}" value="{{$value}}" />
                                @elseif($control['field']['type'] === 'password')
                                    <input type="password" name="{{$field_name}}" {!!$control_data!!} class="form-control input-sm {{$classes}}" data-validation="{{$validationRules}}" value="{{$value}}" />
                                @elseif($control['field']['type'] === 'weekline')
                                    <span data-name="{{$field_name}}" class="form-control input-sm {{$classes}}" value="{{$value}}" ></span>
                                    <input type="hidden" name="{{$field_name}}" value="{{$value}}" />
                                @elseif($control['field']['type'] === 'integer')
                                    <input type="text" name="{{$field_name}}" {!!$control_data!!} class="form-control input-sm {{$classes}}" data-validation="{{$validationRules}}" value="{{$value}}" />
                                @elseif($control['field']['type'] === 'float')
                                    <input type="text" name="{{$field_name}}" {!!$control_data!!} class="form-control input-sm {{$classes}}" data-validation="{{$validationRules}}" value="{{$value}}" />
                                @elseif($control['field']['type'] === 'boolean')
                                <?php
                                    $switchOnText = trans('labels.YES');
                                    $switchOffText = trans('labels.NO');
                                    if(isset($control['field']['switch']))
                                    {
                                        $switchOnText = trans('labels.'.$control['field']['switch'][0]);
                                        $switchOffText = trans('labels.'.$control['field']['switch'][1]);
                                    }
                                ?>
                                    <input type="checkbox" name="{{$field_name}}" class="make-switch {{$classes}}" {{$value==1? 'checked':''}} data-on-text="{{$switchOnText}}" data-off-text="{{$switchOffText}}" data-validation="{{$validationRules}}" {!!$control_data!!} value="1">
                                @elseif($control['field']['type'] === 'url')
                                    <input type="text" name="{{$field_name}}" {!!$control_data!!} class="form-control input-sm {{$classes}}" value="{{$value}}" data-validation="{{$validationRules}}" />
                                @elseif($control['field']['type'] === 'date')
                                    <input type="text" name="{{$field_name}}" {!!$control_data!!} class="form-control input-sm date-picker {{$classes}}" data-validation="{{$validationRules}}" value="{{$value}}" />
                                @elseif($control['field']['type'] === 'time')
                                    <input type="text" name="{{$field_name}}" {!!$control_data!!} class="form-control input-sm timepicker timepicker-no-seconds {{$classes}}" data-validation="{{$validationRules}}" value="{{$value}}" />
                                @elseif($control['field']['type'] === 'datetime')

                                    <div class="input-group date form_meridian_datetime">
                                        <span class="input-group-addon">
                                            <i class="fa fa-clock-o date-set"></i>
                                        </span>
                                        <input type="text" name="{{$field_name}}" class="form-control input-sm {{$classes}}" {!!$control_data!!} data-validation="{{$validationRules}}" value="{{$value}}" />
                                        <span class="input-group-btn">
                                            <button class="btn default date-reset" type="button">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                @elseif($control['field']['type'] === 'radio_buttons')
                                    <div class="switch-field">
                                        @foreach($control['field']['list'] as $listKey => $listValue)
                                        <input type="radio" class="radio_list" id="switch_{{$field_name}}_{{$listKey}}" name="{{$field_name}}" value="{{$listKey}}"  {{$value===$listKey? 'checked':''}}/>
                                        <label for="switch_{{$field_name}}_{{$listKey}}">{{$listValue}}</label>
                                        @endforeach
                                    </div>
                                @elseif($control['field']['type'] === 'list')
                                    <select type="select" class="form-control input-sm select2 {{$classes}}" name="{{$field_name}}" {!!$control_data!!} data-placeholder="{{trans('messages.choose_a_selection')}}" >
                                        <option></option>
                                        @foreach($control['field']['list'] as $listKey => $listValue)
                                        <option value="{{ $listKey }}" {{$value==$listKey? 'selected':''}}>{{ $listValue }}</option>
                                        @endforeach
                                    </select> 
                                @elseif($control['field']['type'] === 'group_list')
                                <?php
                                    if(!is_array($value))
                                    {
                                        $value = [$value];
                                    }
                                ?>
                                    <select type="select" class="form-control select2 {{$classes}}" name="{{$field_name}}{!!(isset($control['field']['multiselect']) && $control['field']['multiselect'])? '[]" multiple':''!!}  {!!$control_data!!} data-placeholder="{{trans('messages.choose_a_selection')}}">
                                        @foreach($control['field']['list'] as $listKey => $listValue)
                                        <optgroup label="{{$listKey}}">
                                            @foreach($listValue as $listValueKey => $listValueValue)
                                            <option value="{{ $listValueKey }}" {{in_array($listValueKey, $value)? 'selected':''}}>{{ $listValueValue }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select> 
                                @elseif($control['field']['type'] === 'multiselect')
                                    <?php
                                    if($value === '')
                                    {
                                        $value = array();
                                    }
                                    ?>
                                    <select type="select" class="form-control input-sm select2 {{$classes}}" name="{{$field_name}}[]" multiple {!!$control_data!!} data-placeholder="{{trans('messages.choose_a_selection')}}" >
                                        @foreach($control['field']['list'] as $listKey => $listValue)
                                        <option value="{{ $listKey }}" {{in_array($listKey, $value)? 'selected':''}}>{{ $listValue }}</option>
                                        @endforeach
                                    </select> 
                                @elseif($control['field']['type'] === 'tabular')
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover tabular">
                                            <thead>
                                                <tr>
                                                @foreach($control['field']['table'] as $key => $value)
                                                    <th scope="col"> {{trans('labels.'.$key)}} </th>
                                                @endforeach
                                                    <th scope="col"> Delete </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @if(!empty($control['field']['value']))
                                            @foreach($control['field']['value'] as $index => $record)
                                                <tr>
                                                @foreach($control['field']['table'] as $key => $value)
                                                    <td scope="col">
                                                    @if(in_array($value['type'], ['boolean']))

                                                        @if($record[$key] == 0)
                                                            No
                                                        @else
                                                            Yes
                                                        @endif
                                                    @else
                                                        {{$record[$key]}}
                                                    @endif
                                                    </td>
                                                @endforeach
                                                    <td>
                                                        <a class="font-red-sunglo destroy" data-toggle="confirmation" data-popout="true" data-singleton="true" data-url="{{route(str_singular($field_name).'.destroy', $record['serial_no'])}}" title="Delete?"><i class="icon-trash"></i></a> | 
                                                        <a class="font-blue-sharp edit" href="#add-new-record" data-url="{{route(str_singular($field_name).'.edit', $record['serial_no'])}}" data-update-url="{{route(str_singular($field_name).'.update', $record['serial_no'])}}"><i class="icon-pencil"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif

                                            </tbody>
                                            <tfoot>
                                                <th colspan="{{count($control['field']['table']) + 1}}">
                                                    <a  href="#add-new-record" data-target="#add-new-record" data-toggle="modal" data-original-title="Add New Item" title="" class="btn btn-sm green add-new-record" style="position: absolute; left: -62px; top: 38px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </th>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @section('sform')
                                    @include('Admin::forms.generic', array_merge(generateFormFields($control['field']['table'], null, 1), ['type' => 'modal', 'form_id' => $field_name]))
                                    @endsection
                                @push('scripts')
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            $('#add-new-record form button[type="submit"]').before('<button name="new" class="btn green">Submit</button>');
                                            $('table.tabular').on('click', '.remove', function(){
                                                $(this).closest('tr').remove();
                                            });
                                            //To be handled !
                                            $('table tbody tr').each(function(){
                                                var id = $(this).find('td:eq(0)').html().trim();
                                                $(this).find('td:eq(0)').html($('#add-new-record form select').find('option[value="'+id+'"]').text());
                                            });
                                            //End
                                            var relatedTarget ;
                                            $('#add-new-record').on('show.bs.modal', function(e) {
                                                relatedTarget = e.relatedTarget;

                                                $('#add-new-record button[type="submit"]').removeClass('hidden');
                                                $('#add-new-record button[name="new"]').addClass('hidden');
                                                if($(relatedTarget).hasClass('add-new-record'))
                                                {
                                                    $('#add-new-record button[type="submit"]').addClass('hidden');
                                                    $('#add-new-record button[name="new"]').removeClass('hidden');
                                                }
                                            });
                                            $('#add-new-record form').submit(function(ev){
                                                ev.preventDefault();
                                                var postData = $(this).serialize();
                                                var url = $(this).attr('action');
                                                var postMethod = $(this).attr('method');

                                                $.each($(this).find('input[type=checkbox]')
                                                    .filter(function(idx){
                                                        return $(this).prop('checked') === false
                                                    }),
                                                    function(idx, el){
                                                        // attach matched element names to the formData with a chosen value.
                                                        var emptyVal = "0";
                                                        postData += '&' + $(el).attr('name') + '=' + emptyVal;
                                                    }
                                                );
                                                var target = $(this);
                                                App.blockUI({
                                                    message: 'Saving',
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
                                                    toastr['success'](data.message, "Success");

                                                    var row = $('a[data-url="'+url+'"]').closest('tr');
                                                    $(target).find('input[type="text"], input[type="checkbox"], select').each(function(key, value){
                                                        if($(value).is('select'))
                                                        {

                                                            $(row).find('td:eq('+key+')').html($(value).find('option:selected').text());
                                                        }else if($(value).is('input[type="checkbox"]'))
                                                        {
                                                            var label = $(value).data('off-text');
                                                            if($(value).prop('checked') === true)
                                                            {
                                                                label = $(value).data('on-text');
                                                            }
                                                            $(row).find('td:eq('+key+')').html(label);
                                                        }else
                                                        {
                                                            $(row).find('td:eq('+key+')').html($(value).val());
                                                        }
                                                    });

                                                    $('#add-new-record').modal('toggle');
                                                    setTimeout(function() {
                                                        var redirectUrl = $(this).find('.form-actions a').attr('href');

                                                    }, 1000);
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
                                            $('#add-new-record .modal-footer > button[name="new"]').click(function(ev){
                                                ev.preventDefault();
                                                ev.stopImmediatePropagation();

                                                var dataArray = [];
                                                var labelArray = [];
                                                var rowsCount = $('#add-new-record').prev().find('table tbody tr').length;
                                                var rowHtml = '<tr>';
                                                @foreach($control['field']['table'] as $key => $value)
                                                @if($value['type']==='editor')
                                                    dataArray['{{$key}}'] = $(this).closest('.modal').find('textarea[name="{{$key}}"]').val();
                                                    labelArray['{{$key}}'] = dataArray['{{$key}}'];
                                                @elseif(in_array($value['type'], ['list']))
                                                    dataArray['{{$key}}'] = $(this).closest('.modal').find('select[name="{{$key}}"]').val();
                                                    labelArray['{{$key}}'] = $(this).closest('.modal').find('select[name="{{$key}}"] option:selected').text();
                                                @elseif(in_array($value['type'], ['boolean']))

                                                    labelArray['{{$key}}'] = $(this).closest('.modal').find('input[name="{{$key}}"]').data('off-text');
                                                    dataArray['{{$key}}'] = 0;
                                                    if($(this).closest('.modal').find('input[name="{{$key}}"]').prop('checked') === true)
                                                    {
                                                        dataArray['{{$key}}'] = $(this).closest('.modal').find('input[name="{{$key}}"]').val();
                                                        labelArray['{{$key}}'] = $(this).closest('.modal').find('input[name="{{$key}}"]').data('on-text');
                                                    }
                                                @else
                                                    dataArray['{{$key}}'] = $(this).closest('.modal').find('input[name="{{$key}}"]').val();
                                                    labelArray['{{$key}}'] = dataArray['{{$key}}'];
                                                @endif
                                                rowHtml += '<td>' + labelArray['{{$key}}'] + '</td>';
                                                rowHtml += '<input type="hidden" name="{{$field_name}}['+rowsCount+'][{{$key}}]" value="'+dataArray['{{$key}}']+'" />';
                                                @endforeach
                                                rowHtml += '<td><a class="font-red-sunglo remove"><i class="icon-trash"></i></a></td>';
                                                rowHtml += '</tr>';
                                                $('#add-new-record').prev().find('table tbody').append(rowHtml);
                                                
                                                $('#add-new-record').modal('toggle');
                                                return false;
                                            });
                                        });
                                    </script>
                                @endpush
                                @elseif($control['field']['type'] === 'urgent-calculation-tabular')
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover tabular">
                                            <thead>
                                                <tr>
                                                <tr>
                                                    <th scope="col" style="width: 35%"> Urgent Cause </th>
                                                    <th scope="col" style="width: 25%"> السبب </th>
                                                    <th scope="col" style="width: 10%"> {{trans('labels.positive_effect')}} </th>
                                                    <th scope="col" style="width: 65px;"> {{trans('labels.from_value')}} </th>
                                                    <th scope="col" style="width: 65px;"> {{trans('labels.to_value')}} </th>
                                                    <th scope="col" style="width: 10%"> {{trans('labels.is_active')}} </th>
                                                </tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @if(!empty($control['field']['value']))
                                            @foreach($control['field']['value'] as $index => $record)
                                                <tr data-serial-no="{{$record['serial_no']}}" data-action="{{route('stock.stock_urgent_calculation.update', [$record['stock_serial_no'], $record['serial_no']])}}">
                                                    <td scope="col">
                                                        {{$record['urgent_cause']['label_en']}}
                                                    </td>
                                                    <td scope="col">
                                                        {{$record['urgent_cause']['label_ar']}}
                                                    </td>
                                                    <td scope="col">
                                <div class="input-icon right">
                                    <i class="fa fa-spinner fa-spin hidden"></i>
                                    <input type="checkbox" name="positive_effect" class="make-switch" data-on-text="{{trans('labels.YES')}}" data-off-text="{{trans('labels.NO')}}" value="1" {{$record['positive_effect']===1? 'checked':''}}>
                                </div>
                                                    </td>
                                                    <td scope="col">
                                <div class="input-icon right">
                                    <i class="fa fa-spinner fa-spin hidden"></i>
                                    <input type="text" name="from_value" class="form-control input-sm" value="{{$record['from_value']}}" style="padding-right: 5px;padding-left: 5px;" />
                                </div>
                                                    </td>
                                                    <td scope="col">
                                <div class="input-icon right">
                                    <i class="fa fa-spinner fa-spin hidden"></i>
                                    <input type="text" name="to_value" class="form-control input-sm" value="{{$record['to_value']}}" style="padding-right: 5px;padding-left: 5px;" />
                                </div>
                                                    </td>
                                                    <td scope="col">
                                <div class="input-icon right">
                                    <i class="fa fa-spinner fa-spin hidden"></i>
                                    <input type="checkbox" name="is_active" class="make-switch" data-on-text="{{trans('labels.YES')}}" data-off-text="{{trans('labels.NO')}}" value="1" {{$record['is_active']===1? 'checked':''}}>
                                </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                @push('scripts')
                                    <script type="text/javascript">
                                        $(document).ready(function(){

                                            $('input[name="from_value"], input[name="to_value"], input[name="positive_effect"], input[name="is_active"]').on('change switchChange.bootstrapSwitch', function(){
                                                var postData = {};
                                                var url = $(this).closest('tr').data('action');
                                                var postMethod = 'PUT';

                                                postData['from_value'] = $(this).closest('tr').find('input[name="from_value"]').val();
                                                postData['to_value'] = $(this).closest('tr').find('input[name="to_value"]').val();
                                                postData['positive_effect'] = 0;
                                                if($(this).closest('tr').find('input[name="positive_effect"]').is(':checked'))
                                                {
                                                    postData['positive_effect'] = 1;
                                                }
                                                postData['is_active'] = 0;
                                                if($(this).closest('tr').find('input[name="is_active"]').is(':checked'))
                                                {
                                                    postData['is_active'] = 1;
                                                }
                                                var target = $(this).closest('tr');
                                                App.blockUI({
                                                    message: 'Saving',
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
                                                    toastr['success'](data.message, "Success");
                                                }).fail(function(jqXHR, textStatus, errorThrown){
                                                    if(jqXHR.status === 500)
                                                    {
                                                        toastr['error']("Internal Error code: 500", "Error")
                                                    }else
                                                    {
                                                        var data = jqXHR.responseJSON;
                                                        var messageBody = '';
                                                        messageBody += '<ul>';
                                                        $.each(data, function(key, value){
                                                            $.each(value, function(key2, value2){
                                                                messageBody += '<li>' + value2 + '</li>';
                                                            });
                                                        });
                                                        messageBody += '</ul>';
                                                        toastr['error'](messageBody, "Error")
                                                    }
                                                }).always(function(data) {
                                                    App.unblockUI(target);
                                                });
                                            });
                                        });
                                    </script>
                                @endpush
                                @elseif($control['field']['type'] === 'membership-plan-instances-tabular')
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover tabular">
                                            <thead>
                                                <tr>
                                                <tr>
                                                    <th scope="col" style="width: 30%"> {{trans('labels.membership_plan')}} </th>
                                                    <th scope="col" style="width: 8%"> {{trans('labels.demo')}} </th>
                                                    <th scope="col" style="width: 8%;"> {{trans('labels.plan_price')}} </th>
                                                    <th scope="col" style="width: 8%;"> {{trans('labels.period_in_days')}} </th>
                                                    <th scope="col" style="width: 10%"> {{trans('labels.start_date')}} </th>
                                                    <th scope="col" style="width: 16%"> {{trans('labels.expire_date')}} </th>
                                                    <th scope="col" style="width: 20%"> {{trans('labels.status')}} </th>
                                                </tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @if(!empty($control['field']['value']))
                                            @foreach($control['field']['value'] as $index => $record)
                                                <tr data-serial-no="{{$record['serial_no']}}" data-action="{{route('membership.membership_plan_instance.update', [$record['membership_serial_no'], $record['serial_no']])}}">
                                                    <td scope="col">
                                                        {{$record['membership_plan'][\App\MembershipPlanInstance::getLabel()]}}
                                                    </td>
                                                    <td scope="col">
                                                        {{$record['demo']? trans('labels.YES'):trans('labels.NO')}}
                                                    </td>
                                                    <td scope="col">
                                                        {{$record['plan_price']}}
                                                    </td>
                                                    <td scope="col">
                                                        {{$record['period_in_days']}}
                                                    </td>
                                                    <td scope="col">
                                                        {{$record['start_date']}}
                                                    </td>
                                                    <td scope="col">
                                <div class="input-icon right">
                                    <i class="fa fa-spinner fa-spin hidden"></i>
                                    <input type="text" name="expire_date" class="form-control date-picker input-sm" value="{{$record['expire_date']}}" style="padding-right: 5px;padding-left: 5px;" />
                                </div>
                                                    </td>
                                                    <td scope="col">
                                <div class="input-icon right">
                                    <i class="fa fa-spinner fa-spin hidden"></i>

                                    <select type="select" class="form-control input-sm select2" name="instance_status" data-placeholder="{{trans('messages.choose_a_selection')}}" >
                                        <option></option>
                                        @foreach(\App\MembershipPlanInstanceState::all() as $listValue)
                                        <option value="{{ $listValue->serial_no }}" {{$record['status']==$listValue->serial_no? 'selected':''}}>{{ $listValue[\App\MembershipPlanInstanceState::getLabel()] }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                @push('scripts')
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            var locked = false;
                                            $('table select[name="instance_status"], table input[name="expire_date"]').on('change', function(){
                                                if(locked)
                                                {
                                                    return;
                                                }
                                                locked = true;
                                                var postData = {};
                                                var url = $(this).closest('tr').data('action');
                                                var postMethod = 'PUT';

                                                postData['status'] = $(this).closest('tr').find('select[name="instance_status"]').val();
                                                postData['expire_date'] = $(this).closest('tr').find('input[name="expire_date"]').val();
                                                var target = $(this).closest('tr');
                                                App.blockUI({
                                                    message: 'Saving',
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
                                                    toastr['success'](data.message, "Success");
                                                }).fail(function(jqXHR, textStatus, errorThrown){
                                                    if(jqXHR.status === 500)
                                                    {
                                                        toastr['error']("Internal Error code: 500", "Error")
                                                    }else
                                                    {
                                                        var data = jqXHR.responseJSON;
                                                        var messageBody = '';
                                                        messageBody += '<ul>';
                                                        $.each(data, function(key, value){
                                                            $.each(value, function(key2, value2){
                                                                messageBody += '<li>' + value2 + '</li>';
                                                            });
                                                        });
                                                        messageBody += '</ul>';
                                                        toastr['error'](messageBody, "Error")
                                                    }
                                                }).always(function(data) {
                                                    App.unblockUI(target);
                                                    locked = false;
                                                });
                                            });
                                        });
                                    </script>
                                @endpush
                                @elseif($control['field']['type'] === 'editor')
                                    <textarea class="{{$type == 'portlet'? 'wysihtml5':''}} form-control input-sm {{$classes}}" rows="6" name="{{$field_name}}" {!!$control_data!!} data-error-container="#editor1_error" data-validation="{{$validationRules}}">{{$value}}</textarea>
                                    <div id="editor1_error"> </div>
                                @elseif($control['field']['type'] === 'textarea')
                                    <textarea class="form-control input-sm {{$classes}}" rows="6" name="{{$field_name}}" {!!$control_data!!} data-validation="{{$validationRules}}">{{$value}}</textarea>
                                @elseif($control['field']['type'] === 'image')
                                    @if(!empty($value))
                                    <div style="margin-bottom: 5px;">{!! $value !!}</div>
                                    @endif
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="input-group input-large">
                                            <div class="form-control input-sm uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                <span class="fileinput-filename"> </span>
                                            </div>
                                            <span class="input-group-addon btn default btn-file">
                                                <span class="fileinput-new"> Select file </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" name="{{$field_name}}"> </span>
                                            <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                @elseif($control['field']['type'] === 'range_slider')
                                    <input name="{{$field_name}}" type="text" class="range_slider {{$classes}}" {!!$control_data!!} value="{{$value}}" />
                                @elseif(in_array($control['field']['type'], ['range_input', 'range_input_mx']))
                                    <?php
                                        $range = explode(';', $value);
                                        if(sizeof($range) === 2)
                                        {
                                            $from = $range[0];
                                            $to = $range[1];
                                        }else
                                        {
                                            $from = $value;
                                            $to = '';
                                        }
                                        $fromFieldName = $field_name.'_from';
                                        $toFieldName = $field_name.'_to';
                                        $rowClass = 'range_input_row';
                                        if($control['field']['type'] == 'range_input_mx')
                                        {
                                            $fromFieldName = $field_name.'_min';
                                            $toFieldName = $field_name.'_max';
                                            $rowClass = '';
                                        }
                                    ?>
                                    <div class="row {{$rowClass}}">
                                        <div class="col-md-6">
                                                    <input name="{{$fromFieldName}}" type="text" class="form-control input-sm range_input {{$classes}}" {!!$control_data!!} value="{{$from}}" placeholder="{{trans('labels.from')}}" />
                                        </div>
                                        <div class="col-md-6">
                                                    <input name="{{$toFieldName}}" type="text" class="form-control input-sm range_input {{$classes}}" {!!$control_data!!} value="{{$to}}" placeholder="{{trans('labels.to')}}" />
                                        </div>
                                    </div>
                                @endif

                                @if(isset($control['field']['input-group']) && $control['field']['type'] != 'time')
                                </div>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                @endforeach
                </div>
                <!--/row-->
            @endforeach
    @if($type == 'portlet')
                <div class="form-actions right">
                    <a href="{{back()->getTargetUrl()}}" class="btn default">{{trans('actions.cancel')}}</a>
                    <button type="submit" class="btn green"><i class="fa fa-spinner fa-spin hidden"> </i> {{trans('actions.submit')}}</button>
                </div>
            </div>
        </form>
        <!-- END FORM-->
    </div>
</div>
    @elseif($type == 'modal')
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-outline dark">{{trans('actions.close')}}</button>
            <button type="submit" class="btn green"><i class="fa fa-spinner fa-spin hidden"> </i> {{trans('actions.submit')}}</button>
        </div>
    </form>
    <!-- END FORM-->
    @endif


                                    <div id="add-new-record" class="modal fade container" data-backdrop="add-new-record" data-keyboard="false">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                @yield('sform')
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>