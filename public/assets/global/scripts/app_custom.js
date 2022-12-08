var GenereicFormValidation = function () {
    
    var handleValidation = function(form) {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#generic_form');
        if(form != undefined)
        {
            form1 = form;
        }
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        var validationRules = {};
        $(form1).find('input, textarea, select').each(function(){
            var element = $(this);
            if($(this).data('validation') !== undefined)
            {
                var rulesArray = $(this).data('validation').split('|');
                var rules = { };
                $.each(rulesArray, function(key, value){
                    if(value === 'required')
                    {
                        rules['required'] = true;
                    }else if(value.startsWith('max'))
                    {
                        rules['maxlength'] = value.replace('max:', '');
                    }else if(value.startsWith('min'))
                    {
                        rules['minlength'] = value.replace('min:', '');
                    }else if(value === 'url')
                    {
                        rules['url'] = true;
                    }else if(value === 'email')
                    {
                        rules['email'] = true;
                    }else if(value === 'integer')
                    {
                        rules['digits'] = true;
                    }else if(value.startsWith('digits'))
                    {
                        rules['digits'] = true;
                        rules['maxlength'] = value.replace('digits:', '');
                        rules['minlength'] = value.replace('digits:', '');
                    }else if(value === 'numeric')
                    {
                        rules['number'] = true;
                    }else if(value.startsWith('same'))
                    {
                        var similarFieldSelector = '#generic_form input[name="'+value.replace('same:', '')+'"]';
                        rules['equalTo'] = similarFieldSelector;

                        var $this = $(element);
                        $this.on('change', function(){
                            $(similarFieldSelector).val($this.val());
                        });
                        $(similarFieldSelector).on('change', function(){
                            $this.val($(similarFieldSelector).val());
                        });
                    }
                });
                validationRules[$(this).attr('name')] = rules;
            }
        });
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: validationRules,

            invalidHandler: function(event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
            },

            errorPlacement: function(error, element) {
                /*if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }*/

                var i = $(element).closest(".input-icon").children("i");
                i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", error.text()).tooltip({
                    container: "body"
                })
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
                //$(e).closest(".form-group").removeClass("has-success").addClass("has-error")
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function(label, r) {
                //label.closest('.form-group').removeClass('has-error'); // set success class to the control group
                var i = $(r).closest(".input-icon").children("i");
                $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
            },

            submitHandler: function(form) {
                error1.hide();
                handleFormSubmit(form);
            }
        });
    }
    var handleFormSubmit = function(form){

            var form_enctype = $(form).attr('enctype');
            if(form_enctype === 'multipart/form-data')
            {
                form.submit();
            }else
            {

                var postData = $(form).serialize();
                var url = $(form).attr('action');
                var postMethod = $(form).attr('method');

                $.each($(form).find('input[type=checkbox]')
                    .filter(function(idx){
                        return $(this).prop('checked') === false
                    }),
                    function(idx, el){
                        // attach matched element names to the formData with a chosen value.
                        var emptyVal = "0";
                        postData += '&' + $(el).attr('name') + '=' + emptyVal;
                    }
                );

                lockForm(form);
                $.ajax({
                    url : url,
                    data: postData,
                    type: postMethod,
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    toastr['success'](data.message, "Success")
                    unlockForm(form, true);
                    setTimeout(function() {
                        var redirectUrl = $(form).find('.form-actions a').attr('href');
                        if(data.redirect_url !== undefined)
                        {
                            window.location = data.redirect_url;
                        }else
                        {
                            window.location = redirectUrl;
                        }
                    }, 1000);
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
                    unlockForm(form);
                });
            }
    }

    var handleBootstrapMaxlength = function() {
    
        if (jQuery().maxlength) {
            $('.maxlength').maxlength({
                limitReachedClass: "label label-danger",
                threshold: 10
            });
        }
    }

    var handlePopOverConfirmation = function () {
        
        $('body').on('confirmed.bs.confirmation', '[data-toggle="confirmation"].destroy', function () {
            var currentElement = $(this);
            $(currentElement).attr('disabled', 'disabled');
            var currentClass = $(currentElement).find('i').attr('class');
            $(currentElement).find('i').removeClass(currentClass).addClass('fa fa-spin fa-spinner');

            var url = $(currentElement).data('url');

            $.ajax({
                url : url,
                type: 'DELETE',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }}).done(function(data){
                $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass('fa fa-hourglass-end');
                toastr['success'](data, "Success")
                setTimeout(function() {
                    $(currentElement).closest('tr').hide();
                }, 1000);
            }).fail(function(jqXHR, textStatus, errorThrown){
                if(jqXHR.status === 500)
                {
                    toastr['error']("Internal Error code: 500", "Error")
                }else
                {
                    var data = jqXHR.responseJSON;
                    toastr['error'](data, "Error")
                }
                $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass(currentClass);
            }).always(function(data) {
                $(currentElement).removeAttr('disabled');
            });
        });
    }

    var handleEditButtonClick = function () {
        
        $('body').on('click', 'a.edit', function (ev) {
            ev.preventDefault();
            var currentElement = $(this);
            $(currentElement).attr('disabled', 'disabled');
            var currentClass = $(currentElement).find('i').attr('class');
            $(currentElement).find('i').removeClass(currentClass).addClass('fa fa-spin fa-spinner');

            var modalReference = $(this).attr('href');
            if(modalReference == undefined)
            {
                modalReference = '#add-new';
            }
            var url = $(currentElement).data('url');
            var updateUrl = $(currentElement).data('update-url');

            $.ajax({
                url : url,
                type: 'GET',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }}).done(function(data){
                //populate form
                var form = $(modalReference).find('form');
                populate(form, data);
                $(modalReference).modal('show');
                $(form).attr('action', updateUrl);
                $(form).find('input[name="_method"]').val('PUT');
            }).fail(function(jqXHR, textStatus, errorThrown){
                if(jqXHR.status === 500)
                {
                    toastr['error']("Internal Error code: 500", "Error")
                }else
                {
                    var data = jqXHR.responseJSON;
                    toastr['error'](data, "Error")
                }
            }).always(function(data) {
                $(currentElement).removeAttr('disabled');
                $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass(currentClass);
            });
        });
    }

    var handleViewButtonClick = function () {
        $('#view-item form button[type="submit"]').remove();
        
        $('body').on('click', 'a.view', function (ev) {
            ev.preventDefault();
            var currentElement = $(this);
            $(currentElement).attr('disabled', 'disabled');
            var currentClass = $(currentElement).find('i').attr('class');
            $(currentElement).find('i').removeClass(currentClass).addClass('fa fa-spin fa-spinner');

            var url = $(currentElement).data('url');

            $.ajax({
                url : url,
                type: 'GET',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }}).done(function(data){
                //populate form
                var properties = {};
                properties.elements = [];
                var pro = {};
                pro.disabled = 'disabled';
                $.each(data, function(key, value){
                    properties.elements[key] = pro;
                });
                var form = $('#view-item').find('form');
                populate(form, data, properties);
                $('#view-item').modal('show');
                $(form).find('input[name="_method"]').val('PUT');
            }).fail(function(jqXHR, textStatus, errorThrown){
                if(jqXHR.status === 500)
                {
                    toastr['error']("Internal Error code: 500", "Error")
                }else
                {
                    var data = jqXHR.responseJSON;
                    toastr['error'](data, "Error")
                }
            }).always(function(data) {
                $(currentElement).removeAttr('disabled');
                $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass(currentClass);
            });
        });
    }

    var handleInputMasks = function () {

        if (jQuery().inputmask) {
            $(".mask").inputmask();
        }
    }

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
    }

    var handleTimePickers = function () {

        if (jQuery().timepicker) {
            $('.timepicker-no-seconds').timepicker({
                autoclose: true,
                minuteStep: 5
            });

            // handle input group button click
            $('.timepicker').parent('.input-group').on('click', '.input-group-btn', function(e){
                e.preventDefault();
                $(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
            });
        }
    }

    var handleDatetimePicker = function () {

        if (!jQuery().datetimepicker) {
            return;
        }

        $(".form_meridian_datetime").datetimepicker({
            isRTL: App.isRTL(),
            format: "dd MM yyyy - HH:ii P",
            showMeridian: true,
            autoclose: true,
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            todayBtn: true
        });

        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }

    var handleWeekline = function() {
        if (!jQuery().weekLine) {
            return;
        }

         // Return selected days as labels
         $(".weekline").weekLine({
                theme: 'white',
                onChange: function () {
                    var selection = $(this).weekLine('getSelected');
                    $(this).next().val(selection);
                }
         });
         $.each($(".weekline"), function(k, v){
            if($(v).attr('value') !== undefined && $(v).attr('value') !== '')
            {
                $(v).weekLine("setSelected", $(v).attr('value')); 
            }
         });
    }
    
    var handleRecords = function () {

        var grid;
        try {
            grid = new Datatable()
        }
        catch(err) {
            return;
        }
        //var grid = new Datatable();
        var datatableElement = $("#ajax_datatable");
        var columns = [];
        $.each($(datatableElement).find('th'), function(key, value){
            var obj = JSON.parse('{"data": "'+$(value).data('name')+'", "orderable": '+$(value).data('orderable')+'}');
            columns.push(obj)
        });
        var orders = [];
        var ordersString = $(datatableElement).data('orders');
        if(ordersString != undefined)
        {
            ordersString = ordersString.replace(/'/g, '"');
            var obj = JSON.parse(ordersString);
            orders.push(obj)
            orders = orders[0];
        }
        grid.init({
            src: datatableElement,
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
                $('body').confirmation(
                {
                    selector: '[data-toggle="confirmation"]',
                    container: 'body',
                    btnOkClass: 'btn btn-sm btn-success',
                    btnCancelClass: 'btn btn-sm btn-danger'
                });
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load

            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                
                "retrieve": true,
                //"bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                "lengthMenu": [
                    [10, 20, 50, 100, 150, -1],
                    [10, 20, 50, 100, 150, "All"] // change per page values here
                ],
                "pageLength": 10, // default record count per page

                "ajax": {
                    "url": $(datatableElement).data('url'), // ajax source
                    "type": "GET",
                    "dataType": "json"
                },
                "rowCallback": function( row, data ) {
                    if(data.urgent_calculation_serial_no !== undefined && data.urgent_calculation_serial_no !== '-')
                    {
                        $(row).addClass('highlighted');
                    }
                },
                "order": orders,// set first column as a default sort by asc
                "columns": columns
            }
        });

        $('button.select').on( 'click', function (e) {
            e.preventDefault();
     
            // Get the column API object
            var column = grid.getDataTable().column( 0 );
     
            // Toggle the visibility
            column.visible( ! column.visible() );
            App.initUniform();
            $('.checker').parent().css('text-align', 'center');
        } );


        // handle group actionsubmit button click
        $('a.bulk-delete').on('click', function (e) {
            e.preventDefault();
            if (grid.getSelectedRowsCount() > 0) {
                var currentElement = $(this);
                $(currentElement).attr('disabled', 'disabled');
                var currentClass = $(currentElement).find('i').attr('class');
                $(currentElement).find('i').removeClass(currentClass).addClass('fa fa-spin fa-spinner');

                var row_ids = grid.getSelectedRows();
                var url = $(currentElement).data('url')+'/'+row_ids;

                $.ajax({
                    url : url,
                    type: 'DELETE',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }}).done(function(data){
                    $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass('fa fa-hourglass-end');
                    toastr['success'](data, "Success")
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }).fail(function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 500)
                    {
                        toastr['error']("Internal Error code: 500", "Error")
                    }else
                    {
                        var data = jqXHR.responseJSON;
                        toastr['error'](data, "Error")
                    }
                    $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass(currentClass);
                }).always(function(data) {
                    $(currentElement).removeAttr('disabled');
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });

        //grid.setAjaxParam("customActionType", "group_action");
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
        grid.getDataTable().column( 0 ).visible( false );
        
        $('.tool-actions > li > a.tool-action').on('click', function() {
            var action = $(this).attr('data-action');
            datatableElement.DataTable().button(action).trigger();
        });
    }


    function lockForm(form)
    {
        var el = form;
        App.blockUI({
            target: el,
            animate: true,
            overlayColor: 'none'
        });
        /*
        $('.alert').fadeOut( "slow", function() {
          $('.alert').remove();
        });

        $(form).find('input, textarea, select, button, .btn').each(function(){
            $(this).attr('disabled', "disabled");
        });
        $(form).find('.fa-spinner').removeClass('hidden');
        */
    }
    function unlockForm(form, reloadMode)
    {
        App.unblockUI(form);
        /*
        if(reloadMode === undefined)
        {
          reloadMode = false;
        }

        if(!reloadMode)
        {
          $(form).find('input:not(.disabled), textarea, select, button, .btn').each(function(){
              $(this).removeAttr('disabled');
          });
        }
        $(form).find('.fa-spinner').addClass('hidden');
        */
    }

    function populate(frm, data, properties) {
        $.each(data, function(key, value){
            var $ctrl = $('[name^='+key+']', frm);
            switch($ctrl.attr("type"))
            {
                case "file":
                    $ctrl.closest('div.fileinput').replaceWith(value);
                    break;
                case "text":
                case "hidden":
                    $ctrl.val(value);
                    if($ctrl.prev().hasClass('weekline'))
                    {
                        $ctrl.prev().weekLine("setSelected", $ctrl.val()); 
                    }
                    if($ctrl.data('updatable') === false)
                    {
                        $ctrl.attr('disabled', 'disabled');
                        $ctrl.addClass('disabled');
                    }
                    break;
                case "radio": case "checkbox":
                    $ctrl.each(function(){
                        $(this).bootstrapSwitch('state', false);
                        if($(this).attr('value') == value) { $(this).bootstrapSwitch('state', true); }
                    });
                    break;
                case "select":
                    $ctrl.find('option').each(function(){
                        if($(this).attr('value') == value) {
                            $(this).attr("selected",'selected');
                        }
                    });
                    if($ctrl.attr('multiple') !== undefined)
                    {
                        var valuesList = [];
                        $.each(value, function(k, v){
                            valuesList[valuesList.length] = v.serial_no;
                        });
                        $ctrl.select2("val", valuesList);
                    }else
                    {

                        $ctrl.select2("val", value);
                    }
                    break;
                default:
                    $ctrl.val(value);
            }
            if(properties !== undefined && properties.elements[key] !== undefined)
            {
                $.each(properties.elements[key], function(key2, value2){
                    $ctrl.attr(key2, value2);
                    if($ctrl.is('input[type="checkbox"]') && key2=='disabled')
                    {
                        $($ctrl).bootstrapSwitch('disabled', true);
                    }
                });
            }
        });
    }


    function convertErrorMessage(message)
    {
        var messageBody = '';
        if(message.constructor === Array || message.constructor === {}.constructor)
        {
            messageBody += '<ul>';
            $.each(message, function(key, value){
            $.each(value, function(key2, value2){
            messageBody += '<li>' + value2 + '</li>';
            });
            });
            messageBody += '</ul>';
        }else
        {
            messageBody += '<p>' + message + '</p>';
        }
        return messageBody;
    }
    var handleSelectRemoveButton = function() {
        
        //$('select:not(.searchable)').change(function(){
        $('select').change(function(){
            var currentVal = $(this).val();

            if(currentVal == undefined || currentVal == '')
            {
                $(this).closest('.input-group').find('.input-group-btn').addClass('hidden');
            }else
            {
                $(this).closest('.input-group').find('.input-group-btn').removeClass('hidden');
            }
        });
        //$('select:not(.searchable)').closest('.input-icon').addClass('input-group');
        $('select:enabled').closest('.input-icon').addClass('input-group');
        var $resetButton = $();
        //$('select:not(.searchable)').closest('.input-group').append('<span class="input-group-btn hidden"><button class="btn btn-sm red " type="button"><i class="fa fa-times"></i></button></span>');
        $('select:enabled').closest('.input-group').append('<span class="input-group-btn hidden"><button class="btn btn-sm red " type="button"><i class="fa fa-times"></i></button></span>');

        //$('select:not(.searchable)').closest('.input-group').find('span > button').click(function(){
        $('select:enabled').closest('.input-group').find('span > button').click(function(){
            $(this).closest('.input-group').find('select').val('');
            $(this).closest('.input-group').find('select').select2("val", "");
        })
        //$('select:not(.searchable)').trigger('change');
        $('select:enabled').trigger('change');
    }

    return {
        //main function to initiate the module
        init: function () {
            handleValidation();
            //handleFormSubmit()
            handleBootstrapMaxlength();
            handleInputMasks();
            handleDatePickers();
            handleTimePickers();
            handleTimePickers();
            handleDatetimePicker();
            handleRecords();
            handlePopOverConfirmation();
            handleEditButtonClick();
            handleViewButtonClick();
            handleWeekline();

            $('.navbar-nav li').removeClass('active');
            $('.navbar-nav li a[href="'+document.location.href+'"]').closest('li').addClass('active').parent().closest('li').addClass('active');
            
            handleSelectRemoveButton();
        },
        populateDataInForm: function(frm, data, props) {
            populate(frm, data, props);
        }
    };

}();

if (App.isAngularJsApp() === false) { 
    jQuery(document).ready(function() {
        GenereicFormValidation.init(); // init metronic core componets
    });
}