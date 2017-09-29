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
use Nihcp\Entity\DigitalObjectReport;

// get which action was called
$action = get_input('action');

// get the pir_guid associated with this digital object
// the logged in user should be the one who created the PIR
$pir_guid = get_input('pir_guid');

// if user doesn't have access to the pir, then there's something fishy happening
// abort the action
$pir = get_entity($pir_guid);
if (empty($pir)) {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
}


switch ($action) {
    case 'Submit':
        $do_reuse = get_input("do_reuse");
        $overall_issues = get_input("overall_issues");

        // record whether there are any digital objects to share or not
        $dors = DigitalObjectReport::getDigitalObjectReportsFromPirGuid($pir_guid);
        $pir->no_digital_objects_to_share = empty($dors);

        $pir->do_reuse = $do_reuse;
        $pir->overall_issues = $overall_issues;
        $pir->status = 'Submitted';
        $pir->submitted_date = time();
        $pir->save();
        elgg_trigger_event('submit_pir', 'object:'.PostImplementationReport::SUBTYPE, $pir);
        break;
    default:
        break;
}

forward('nihcp_post_implementation_report/overview');