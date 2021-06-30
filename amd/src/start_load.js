define([
    "jquery",
    "local_kopere_dashboard/maskedinput",
    "local_kopere_dashboard/validate",
    "local_kopere_dashboard/iosCheckbox",
], function($, maskedinput, validate, iosCheckbox) {
    return {
        init : function() {
            $(".ios-checkbox").iosCheckbox();
        }
    };
});



