define(["jquery", 'core/ajax', 'core/notification'], function($, ajax, notification) {
    return {
        init : function() {

            setInterval(function() {

                ajax.call([{
                    methodname : 'kopere_dashboard_performancemonitor_disk_moodledata',
                    args       : {}
                }])[0].then(function(data) {
                    $("#load_monitor-performancemonitor_hd").html(data.disk)
                }).then(function(msg) {
                    console.error(msg);
                }).catch(notification.exception);

            }, 30 * 1000);
        }
    };
});
