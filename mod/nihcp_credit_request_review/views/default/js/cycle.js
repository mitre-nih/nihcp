define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");
	var autoNumeric = require("autoNumeric");

	// deleting cycles
	$('.ccr-cycle-delete-button').click(function() {
		var button = $(this);
		var confirm = window.confirm(elgg.echo('question:areyousure'));
		if(confirm) {
			var cycle_guid = button.attr('cycle_guid');
			elgg.action('credit_request_cycle/delete', {
				data: {cycle_guid: cycle_guid},
				success: function () {
					$('#' + cycle_guid).remove();
				}
			});
		}
	});

	$('#ccr-cycle-stratification-threshold-input').autoNumeric('init', {
		'aSep' : '',
		'vMin' : 0,
		'vMax' : 99999.99});

	// form validation for required input fields
	/*var input_elts = $('.required-field input');

	input_elts.on('input', function() {
		console.log('foo');
		var all_filled = true;
		input_elts.each(function(){
			var val = $(this).val().trim();
			if(val == '' || val == null) {
				all_filled = false;
			}
		});
		if(all_filled) {
			alert('filled');
			$('#ccr-cycle-save-button').prop("disabled", false);
			$('#ccr-cycle-save-button').removeClass("disabled");
			$('#ccr-cycle-save-button').addClass("elgg-button-submit");
		} else {
			alert('not');
			$('#ccr-cycle-save-button').prop("disabled", true);
			$('#ccr-cycle-save-button').addClass("disabled");
			$('#ccr-cycle-save-button').removeClass("elgg-button-submit");
		}
	});*/
});