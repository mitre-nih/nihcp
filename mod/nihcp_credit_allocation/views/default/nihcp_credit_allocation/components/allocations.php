<?php
namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;

$review_mode = elgg_extract('review_mode', $vars, false);

$ia = elgg_get_ignore_access();

if($review_mode) {
	$ia = elgg_set_ignore_access();
}

$requests = elgg_extract('requests', $vars, true);
$full_view = elgg_extract('full_view', $vars, true);

$show_action = ((count($requests) > 1 ? true : !CommonsCreditAllocation::isAllocated($requests[0]->guid)) && !$review_mode) || elgg_is_admin_logged_in();

$content = '';

// TCs, DEs, and NAs all have different sets of columns of this table

$num_allocations = 0;
if($requests) {
	$content .= "<table class=\"elgg-table cca-overview-table\">";
	$content .=	"<tr>";
	$content .= "<th class='project-name'><b>Project Name</b></th>";
	if ($full_view) {
		$content .= "<th><b>CCREQ ID</b></th>";
		if($review_mode) {
			$content .= "<th><b>Investigator</b></th>";
		}
	}
	$content .= "<th><b>Vendor</b></th>";
	if ($full_view) {
		$content .= "<th><b>Submission Date</b></th>";
	}
	$content .= "<th><b>Allocation Status</b></th>";
	$content .= "<th><b>Credit Allocated ($)</b></th>";
	$content .= "<th><b>Credit Remaining ($)</b></th>";

	if($full_view) {
		$content .= "<th><b>Last Updated</b></th>";
		if($show_action) {
			$content .= "<th class=\"cca-action\"><b>Action</b></th>";
		}
	}
	$content .= "</tr>";

	foreach ($requests as $request) {
		$ia = elgg_set_ignore_access();
		if ($request->status === CommonsCreditRequest::APPROVED_STATUS) {
			$requester = get_entity($request->owner_guid)->getDisplayName();
			$rel_path = "nihcp_commons_credit_request/request/";
			if($review_mode) {
				$rel_path = "nihcp_credit_request_review/review/";
			}
			$project_url = elgg_get_site_url() . $rel_path .$request->guid;
			$ccreq_id = $request->getRequestId();

			$allocations = CommonsCreditAllocation::getAllocations($request->guid);
			$num_allocations += count($allocations);
			foreach($allocations as $allocation) {
				$row = "<tr id=\"$request->guid\">";
				$row .= "<td><a href=\"$project_url\">$request->project_title</a></td>";

				if ($full_view) {
					$row .= "<td>$ccreq_id</td>";
					if($review_mode) {
						$row .= "<td>$requester</td>";
					}
				}

				$vendor_id = $allocation->vendor;
				$vendor = CommonsCreditVendor::getByVendorId($vendor_id);
				$row .= "<td>{$vendor->getDisplayName()}</td>";

				if ($full_view) {
					$row .= "<td>$request->submission_date</td>";
				}
				$row .= "<td class=\"cca-allocation-status\">$allocation->status</td>";
				$credit_allocated = $allocation->credit_allocated;
				$request_guid = $request->getGUID();
				$vendor_guid = $vendor->getGUID();
				$allocation_url = $allocation->status === CommonsCreditAllocation::STAGED_STATUS ?
									elgg_get_site_url() . "nihcp_credit_allocation/allocate/$request_guid/$vendor_guid" :
									elgg_get_site_url() . "nihcp_credit_allocation/balance_history/$request_guid/$vendor_guid";
				$row .= "<td><a class=\"cca-allocate-link\" data-request-guid=$request_guid data-vendor-guid=$vendor_guid href=\"$allocation_url\">$credit_allocated</a></td>";

				$credit_remaining = $allocation->credit_remaining;
				$row .= "<td>$credit_remaining</td>";
				elgg_set_ignore_access($ia);
				if ($full_view) {
					$epoch = $allocation->getTimeUpdated();
					$dt = new \DateTime("@$epoch");
					$last_updated = $dt->format('Y-m-d H:i:s');
					$row .= "<td>$last_updated</td>";

					if(($allocation->status == CommonsCreditAllocation::STAGED_STATUS && !$review_mode) || elgg_is_admin_logged_in()) {
						$delete_button = elgg_view('input/button', array(
							'value' => 'Delete',
							'data-request-guid' => $request_guid,
							'data-vendor-guid' => $vendor_guid,
							'class' => 'elgg-button-cancel cca-delete-button'
						));
						$row .="<td class=\"cca-action\">$delete_button</td>";
					}
				}

				$row .= "</tr>";
				$content .= $row;
			}
		}
	}


	$content .= "</table>";
}

elgg_set_ignore_access($ia);

if($num_allocations === 0) {
	$content = elgg_echo('nihcp_credit_allocation:allocations:none');
}

echo $content;