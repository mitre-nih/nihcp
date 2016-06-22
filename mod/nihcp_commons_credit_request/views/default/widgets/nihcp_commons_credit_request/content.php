<?php

use \Nihcp\Entity\CommonsCreditCycle;

$ia = elgg_set_ignore_access();

$cycles = CommonsCreditCycle::getCycles($omit_future = true);

$selected_cycle_guid = CommonsCreditCycle::getActiveCycleGUID();

$content = elgg_view('commons_credit_request/overview', array('cycles' => $cycles, 'selected_cycle_guid' => $selected_cycle_guid, 'full_view' => false));

elgg_set_ignore_access($ia);

$content .= "<div class=\"ptm\"><a href=\"".elgg_get_site_url()."nihcp_commons_credit_request/overview\">".elgg_echo("nihcp_commons_credit_request:ccreq:more")."</a></div>";

echo $content;