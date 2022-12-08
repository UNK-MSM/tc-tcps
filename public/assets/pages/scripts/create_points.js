$(document).ready(function(){
    $('select[name="param_2"]').on('change', function(){
        var $select = $(this);
        $('textarea').each(function(){
            $(this).html('');
            if($select.val()==1)
            {
                $(this).html($(this).data('claim_message'));
            }else if($select.val()==-1)
            {
                $(this).html($(this).data('redemption_message'));
            }
        });
    });
    $('textarea[name="body_ar"]').css('direction', 'rtl');
    $('textarea[name="body_en"]').after('<span class="help-block" style="font-size: 11px;"> Use "{points}" tag to reference points amount in your message to the user. </span>');
    $('textarea[name="body_ar"]').after('<span class="help-block" style="direction: rtl; font-size: 11px;"> استخدم المعرف {points} للدلالة على عدد النقاط في رسالتك للمستخدم. </span>');
});