define(["jquery"], function($, ajax, notification) {
    return {
        start : function() {

            $("#dashboard-monitor").load("./load-ajax.php?classname=dashboard&method=monitor");

            $("#dashboard-last_grades").load("./load-ajax.php?classname=dashboard&method=last_grades");

            $("#dashboard-last_enroll").load("./load-ajax.php?classname=dashboard&method=last_enroll");
        }
    };
});
