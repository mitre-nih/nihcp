<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 
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