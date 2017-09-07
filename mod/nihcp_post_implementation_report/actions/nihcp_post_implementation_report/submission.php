<?php

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