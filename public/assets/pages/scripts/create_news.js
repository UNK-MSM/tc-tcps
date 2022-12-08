$(document).ready(function(){
    $('select[name="related_market_serial_no"]').on('change', function(){
        var $this = $(this);
        var selectedMarket = $this.find('option[value="'+$this.val()+'"]').html();
        $('select[name="related_stocks[]"] option').prop('disabled', false);
        if(selectedMarket != undefined)
        {
            $('select[name="related_stocks[]"] option').prop('disabled', true);

            $('select[name="related_stocks[]"] optgroup[label="'+selectedMarket+'"] option').prop('disabled', false);
            $('select[name="related_stocks[]"]').select2('val', '');
            $('select[name="related_stocks[]"]').select2();
        }
    });
    if($('select[name="related_stocks[]"]').val() == undefined)
    {
        $('select[name="related_market_serial_no"]').trigger("change");
    }
});