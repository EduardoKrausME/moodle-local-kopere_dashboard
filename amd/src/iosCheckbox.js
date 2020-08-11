/**
 * iosCheckbox.js
 * Version: 1.0.0
 * Author: Ron Masas
 */
(function(factory) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], factory);
    } else if (typeof module === "object" && module.exports) {
        module.exports = factory(require("jquery"));
    } else {
        factory(jQuery);
    }
}(function($) {
    $.fn.extend({
        iosCheckbox : function() {
            this.destroy = function() {
                $(this).each(function() {
                    $(this).next('.ios-ui-select').remove();
                });
            };

            if ($(this).attr('data-ios-checkbox') === 'true') {
                return;
            }

            $(this).attr('data-ios-checkbox', 'true');

            $(this).each(function() {
                /**
                 * Original checkbox element
                 */
                var org_checkbox = $(this);
                /**
                 * iOS checkbox div
                 */
                var ios_checkbox = jQuery("<div>", {
                    class : 'ios-ui-select'
                }).append(jQuery("<div>", {
                    class : 'inner'
                }));

                if (org_checkbox.is(":checked")) {
                    ios_checkbox.addClass("checked");
                }

                org_checkbox.css({opacity : 0})
                    .after(ios_checkbox);

                if (org_checkbox.is(":disabled")) {
                    return ios_checkbox.css('opacity', '0.6');
                }
                var checkId = org_checkbox.attr("id");

                ios_checkbox.click(function() {
                    ios_checkbox.toggleClass("checked");
                    org_checkbox.click();
                    setTimeout(function() {
                        if (ios_checkbox.hasClass("checked") && !org_checkbox.is(":checked")) {
                            // console.log("checked");
                            $("#" + checkId).prop('checked', "checked");
                        } else if (!ios_checkbox.hasClass("checked") && org_checkbox.is(":checked")) {
                            // console.log("not-checked");
                            $("#" + checkId).prop('checked', "");
                        }

                    }, 1000);
                });

                // console.log("[for=" + checkId + "]");
                $("[for=" + checkId + "]").click(function() {
                    setTimeout(function() {
                        if (org_checkbox.is(":checked")) {
                            ios_checkbox.addClass("checked");
                        } else {
                            ios_checkbox.removeClass("checked");
                        }
                    }, 100);
                });
            });
            return this;
        }
    });
}));
