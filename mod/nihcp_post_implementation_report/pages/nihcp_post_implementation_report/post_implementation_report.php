<?php

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