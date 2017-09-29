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
