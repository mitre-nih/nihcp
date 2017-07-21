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
 


use Nihcp\Entity\CommonsCreditRequest;
use Nihcp\Entity\FinalScore;
use Nihcp\Entity\FinalRecommendation;
use Nihcp\Entity\CommonsCreditVendor;
use Nihcp\Entity\CommonsCreditAllocation;

nihcp_triage_coordinator_gatekeeper();

$cycle_guid = get_input('nihcp-ccreq-cycle-select');

$ia = elgg_set_ignore_access();


if (empty($cycle_guid)) {
	$ccreqs = CommonsCreditRequest::getAll();
	$cycle_view = "All_Cycles";
} else {
	$ccreqs = CommonsCreditRequest::getByCycle($cycle_guid);
	$cycle = get_entity($cycle_guid);
	$cycle_view = "Cycle" . $cycle->start."-".$cycle->finish;
}

$vendors = CommonsCreditVendor::getAllVendors();
$vendor_ids = array();
foreach ($vendors as $vendor) {
	$vendor_ids[] = $vendor->vendor_id;
}
asort($vendor_ids);

$ccreq_rows = [];

// Project Name, CCREQ ID, Credit ($), Investigator, Email, MITRE Triage Score, MITRE Triage Recommendation, NIH Decision, VendorName1 Credit ($), VendorName2 Credit ($), ...
foreach($ccreqs as $ccreq) {
	$investigator = $ccreq->getOwnerEntity();

	$final_recommendation = get_entity(FinalRecommendation::getFinalRecommendation($ccreq->guid));
	if (empty($final_recommendation)) {
		$triage_recommendation = "";
	} else {
		$triage_recommendation = $final_recommendation->final_recommendation;
	}

	$nih_decision = "";
	if ($ccreq->status === CommonsCreditRequest::APPROVED_STATUS || $ccreq->status === CommonsCreditRequest::DENIED_STATUS) {
		$nih_decision = $ccreq->status;
	}


	// get the vendor allocations
	$vendor_allocations = array();
	foreach($vendor_ids as $vendor_id) {
		$vendor_allocations[$vendor_id] = 0;
	}
	$ccreq_allocations = CommonsCreditAllocation::getAllocations($ccreq->guid);
	foreach ($ccreq_allocations as $ccreq_allocation) {
		$vendor_allocations[$ccreq_allocation->vendor] = $ccreq_allocation->credit_allocated;
	}

	$ccreq_row = [$ccreq->project_title, $ccreq->getRequestId(), $ccreq->getExpectedCostTotal(), $investigator->getDisplayName(), $investigator->email, FinalScore::calculateROI($ccreq->guid), $triage_recommendation, $nih_decision];

	foreach ($vendor_allocations as $vendor_allocation) {
		$ccreq_row[] = $vendor_allocation;
	}

	$ccreq_rows[] = $ccreq_row;

}

elgg_set_ignore_access($ia);

$date = date('Ymd');

$fo = new ElggFile();

$title = $cycle_view. "_-_$date";
$fo->setFilename("$title.csv");
$fo->originalfilename = $fo->getFilename();
$fo->title = $title;
$fo->setMimeType("text/csv");

$fo->save();

$fh = $fo->open('write');

function fputcsv_eol($fp, $array, $eol) {
	fputcsv($fp, $array);
	if("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
		fwrite($fp, $eol);
	}
}

$newline = "\n";

$header_fields = ['Project Name', 'CCREQ ID', 'Credit ($)', 'Investigator', 'Email', 'MITRE Triage Score', 'MITRE Triage Recommendation', 'NIH Decision'];
foreach($vendor_ids as $vendor_id) {
	$header_fields[] = $vendor_id;
}

fputcsv_eol($fh, $header_fields, $newline);

$fh = $fo->open('append');
foreach($ccreq_rows as $row) {
	fputcsv_eol($fh, $row, $newline);
}

$fo->close();

forward(str_replace('view', 'download', $fo->getURL()));
