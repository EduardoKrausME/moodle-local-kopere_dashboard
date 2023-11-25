define([
    "jquery"
], function($) {
    return {

        userenrolment_status : function() {
            function timeend_status_change(delay) {
                if (delay != 0) {
                    delay = 400;
                }

                if ($('#timeend-status').val() == 1) {
                    $('.area_timeend').show(delay);
                } else {
                    $('.area_timeend').hide(delay);
                }
            }

            function status_change(delay) {
                if (delay != 0) {
                    delay = 400;
                }

                if ($('#status').val() == 0) {
                    $('.area-inscricao-times').show(delay);
                } else {
                    $('.area-inscricao-times').hide(delay);
                }
            }

            timeend_status_change(0);
            status_change(0);

            $('#timeend-status').change(timeend_status_change);
            $('#status').change(status_change);
        },

    };
});
