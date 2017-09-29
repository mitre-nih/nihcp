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


    $(function() {
        $('#nihcp-ccreq-cycle-select').change(function() {
            var cycle_guid = $(this).val();
            $(".crrLoader").show();

            elgg.get('ajax/view/nihcp_post_implementation_report/overview/reports', {
                data: {
                    cycle_guid: cycle_guid
                },
                success: function(output) {
                    $(".crrLoader").hide();
                    $('#nihcp-pir-overview-reports').html(output);
                    $('#pir-overview-table').tablesorter({
                        headers: {
                            1: {
                                sorter:'ccreq-id'
                            }
                        }

                    });
                }
            });
        });

        var handleSearchInput = function(e){
            if (e.which === 13 || e.type === 'click') {
                var search_term = $("#nihcp-pir-search-input").val().trim();
                if(search_term == ""){
                    $('#nihcp-pir-overview-reports').html("Please enter a search term.");
                }else {
                    $(".crrLoader").show();
                    elgg.get('ajax/view/nihcp_post_implementation_report/overview/reports', {
                        data: {
                            search_term: search_term
                        },
                        success: function (output) {
                            $(".crrLoader").hide();
                            $('#nihcp-pir-overview-reports').html(output);
                            $('#pir-overview-table').tablesorter({
                                headers: {
                                    1: {
                                        sorter:'ccreq-id'
                                    }
                                }

                            });
                        }
                    });//elgg.get
                }
            }//if event logic
        };//handleSearchInput

        $('#nihcp-pir-search-submit').click(handleSearchInput);
        $('#nihcp-pir-search-input').keypress(handleSearchInput);

        $('#nihcp-ccreq-cycle-select').trigger('change');
    });

    // deleting digital object reports
    $('.dor-delete-button').click(function() {
        var button = $(this);
        var confirm = window.confirm(elgg.echo('question:areyousure'));
        if(confirm) {
            var dor_guid = button.closest('tr').attr('id');
            elgg.action('delete_digital_object_report', {
                data: {dor_guid: dor_guid},
                success: function () {
                    location.reload();
                }
            });
        } else {
            return false;
        }
    });

    // submitting PIR
    $('#pir-submit-button').click(function() {

        var button = $(this);
        var confirm = window.confirm(elgg.echo('nih_pir:question:areyousure'));
        if(confirm) {
            var action = button.attr('value');
            var pir_guid = $('#pir_guid').attr('value');
            var do_reuse = $('#do_reuse').attr('value');
            var overall_issues = $('#overall_issues').val();
            elgg.action('submission', {
                data: {action: action,
                    pir_guid: pir_guid,
                    do_reuse: do_reuse,
                    overall_issues: overall_issues},
                success: function () {
                    window.location.href = "..";
                }

            });
        } else {
            return false;
        }
    });

    // delete uploaded files from draft digital object reports
    $('.dor-delete-file-button').click(function() {
        var button = $(this);
        var confirm = window.confirm(elgg.echo('question:areyousure'));
        if(confirm) {
            var file_guid = button.attr('file_guid');
            elgg.action('file/delete', {
                data: {file_guid: file_guid},
                success: function () {
                    button.closest('.dor-file-upload').remove();
                }
            });
        }

    });

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
    }catch(e){
        console.log("tablesorter not included on page");
    }


    // form validation for required input fields
    var input_elts = $('.required-field');

    input_elts.on('keyup', function() {
        var all_filled = true;
        input_elts.each(function(){
            var val = $(this).val().trim();
            var type = $(this).attr('type');
            if(type != 'file' && (val == '' || val == null)) {
                all_filled = false;
            }
        });
        if(all_filled) {
            $('#do-save-button').prop("disabled", false);
            $('#do-save-button').removeClass("disabled");
            $('#do-save-button').addClass("elgg-button-submit");
        } else {
            $('#do-save-button').prop("disabled", true);
            $('#do-save-button').addClass("disabled");
            $('#do-save-button').removeClass("elgg-button-submit");
        }
    });

    var no_dos = $('#no-digital-objects-checkbox');
    no_dos.on('input', function() {
        if ($(this).prop('checked')) {
            $('#pir-next-button').prop("disabled", false);
        } else {
            $('#pir-next-button').prop("disabled", true);
        }
    });

    // auto-formatting of cost fields to dollar format
    $('.pir-form-cost').autoNumeric('init', {
        'aSep' : '',
        'vMin' : 0,
        'vMax' : 999999999.99});

    // auto-formatting of cost fields to dollar format
    $('.pir-form-number').autoNumeric('init', {
        'aSep' : '',
        'vMin' : 0,
        'vMax' : 999999999999});
});