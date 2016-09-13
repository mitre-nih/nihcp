<?php

namespace Nihcp\Entity;

$request_guid = elgg_extract('request_guid', $vars, false);
$vendor_guid = elgg_extract('vendor_guid', $vars, false);

$request = get_entity($request_guid);

$content = "<h3 class ='pvm'>$request->project_title</h3>";

$ia = elgg_set_ignore_access();
$entities = CommonsCreditAllocation::getAllocationBalanceHistory($request_guid, $vendor_guid);



$content .= "<table class=\"elgg-table cca-overview-table\">";
$content .=	"<tr>";
$content .= "<th><b>Initial Credits ($)</b></th>";
$content .= "<th><b>Credit Change ($)</b></th>";
$content .= "<th><b>Remaining Credits ($)</b></th>";
$content .= "<th><b>Date Updated</b></th>";
$content .= "</tr>";

// record the changes of credit balance between updates for display in the table
$credit_changes = array();
for ($i = count($entities)-2; $i >= 0; $i--) {
    $credit_changes[$i] = $entities[$i]->credit_remaining - $entities[$i+1]->credit_remaining;
}

$counter = 0;
foreach ($entities as $allocation) {
    $content .=	"<tr>";
    $content .=	"<td>$allocation->credit_allocated</td>";
    $content .=	"<td>$credit_changes[$counter]</td>";
    $counter++;
    $content .=	"<td>$allocation->credit_remaining</td>";
    $epoch = $allocation->getTimeUpdated();
    $dt = new \DateTime("@$epoch");
    $last_updated = $dt->format('Y-m-d H:i:s');
    $content .= "<td>$last_updated</td>";
    $content .=	"</tr>";
}

elgg_set_ignore_access($ia);

$content .= "</table>";

echo $content;