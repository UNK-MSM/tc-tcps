$(document).ready(function(){

    $('body').on('confirmed.bs.confirmation', '[data-toggle="confirmation"].activate-membership', function () {
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
            toastr['success']("Memebership is activated successfully", "Success");
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

});