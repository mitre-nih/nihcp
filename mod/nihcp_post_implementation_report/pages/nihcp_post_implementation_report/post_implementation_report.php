<?php
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
use Nihcp\Entity\PostImplementationReport;

pseudo_atomic_set_ignore_access(function () {

    // reviewer view
    if (nihcp_triage_coordinator_gatekeeper(false) || nihcp_nih_approver_gatekeeper(false)) {
        $content = elgg_view('nihcp_post_implementation_report/pir_summary');
    } else { // investigator view


        $ccreq_guid = sanitize_int(get_input("ccreq_guid"));

        //check to see if pir is still editable
        $pir = get_entity(PostImplementationReport::getPirGuidFromCcreqGuid($ccreq_guid));

        if (empty($pir) || $pir->status === 'Draft') {
            $content = elgg_view('nihcp_post_implementation_report/post_implementation_report');
        } else if ($pir->owner_guid === elgg_get_logged_in_user_guid()) {
            $content = elgg_view('nihcp_post_implementation_report/pir_summary');
        } else {
            register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
            forward(REFERER);
        }
    }

    $params = array(
        'title' => elgg_echo("nihcp_pir"),
        'content' => $content,
        'filter' => '',
    );

    $body = elgg_view_layout('one_column', $params);

    echo elgg_view_page(elgg_echo("nihcp_pir"), $body, 'default');

});