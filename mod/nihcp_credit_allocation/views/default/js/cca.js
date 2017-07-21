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

	$(function() {
		function registerCCADeleteButtonHandler() {
			$('.cca-delete-button').click(function () {
				var button = $(this);
				var request_guid = button.data('request-guid'),
					vendor_guid = button.data('vendor-guid');
				elgg.action('nihcp_credit_allocation/delete', {
					data: {request_guid: request_guid, vendor_guid: vendor_guid},
					success: function (output) {
						if (output.status !== -1) {
							var row = button.closest('tr');
							var old_alloc = parseFloat(row.find('.cca-allocate-link').text());
							var unalloc_elt = $('#cca-unallocated-amount');
							var old_unalloc = parseFloat(unalloc_elt.text());
							unalloc_elt.text(old_unalloc+old_alloc);
							row.remove();
							$('#cca-allocations-submit-button').removeClass('elgg-button-submit').addClass('disabled').attr('disabled', 1);
							$('#cca-allocate-button').removeClass('disabled').addClass('elgg-button-submit').removeAttr('disabled');
						}
					}
				});
			})
		}
		registerCCADeleteButtonHandler();


		$('#nihcp-ccreq-cycle-select').change(function() {
			var cycle_guid = $(this).val();
			elgg.get('ajax/view/nihcp_credit_allocation/allocations/allocations_in_cycle', {
				data: {
					cycle_guid: cycle_guid
				},
				success: function(output) {
					$('#nihcp-cca-overview-allocations').html(output);
					var tableWidth = $(".cca-overview-table").width() * 1.02;
					if (tableWidth > $(".elgg-page-default .elgg-page-body .elgg-inner").width()) {
						$(".elgg-page-default .elgg-page-body .elgg-inner").css("max-width", $(".cca-overview-table").width() * 1.02);
					}// 1.02 is to account for margins/paddings
					registerCCADeleteButtonHandler();
				}
			});
		});
		$('#nihcp-ccreq-cycle-select').trigger('change');

		$('#cca-allocations-submit-button').click(function() {
			var button = $(this);
			var confirm = window.confirm(elgg.echo('nihcp_credit_allocation:submit:areyousure'));
			if(confirm) {
				var request_guid = $('#request_guid').val();
				elgg.action('nihcp_credit_allocation/submit_allocations', {
					data: {request_guid: request_guid},
					success: function (output) {
						if(output.status !== -1) {
							$('.cca-allocate-link').each(function() {
								var link = $(this);
								var request_guid = link.data('request-guid'),
									vendor_guid = link.data('vendor-guid');
								var new_url = elgg.config.wwwroot+"nihcp_credit_allocation/balance_history/"+request_guid+'/'+vendor_guid;
								link.attr('href', new_url);
							});
							$('.cca-allocation-status').text('Submitted');
							$('#cca-allocate-button').remove();
							$('.cca-action').remove();
							button.remove();
						}
					}
				});
			}
		});

	});

});