/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 

define(function(require) {
    var elgg = require("elgg");
    var $ = require("jquery");

    // stop the fading animation
    $('.elgg-message').stop(true, false);

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

    //report exports
    // disable cycle select dropdown if the "How did you hear..." option is selected
    $('#nihcp-ccreq-report-select').change(function() {
        if ($('#nihcp-ccreq-report-select').val() === "How Did You Learn About The Portal") {
            $('#nihcp-ccreq-cycle-select').val(0);
            $('#nihcp-ccreq-cycle-select').prop( "disabled", true );
            $('.nihcp-ccreq-report').append("<div id='not-cycle-specific'>This report is not cycle specific.</div>");
        } else {
            $('#nihcp-ccreq-cycle-select').prop( "disabled", false );
            $('#not-cycle-specific').remove();
        }
    });

    // click handler for widget collapse button
    // adds aria-expanded attribute for accessibility
    $('.elgg-widget-collapse-button').on('keypress click', function(e){
        if (e.which === 13 || e.type === 'click') {
            var element = $(this);
            if (element.hasClass("elgg-widget-collapsed")) { // it was collapsed, and now being expanded
                element.attr("aria-expanded", "true");
            } else { // was expanded, and now being collapsed
                element.attr("aria-expanded", "false");
            }
        }
    });



});
