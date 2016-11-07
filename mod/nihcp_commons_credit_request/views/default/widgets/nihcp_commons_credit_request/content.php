<?php

use \Nihcp\Entity\CommonsCreditCycle;
use \Nihcp\Entity\CommonsCreditRequest;

$ia = elgg_set_ignore_access();

$cycles = CommonsCreditCycle::getCycles($omit_future = true);

$session = elgg_get_session();
$selected_cycle_guid = $session->get('ccr_prev_selected_cycle', CommonsCreditCycle::getActiveCycleGUID());

$content = elgg_view('commons_credit_request/overview', array('cycles' => $cycles, 'selected_cycle_guid' => $selected_cycle_guid, 'full_view' => false));

$has_approved_requests = !empty(CommonsCreditRequest::getByUser(\Nihcp\Entity\CommonsCreditRequest::APPROVED_STATUS));

elgg_set_ignore_access($ia);

if ($has_approved_requests) {
    $link_text = elgg_echo("nihcp_commons_credit_request:ccreq:more_and_allocate");
} else {
    $link_text = elgg_echo("nihcp_commons_credit_request:ccreq:more");
}

$content .= "<div class=\"ptm\"><a href=\"".elgg_get_site_url()."nihcp_commons_credit_request/overview\">".$link_text."</a></div>";

echo $content;