$(document).ready(function(){
    $('select[name="related_market_serial_no[]"]').on('change', function(){
        var $this = $(this);
        console.log($this.val());
        if($this.val() != undefined)
        {
            $('select[name="related_stocks[]"]').attr('disabled', 'disabled');
        }else
        {
            $('select[name="related_stocks[]"]').removeAttr('disabled');
        }
        /*console.log($this);
        console.log($this.val());
        $('select[name="related_stocks[]"] option').prop('disabled', false);
        if($this.val() != undefined)
        {
            $('select[name="related_stocks[]"] option').prop('disabled', true);
            $.each($this.val(), function(key, value){
                var selectedMarket = $this.find('option[value="'+value+'"]').html();
                if(selectedMarket != undefined)
                {

                    $('select[name="related_stocks[]"] optgroup[label="'+selectedMarket+'"] option').prop('disabled', false);
                }
            });
        }
        $('select[name="related_stocks[]"]').select2('val', '');
        $('select[name="related_stocks[]"]').select2();*/
    });
    $('select[name="related_stocks[]"]').on('change', function(){
        var $this = $(this);
        console.log($this.val());
        if($this.val() != undefined)
        {
            $('select[name="related_market_serial_no[]"]').attr('disabled', 'disabled');
        }else
        {
            $('select[name="related_market_serial_no[]"]').removeAttr('disabled');
        }
        //$('select[name="related_market_serial_no[]"]').select2('val', '');
        //$('select[name="related_market_serial_no[]"]').select2();
    });
});