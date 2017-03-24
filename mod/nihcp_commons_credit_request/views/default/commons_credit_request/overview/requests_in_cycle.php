<?php
use Nihcp\Entity\CommonsCreditRequest;

$search_term = elgg_extract('search_term',$vars);
if($search_term) {
    $search_term = sanitise_string($search_term);
    $requests = CommonsCreditRequest::searchByTitle($search_term);
}else{
    $cycle_guid = elgg_extract('cycle_guid', $vars);
    $session = elgg_get_session();
    $session->set('ccr_prev_selected_cycle', $cycle_guid);
    $requests = CommonsCreditRequest::getByUserAndCycle('!' . CommonsCreditRequest::DRAFT_STATUS, 0, $cycle_guid);
}

$full_view = elgg_extract('full_view', $vars, true);

if($full_view === 'widget') {
	$full_view = false;
}

if(!$full_view && !empty($requests)) {
	$requests = array_slice($requests, 0, 5);
}

echo elgg_view('commons_credit_request/overview/components/table', ['full_view' => $full_view, 'requests' => $requests]);
