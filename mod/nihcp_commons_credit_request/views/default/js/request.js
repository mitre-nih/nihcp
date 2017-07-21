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


    // disable commons credit request form submission on enter key
    $("#ccreq-form").keypress(
        function(event){
            if (event.which == '13') {
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



	$(function() {
		$('#nihcp-ccreq-cycle-select').change(function() {
			var cycle_guid = $(this).val();
			elgg.get('ajax/view/commons_credit_request/overview/requests_in_cycle', {
				data: {
					cycle_guid: cycle_guid,
					full_view: $(this).closest('.elgg-widget-content').length !== 0 ? 'widget' : true
				},
				success: function(output) {
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
