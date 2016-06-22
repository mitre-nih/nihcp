<?php
$cycle_guid = elgg_extract('cycle_guid', $vars);
$requests = \Nihcp\Entity\CommonsCreditRequest::getByUserAndCycle('all', 0, $cycle_guid);
$full_view = elgg_extract('full_view', $vars, true);

if($full_view === 'widget') {
	$full_view = false;
}

echo elgg_view('commons_credit_request/overview/components/table', ['full_view' => $full_view, 'requests' => $requests]);