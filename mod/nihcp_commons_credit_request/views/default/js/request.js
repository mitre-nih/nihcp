define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");
    var autoNumeric = require("autoNumeric");


    // disable commons credit request form submission on enter key
    // still allow new lines in textareas
    $("#ccreq-form").keypress(
        function(event){
            if (event.which == '13' && !$(":focus").is("textarea")) {
                event.preventDefault();
            }


    });

    // auto-formatting of cost fields to dollar format
    $('.ccreq-cost').autoNumeric('init', {
        'aSep' : '',
        'vMin' : 0,
        'vMax' : 99999.99});


    // delete uploaded files from draft ccreqs
    $('.ccreq-delete-file-button').click(function() {
        var button = $(this);
        var confirm = window.confirm(elgg.echo('question:areyousure'));
        if(confirm) {
            var file_guid = button.attr('file_guid');
            elgg.action('file/delete', {
                data: {file_guid: file_guid},
                success: function () {
                    button.closest('.ccreq-file-upload').remove();
                }
            });
        }
    });

    // form validation for required input fields
    var input_elts = $('.required-field input,.required-field textarea,.required-field select');

    input_elts.on('input', function() {
        var all_filled = true;
        input_elts.each(function(){
            var val = $(this).val().trim();
            if(val == '' || val == null) {
                all_filled = false;
            }
        });
        if(all_filled) {
            $('#ccreq-next-button').prop("disabled", false);
            $('#ccreq-next-button').removeClass("disabled");
            $('#ccreq-next-button').addClass("elgg-button-submit");
        } else {
            $('#ccreq-next-button').prop("disabled", true);
            $('#ccreq-next-button').addClass("disabled");
            $('#ccreq-next-button').removeClass("elgg-button-submit");
        }
    });

    $('#project_title').on('input', function() {
        var val = $(this).val().trim();
        if(val === '' || val === null) {
            $('#ccreq-save-button').prop("disabled", true);
            $('#ccreq-save-button').addClass("disabled");
            $('#ccreq-save-button').removeClass("elgg-button-submit");
        } else {
            $('#ccreq-save-button').prop("disabled", false);
            $('#ccreq-save-button').removeClass("disabled");
            $('#ccreq-save-button').addClass("elgg-button-submit");
        }
    });

    var cost_elts = $('.ccreq-cost');

    var calculate_total_cost = function () {
        var total = 0.0;
        cost_elts.each(function () {
            var raw = $(this).autoNumeric('get');
            var val = parseFloat(raw);
            total += val;
        });
        $('#ccreq-total-cost').text(total);
    }

    cost_elts.change(calculate_total_cost);

    $(document).ready(calculate_total_cost);

    // deleting draft requests
    $('.ccreq-delete-button').click(function() {
        var button = $(this);
        var confirm = window.confirm(elgg.echo('question:areyousure'));
        if(confirm) {
			var request_guid = button.closest('tr').attr('id');
            elgg.action('delete_request', {
                data: {request_guid: request_guid},
                success: function () {
                    $('#' + request_guid).remove();
                }
            });
        }
    });

    $('#grant_id_verify').on('keypress click', function(e){
        if (e.which === 13 || e.type === 'click') {
            console.log("verifying");
            $("#verify_loading").show();
            //ajax request, if fail show the rationale field
            elgg.action('verify_grant_id', {
                data: {
                    grant_id: $("#grant_id").val(),
                },
                success: function (response) {
                    $("#verify_loading").hide();
                    //console.log("success!");
                    if (response.output == 1) {
                        //
                        $('.rationale').hide(); //may or may not be hidden
                        //$('.checkmark').show();
                        $("#verification_status").html("<span style='color: green;font-size:x-large;'>\u2713</span>");
                        $("#verification_status").show();
                    } else if (response.output == 0) {
                        $('.rationale').show();
                        //$('.checkmark').show();
                        $("#verification_status").html("<span style='color: red;font-size:x-large;'>\u2717</span>");
                        $("#verification_status").show();
                    } else if (response.output == "error") {
                        $("#verify_loading").hide();
                        $('.rationale').show();
                        $("#verification_status").html("<span style='color: red;font-size:x-large;'>\u2717</span>");
                        console.log("Verification service returned an error.");
                        elgg.register_error(elgg.echo('Grant id verification returned an error, please enter a rationale to continue.'));
                    }

                },
                error: function (xhr, status) {
                    $("#verify_loading").hide();
                    console.log("failed!");
                    console.log(xhr);
                    elgg.register_error(elgg.echo('grant id verification failed, please try again'));
                }
            });
        }


    });

	$(function() {
		$('#nihcp-ccreq-cycle-select').change(function() {
			var cycle_guid = $(this).val();
            $(".crrLoader").show();
			elgg.get('ajax/view/commons_credit_request/overview/requests_in_cycle', {
				data: {
					cycle_guid: cycle_guid,
					full_view: $(this).closest('.elgg-widget-content').length !== 0 ? 'widget' : true
				},
				success: function(output) {
                    $(".crrLoader").hide();
					$('#nihcp-ccr-overview-requests-in-cycle').html(output);
					// withdrawing submitted requests
					$('.ccreq-withdraw-button').click(function() {
						var button = $(this);
						var confirm = window.confirm(elgg.echo('question:areyousure'));
						if(confirm) {
							var request_guid = button.closest('tr').attr('id');
							elgg.action('withdraw_request', {
								data: {request_guid: request_guid},
								success: function () {
									$('#' + request_guid + ' > .ccreq-status').text('Withdrawn');
									button.closest('.ccreq-withdraw-button').remove();
								}
							});
						}
					});
				}
			});
		});
		$('#nihcp-ccreq-cycle-select').trigger('change');
	});
});
