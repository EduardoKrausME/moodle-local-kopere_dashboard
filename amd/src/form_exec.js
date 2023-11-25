define([
    "jquery"
], function($) {
    return {

        form_close_and_auto_submit_input : function(campo) {
            $('#' + campo).change(function() {
                $('#submit_' + campo).click();
            });
        }

    };
});

