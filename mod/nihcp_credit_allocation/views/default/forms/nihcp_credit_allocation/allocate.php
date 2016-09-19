<?php

namespace Nihcp\Entity;

$request_guid = elgg_extract('request_guid', $vars, false);
$vendor_guid = elgg_extract('vendor_guid', $vars, false);

$request = get_entity($request_guid);
$vendor = pseudo_atomic_set_ignore_access(function($guid) {return get_entity($guid);}, $vendor_guid);

echo "<h2 class=\"mbl\">$request->project_title</h2>";

$unallocated = $request->getExpectedCostTotal();

$allocations = CommonsCreditAllocation::getAllocations($request->guid);
foreach($allocations as $_allocation) {
	$_allocated = $_allocation->credit_allocated ? $_allocation->credit_allocated : 0;
	$unallocated -= $_allocated;
}

echo "<div>";
echo "<label class=\"mrm\">".elgg_echo("nihcp_credit_allocation:request:unallocated_credit")."</label>";
echo "<span id=\"cca-unallocated-credit\">$unallocated</span>";
echo "</div>";

echo "<div>";
if($vendor) {
	$allocation = get_entity(CommonsCreditAllocation::getAllocationGUID($request_guid, $vendor_guid));
	echo "<label class=\"mrm\">Vendor</label>";
	echo $vendor->getDisplayName();
	echo elgg_view('input/hidden', array('name' => 'vendor_guid', 'id'=>'cca-vendor-guid', 'value'=>$vendor->getGUID()));
} else {
	$vendors = CommonsCreditVendor::getActiveVendors();
	$vendor_options = [];
	foreach ($vendors as $vendor) {
		$vendor_options[$vendor->getGUID()] = $vendor->getDisplayName();
	}
	foreach($allocations as $_allocation) {
		$_vendor = CommonsCreditVendor::getByVendorId($_allocation->vendor);
		unset($vendor_options[$_vendor->getGUID()]);
	}
	echo "<label class=\"mrm\">" . elgg_echo("nihcp_credit_allocation:request:select_vendor") . "</label>";
	echo elgg_view('input/select', array(
		'class' => 'cca-required',
		'name' => 'vendor_guid',
		'id' => 'cca-vendor-select',
		'options_values' => $vendor_options,
	));
}
echo "</div>";
echo "<div>";
echo "<label class=\"mrm\">".elgg_echo("nihcp_credit_allocation:request:requested_allocation")."</label>";
echo elgg_view('input/text', array(
	'class' => 'cca-required',
	'name' => 'credit_allocated',
	'id' => 'cca-credit-allocated',
	'value' => $allocation ? $allocation->credit_allocated : null));
echo "</div>";

echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request->getGUID()));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Submit', 'id' => 'cca-submit-button'));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Cancel'));
