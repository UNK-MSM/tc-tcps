$(document).ready(function(){
    $('select[name="urgent_calculation_cause_serial_no"]').on('change', function(){
        var el = $(this).closest(".portlet-body");
        var url = $(this).data('url');
        var selectedItem = $(this).val();
        url = url + '/' + selectedItem;

        App.blockUI({
            target: el,
            animate: true,
            overlayColor: 'none'
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
});