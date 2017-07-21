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
								// submitting Final Decision
								$('#crr-final-decision-submit-button').click(function() {

									var button = $(this);
									var confirm = window.confirm(elgg.echo('question:areyousure'));
									if(confirm) {
										var action = button.attr('value');
										var request_guid = $('#request_guid').attr('value');
										var decision = $('#decision').attr('value');
										var feedback_comments = $('#feedback_comments').val();
										elgg.action('decide-request', {
											data: {action: action,
												request_guid: request_guid,
												decision: decision,
												feedback_comments: feedback_comments},
											success: function () {
												location.reload();
											}

										});
									}
								});
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

	// submitting Final Recommendation
	$('#crr-final-recommendation-submit-button').click(function() {

		var button = $(this);
		var confirm = window.confirm(elgg.echo('question:areyousure'));
		if(confirm) {
			var action = button.attr('value');
			var request_guid = $('#request_guid').attr('value');
			var final_recommendation = $('#final_recommendation').attr('value');
			var final_recommendation_comments = $('#final_recommendation_comments').val();
			elgg.action('final-recommendation', {
				data: {action: action,
					request_guid: request_guid,
					final_recommendation: final_recommendation,
					final_recommendation_comments: final_recommendation_comments},
				success: function () {
					window.location.href = "..";
				}

			});
		}
	});


});