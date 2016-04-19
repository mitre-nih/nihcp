<?php

use \Nihcp\Entity\CommonsCreditRequest;

$requests = elgg_get_entities([
	'type' => 'object',
	'subtype' => CommonsCreditRequest::SUBTYPE,
	'owner_guid' => elgg_get_logged_in_user_entity()->getGUID(),
	'order_by' => 'time_created desc',
	'limit' => 3
]);

$content = elgg_view('commons_credit_request/overview', array('requests' => $requests, 'full_view' => false));

$content .= "<div class=\"ptm\"><a href=\"".elgg_get_site_url()."nihcp_commons_credit_request/overview\">".elgg_echo("nihcp_commons_credit_request:ccreq:more")."</a></div>";

echo $content;