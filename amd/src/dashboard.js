define(["jquery"], function($) {
    return {
        start : function() {
            $("#dashboard-moodleinfo").load(
                M.cfg.wwwroot + "/local/kopere_dashboard/view-ajax.php?classname=dashboard&method=monitor");
            $("#dashboard-last_grades").load(
                M.cfg.wwwroot + "/local/kopere_dashboard/view-ajax.php?classname=dashboard&method=last_grades");
            $("#dashboard-last_enroll").load(
                M.cfg.wwwroot + "/local/kopere_dashboard/view-ajax.php?classname=dashboard&method=last_enroll");
        }
    };
});
