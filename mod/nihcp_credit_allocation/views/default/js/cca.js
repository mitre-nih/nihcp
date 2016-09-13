define(function(require) {
    var elgg = require("elgg");
    var $ = require("jquery");
    var autoNumeric = require("autoNumeric");

	$(function() {
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
							$('.cca-allocation-status').text('Submitted');
							$('#cca-allocate-button').remove();
							$('.cca-action').remove();
							button.remove();
						}
					}
				});
			}
		});

		$('.cca-delete-button').click(function() {
			var button = $(this);
			var request_guid = button.data('request-guid');
			var vendor_guid = button.data('vendor-guid');
			elgg.action('nihcp_credit_allocation/delete', {
				data: {request_guid: request_guid, vendor_guid: vendor_guid},
				success: function (output) {
					if(output.status !== -1) {
						button.closest('tr').remove();
					}
				}
			});
		})
	});

});