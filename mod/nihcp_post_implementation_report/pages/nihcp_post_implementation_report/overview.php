<?php

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditCycle;

elgg_require_js('pir');

pseudo_atomic_set_ignore_access(function () {

    $cycles = elgg_get_entities_from_metadata([
        'type' => 'object',
        'subtype' => CommonsCreditCycle::SUBTYPE,
        'order_by_metadata' => array(
            'name' => 'start',
            'direction' => 'ASC'
        ),
        'limit' => 0,
    ]);

    $session = elgg_get_session();
    $selected_cycle_guid = $session->get('crr_prev_selected_cycle', CommonsCreditCycle::getActiveCycleGUID());

    $params = array(
        'title' => elgg_echo("nihcp_pir"),
        'content' => elgg_view('nihcp_post_implementation_report/overview', array('cycles' => $cycles, 'selected_cycle_guid' => $selected_cycle_guid)),
        'filter' => '',
    );

    $body = elgg_view_layout('one_column', $params);

    echo elgg_view_page(elgg_echo("nihcp_pir"), $body, 'default');

});
