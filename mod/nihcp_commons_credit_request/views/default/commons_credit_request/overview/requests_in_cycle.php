<?php
use Nihcp\Entity\CommonsCreditRequest;

$cycle_guid = elgg_extract('cycle_guid', $vars);
$requests = CommonsCreditRequest::getByUserAndCycle('!'.CommonsCreditRequest::DRAFT_STATUS, 0, $cycle_guid);
$full_view = elgg_extract('full_view', $vars, true);

if($full_view === 'widget') {
	$full_view = false;
}

if(!$full_view && !empty($requests)) {
	$requests = array_slice($requests, 0, 5);
}

echo elgg_view('commons_credit_request/overview/components/table', ['full_view' => $full_view, 'requests' => $requests]);