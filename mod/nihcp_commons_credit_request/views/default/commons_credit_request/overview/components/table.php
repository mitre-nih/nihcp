<?php
$full_view = elgg_extract('full_view', $vars);
$requests = elgg_extract('requests', $vars);
$content = '';
if(!empty($requests)) {
	$content .= "<table summary=\"" . elgg_echo('nihcp_commons_credit_request:table:summary') . "\" class=\"elgg-table ccreq-overview-table\">";
	$content .= elgg_view('commons_credit_request/overview/components/table_head', ['full_view' => $full_view]);
	$content .= elgg_view('commons_credit_request/overview/components/table_body', ['full_view' => $full_view, 'requests' => $requests]);
	$content .= "</table>";
} else {
	$content .= elgg_echo('nihcp_commons_credit_request:ccreq:none');
}
echo $content;