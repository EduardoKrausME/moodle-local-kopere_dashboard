define([
    "jquery",
    "local_kopere_dashboard/maskedinput",
    "local_kopere_dashboard/validate",
], function($, validator) {
    return {
        init : function() {

            console.log("form_popup.js");

            if ($(".kopere-modal-content form.validate").length) {
                $(".kopere-modal-content form.validate").validator();
            }

            $('.kopere-modal-content .button-actions').click(function(event) {
                event.stopImmediatePropagation();
            });
        }
    };
});
