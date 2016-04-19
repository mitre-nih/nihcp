define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");
});

$('.ccreq-required').bind('change', function() {
	var allChecked = true;
	$('.ccreq-required').each(function( index ) {
		if (!$(this).prop('checked')) {
			allChecked = false;
		}
	});


	if(allChecked) {
		$('#ccreq-submit-button').prop("disabled", false);
		$('#ccreq-submit-button').removeClass("disabled");
		$('#ccreq-submit-button').addClass("elgg-button-submit");
	} else {
		$('#ccreq-submit-button').prop("disabled", true);
		$('#ccreq-submit-button').addClass("disabled");
		$('#ccreq-submit-button').removeClass("elgg-button-submit");
	}
});