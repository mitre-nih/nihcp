define(function(require) {
    var elgg = require("elgg");
    var $ = require("jquery");
    var autoNumeric = require("autoNumeric");

    // auto-formatting of Digital Objects to values between 0.00 and 20.00
    $('#general_score').autoNumeric('init', {
        'aSep' : '',
        'vMin' : 0,
        'vMax' : 20,
        'mDec': '2'});

	$(function() {
		$('#nihcp-ccreq-cycle-select').change(function() {
			var cycle_guid = $(this).val();
			elgg.get('ajax/view/nihcp_credit_request_review/overview/requests', {
				data: {
					cycle_guid: cycle_guid
				},
				success: function(output) {
					$('#nihcp-crr-overview-requests').html(output);
					var tableWidth = $(".crr-overview-table").width() * 1.02;
					if (tableWidth > $(".elgg-page-default .elgg-page-body .elgg-inner").width()) {
						$(".elgg-page-default .elgg-page-body .elgg-inner").css("max-width", $(".crr-overview-table").width() * 1.02);
					}// 1.02 is to account for margins/paddings
					$('.crr-approver-button').click(function() {
						var button = $(this);
						var request_guid = button.closest('tr').attr('id');
						var decision = button.val();
						elgg.get('ajax/view/nihcp_credit_request_review/decide-request', {
							data: {request_guid: request_guid, decision: decision},
							success: function (output) {
								$('.crr-overview-page').html(output);
							}
						});
					});
				}
			});
		});
		$('#nihcp-ccreq-cycle-select').trigger('change');
	});

	// unassigning Domain Experts
	$('.crr-de-unassign').click(function() {
		var button = $(this);
		var confirm = window.confirm(elgg.echo('question:areyousure'));
		if(confirm) {
			var unassign_de_guid = button.closest('tr').attr('id');
			var request_guid = button.attr('id');
			elgg.action('assign-de', {
				data: {unassign: unassign_de_guid, request_guid: request_guid},
				success: function () {
					location.reload();
				}
			});
		}
	});

});