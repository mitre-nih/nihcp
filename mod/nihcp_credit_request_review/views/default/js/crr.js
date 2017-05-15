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
	try{
		$.tablesorter.addParser({
			// set a unique id
			id: 'ccreq-id',
			is: function(s) {
				// return false so this parser is not auto detected
				return false;
			},
			format: function(s) {
				// format your data for normalization
				return s;
			},
			// set type, either numeric or text
			type: 'text'
		});
		$.tablesorter.addParser({
			// set a unique id
			id: 'completed',
			is: function(s) {
				// return false so this parser is not auto detected
				return false;
			},
			format: function(s) {
				// format your data for normalization
				var retVal = "";
				if(arguments[0] == "N/A"){
					retVal = "N/A";
				}else {
					retVal = arguments[2].firstChild.getAttribute("class");
				}
				return retVal;
			},
			// set type, either numeric or text
			type: 'text'
		});
        $.tablesorter.addParser({
            // set a unique id
            id: 'roi',
            is: function(s) {
                // return false so this parser is not auto detected
                return false;
            },
            format: function(s) {
                // format your data for normalization
                var retVal = -2;
                try{
                	if(s.charCodeAt(0) == 9898){
						retVal = -1;
					}else if(s == "N/A") {
                        retVal = -3;
                    }else if(s == "No Review"){
                		retVal = -2;
                    }else if(!isNaN(parseFloat(s))){
                		retVal = parseFloat(s);
					}
				}catch(e){
                	console.log("error parsing ROI text");
				}
                return retVal;
            },
            // set type, either numeric or text
            type: 'numeric'
        });
	}catch(e){
		console.log("tablesorter not included on page");
	}

	function registerCRRStatusSelectHandler() {
		$('.crr-status-select').change(function () {
			var result = window.prompt("Enter the reason for changing the status: ", "Fixing input error");
			if(result) {
				var new_status = $(this).val();
				var request_guid = $(this).closest('tr').attr('id');
				elgg.action('change-status', {
					data: {request_guid: request_guid, status: new_status, reason: result}
				});
				var decision_field = $(this).closest('tr').children('.crr-decision');
				if(new_status === 'Completed') {
					decision_field.html('<input class="elgg-button elgg-button-submit crr-approver-button" value="Approve" type="button"><input class="elgg-button elgg-button-cancel crr-approver-button" value="Deny" type="button">');
					registerCRRApproverButtonHandler();
				} else {
					decision_field.text('N/A');
				}
				$('option', $(this)).filter(function() {return ['Approved', 'Denied'].indexOf($(this).val()) !== -1;}).remove();
			} else {
				var old_status = $(this).attr('x-status');
				$(this).val(old_status);
			}
		})
	}
	registerCRRStatusSelectHandler();

	function registerCRRApproverButtonHandler() {
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

	$(function() {
		$('#nihcp-ccreq-cycle-select').change(function() {
			var cycle_guid = $(this).val();
			$(".crrLoader").show();
			elgg.get('ajax/view/nihcp_credit_request_review/overview/requests', {
				data: {
					cycle_guid: cycle_guid
				},
				success: function(output) {
					$(".crrLoader").hide();
					$('#nihcp-crr-overview-requests').html(output);
					var tableWidth = $(".crr-overview-table").width() * 1.02;
					if (tableWidth > $(".elgg-page-default .elgg-page-body .elgg-inner").width()) {
						$(".elgg-page-default .elgg-page-body .elgg-inner").css("max-width", $(".crr-overview-table").width() * 1.02);
					}// 1.02 is to account for margins/paddings
					registerCRRApproverButtonHandler();
					registerCRRStatusSelectHandler();


					$('.crr-overview-table').tablesorter({
                        headers: {
                            1: {
                                sorter:'ccreq-id'
                            },
                            9:{
                                sorter: 'roi'
                            },
							12: {
                            	sorter: 'completed'
							}
                        }
                    });
				}
			});
		});
		var handleSearchInput = function(e){
            if (e.which === 13 || e.type === 'click') {
                var search_term = $("#nihcp-crr-search-input").val().trim();
                if(search_term == ""){
                    $('#nihcp-crr-overview-requests').html("Please enter a search term.");
				}else {
                    $(".crrLoader").show();
                    elgg.get('ajax/view/nihcp_credit_request_review/overview/requests', {
                        data: {
                            search_term: search_term,
                            full_view: $(this).closest('.elgg-widget-content').length !== 0 ? 'widget' : true
                        },
                        success: function (output) {
                            $(".crrLoader").hide();
                            $('#nihcp-crr-overview-requests').html(output);
                            $('.crr-overview-table').tablesorter({
                                headers: {
                                    1: {
                                        sorter: 'ccreq-id'
                                    },
                                    9: {
                                        sorter: 'roi'
                                    },
                                    12: {
                                        sorter: 'completed'
                                    }
                                }
                            });
                        }
                    });//elgg.get
                }
            }//if event logic
		};//handleSearchInput

        $('#nihcp-crr-search-submit').click(handleSearchInput);
        $('#nihcp-crr-search-input').keypress(handleSearchInput);
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
