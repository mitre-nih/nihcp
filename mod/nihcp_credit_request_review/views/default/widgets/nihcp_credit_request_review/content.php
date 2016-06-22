<?php

$content = "<div class=\"ptm\"><a href=\"" . elgg_get_site_url() . "nihcp_credit_request_review/overview\">" . elgg_echo("nihcp_credit_request_review:crr:more") . "</a></div>";

if(nihcp_triage_coordinator_gatekeeper(false)) {
	$content .=
		"<div class=\"ptm\"><a href=\"" . elgg_get_site_url() . "credit_request_cycle\">" . elgg_echo("nihcp_commons_credit_request:cycles:view") . "</a></div>";
}

echo $content;
