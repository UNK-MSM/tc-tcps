$(document).ready(function(){

    $('a.add-new-record').remove();
    $('#ajax_datatable th:last').attr('width', 120);
    $('body').on('confirmed.bs.confirmation', '[data-toggle="confirmation"].recalculate', function () {
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
        }).always(function(data) {
        });
    });


    $('body').on('confirmed.bs.confirmation', '[data-toggle="confirmation"].refresh_last_stock_prediction', function () {
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
            toastr['success'](data, "Success");
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
            $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass(currentClass);
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

            if(data.recalculations_job_c == 'ERROR')
            {
                $(currentElement).removeAttr('disabled');
                $(currentElement).find('i').removeClass('fa fa-spin fa-spinner').addClass(currentClass).addClass('font-red');
                $(currentElement).attr('title', 'Unable to complete recalculation process');
                toastr['error']("Unable to complete recalculation process", "Error");

            }else
            {

                setTimeout(function() {
                    calculationProgress(calculation_progress_url, currentElement, currentClass);
                }, 3000);
            }
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