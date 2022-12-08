$(document).ready(function(){
    var modal = '';
    modal += '<div id="settings-modal" class="modal fade" role="dialog" aria-hidden="true">';
    modal += '    <div class="modal-dialog">';
    modal += '        <div class="modal-content">';
    modal += '            <div class="modal-header">';
    modal += '                <a href="javascript:;" class="close" data-dismiss="modal" aria-hidden="true"></a>';
    modal += '                <h4 class="modal-title">Clear Stock Level Settings</h4>';
    modal += '            </div>';
    modal += '            <div class="modal-body">';
    modal += '                <form action="#" class="form-horizontal">';
    modal += '                    <div class="row">';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Min Normal Range</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_normal_range_min_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Max Normal Range</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_normal_range_max_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                    </div>';
    modal += '                    <div class="row">';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Min Emergency Range</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_emergency_range_min_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Max Emergency Range</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_emergency_range_max_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                    </div>';
    modal += '                    <div class="row">';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Difference %</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_difference_in_percent_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Addition %</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_addition_in_percent_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                    </div>';
    modal += '                    <div class="row">';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Minimum %</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_minimum_in_percent_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                    </div>';
    modal += '                    <div class="row">';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Invert Tuning</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_invert_tuning_enabled_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                        <div class="col-md-6">';
    modal += '                            <div class="form-group">';
    modal += '                                <label class="control-label col-md-7">Push Tuning</label>';
    modal += '                                <div class="col-md-5">';
    modal += '                                    <input type="checkbox" name="clear_push_tuning_enabled_in" class="make-switch" data-size="small" value=1>';
    modal += '                                </div>';
    modal += '                            </div>';
    modal += '                        </div>';
    modal += '                    </div>';
    modal += '                </form>';
    modal += '            </div>';
    modal += '            <div class="modal-footer">';
    modal += '                <a href="javascript:;" class="btn grey-salsa btn-outline" data-dismiss="modal">Close</a>';
    modal += '                <a href="javascript:;" class="btn green clear_settings">';
    modal += '                    <i class="fa fa-check"></i> Submit</a>';
    modal += '            </div>';
    modal += '        </div>';
    modal += '    </div>';
    modal += '</div>';
    $('.page-content-inner').append(modal);

    $('#settings-modal .make-switch').bootstrapSwitch();

    var clearSettingsModalButton = '';
    clearSettingsModalButton += '<a data-original-title="" title="" href="#settings-modal" role="button" data-toggle="modal">';
    clearSettingsModalButton += '<i class="fa fa-cog font-grey-silver"></i>';
    clearSettingsModalButton += '</a>';
    $('.portlet-title > .tools').append(clearSettingsModalButton);
    $('.clear_settings').on('click', function(){
        var url = $('#generic_form').attr('action').split('setting')[0]+'setting/clear';
        //var postData = $('#settings-modal form').serialize();
        var el = $('#settings-modal');
        App.blockUI({
            target: el,
            animate: true,
            overlayColor: 'none'
        });
        $.ajax({
            url : url,
            type: 'POST',
            //data: postData,
            data: {
                clear_normal_range_min_in: $('input[name="clear_normal_range_min_in"]').bootstrapSwitch('state'),
                clear_normal_range_max_in: $('input[name="clear_normal_range_max_in"]').bootstrapSwitch('state'),
                clear_emergency_range_min_in: $('input[name="clear_emergency_range_min_in"]').bootstrapSwitch('state'),
                clear_emergency_range_max_in: $('input[name="clear_emergency_range_max_in"]').bootstrapSwitch('state'),
                clear_difference_in_percent_in: $('input[name="clear_difference_in_percent_in"]').bootstrapSwitch('state'),
                clear_addition_in_percent_in: $('input[name="clear_addition_in_percent_in"]').bootstrapSwitch('state'),
                clear_minimum_in_percent_in: $('input[name="clear_minimum_in_percent_in"]').bootstrapSwitch('state'),
                clear_invert_tuning_enabled_in: $('input[name="clear_invert_tuning_enabled_in"]').bootstrapSwitch('state'),
                clear_push_tuning_enabled_in: $('input[name="clear_push_tuning_enabled_in"]').bootstrapSwitch('state')
            },
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }}).done(function(data){
            toastr['success'](data.message, "Success");
            setTimeout(function() {
                //window.location = data.redirect_url;
            }, 1000);

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
});