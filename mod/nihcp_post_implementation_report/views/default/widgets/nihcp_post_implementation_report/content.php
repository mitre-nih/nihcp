<?php

use \Nihcp\Entity\CommonsCreditRequest;

if (nihcp_triage_coordinator_gatekeeper(false) || nihcp_nih_approver_gatekeeper(false)) { // reviewers
    $content = "<div class=\"ptm\"><a href=\"" . elgg_get_site_url() . "nihcp_post_implementation_report/overview\">" . elgg_echo("nihcp_pir:widget:link:reviewers") . "</a></div>";
} else if (empty(CommonsCreditRequest::getByUser(CommonsCreditRequest::APPROVED_STATUS))) { // non-reviewer, but no approved requests
    $content = "<div class=\"ptm\">No Report Required</div>";
} else { // logged in user has approved requests
    $content = "<div class=\"ptm\"><a href=\"" . elgg_get_site_url() . "nihcp_post_implementation_report/overview\">" . elgg_echo("nihcp_pir:widget:link:investigators") . "</a></div>";
}

echo $content;