
define(function(require) {
    var elgg = require("elgg");
    var $ = require("jquery");

    $('a').filter(function () {
        return this.hostname && this.hostname !== location.hostname;
    }).attr("target", "_blank");

    $('a').filter(function () {
        return this.hostname && this.hostname !== location.hostname;
    }).click(function (e) {
        if (!confirm("You are about to proceed to an external website.")) {
            // if user clicks 'no' then dont proceed to link.
            e.preventDefault();
        }
    });

    $('body').on('change', '#how_did_you_hear_about_us', function() {
        if ($('#how_did_you_hear_about_us').val() == 'other') {
            $('#how_did_you_hear_about_us_other').attr("required", true);
        } else {
            $('#how_did_you_hear_about_us_other').removeAttr("required");
        }
    });
});