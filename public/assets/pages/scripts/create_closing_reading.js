$(document).ready(function(){
    $('.portlet-body').prepend('<div id="info-section"> </div>');
    $('button[type="submit"]').before('<button type="submit" class="btn green" value="submit_and_new" name="submit_type" style="margin-right: 3px;"><i class="fa fa-spinner fa-spin hidden"> </i> Submit & New</button>');
    $('select[name="stock_serial_no"]').on('change', function(){
        var $this = $(this);
        var el = $(this).closest(".portlet-body");
        var url = $(this).data('url');
        var selectedItem = $(this).val();
        url = url + '/' + selectedItem;

        App.blockUI({
            target: el,
            animate: true,
            overlayColor: 'none'
        });
        $('#info-section').html('');

        $.ajax({
            url : url,
            type: 'GET',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }}).done(function(data){
                App.alert({
                    container: '#info-section', // alerts parent container 
                    place: 'append', // append or prepent in container 
                    type: 'info', // alert's type 
                    message: ' &nbsp;&nbsp;Last closing date for selected stock was: '+data.date+', with closing price: '+data.price, // alert's message
                    close: true, // make alert closable 
                    reset: true, // close all previouse alerts first 
                    focus: true, // auto scroll to the alert after shown 
                    icon: 'fa fa-info' // put icon class before the message
                });

                var dateSegments = data.date.split('-');
                var weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                var disabledWeekDays = [];
                $.each(weekDays, function(key, value){
                    if(!data.working_days.includes(value))
                    {
                        disabledWeekDays.push(key);
                    }
                });
                $('input[name="date_closed"]').datepicker('remove');
                $('input[name="date_closed"]').datepicker({
                    rtl: App.isRTL(),
                    orientation: "left",
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    daysOfWeekDisabled: disabledWeekDays
                });
                $('input[name="date_closed"]').datepicker('update', data.next_date);
                //$('input[name="date_closed"]').datepicker('update');
                //$('input[name="date_closed"]').val(data.date);

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
            App.unblockUI(el);
        });
    });
    $('select[name="stock_serial_no"]').trigger("change");
});