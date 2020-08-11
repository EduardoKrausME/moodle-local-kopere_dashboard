define([
    "jquery",
    "local_kopere_dashboard/maskedinput",
    "local_kopere_dashboard/validate",
    "local_kopere_dashboard/iosCheckbox",
], function($, daterangepicker, validator) {
    return {
        init : function() {
            $(".ios-checkbox").iosCheckbox();
        }
    };
});



