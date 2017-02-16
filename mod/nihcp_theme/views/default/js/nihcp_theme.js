
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

	$('.elgg-sidebar > :not(.elgg-admin-sidebar-menu) li[class^="elgg-menu-item-nihcp-policy-"]').wrapAll("<div class='accordion-panel' />");
	$('.elgg-sidebar > :not(.elgg-admin-sidebar-menu) li[class^="elgg-menu-item-nihcp-policy-"]').addClass('mbm');
	$('div.accordion-panel').before("<button class='accordion-btn'>"+elgg.echo("nihcp_commons_credit_request:nih_policies")+"</button>");

	$('button.accordion-btn').click(function() {
		$(this).toggleClass('active');
		$('div.accordion-panel').toggleClass('show');
	});

    $('.elgg-menu-item-account').focusin(function() {
        $('.elgg-menu-topbar-alt > .elgg-menu-item-account > ul').css("display", "block");
    });

    $('.elgg-menu-item-account').focusout(function() {
        var $elem = $(this);

        // let the browser set focus on the newly clicked elem before check
        setTimeout(function () {
            if (!$elem.find(':focus').length) {
                ;$('.elgg-menu-topbar-alt > .elgg-menu-item-account > ul').css("display", "none");
            }
        }, 0)
    });

    $('.skip-to-content').click(function(){
        $('.elgg-page-body')[0].setAttribute('tabindex','-1');
        $('.elgg-page-body')[0].focus();
    });
    $('.skip-to-content').keypress(function(event){
        if(event.keyCode == 13){
            $('.elgg-page-body')[0].setAttribute('tabindex','-1');
            $('.elgg-page-body')[0].focus();
        }
    });


});