<?php

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;

elgg_require_js('confirm');

$ia = elgg_set_ignore_access();
$current_request = $vars['current_request'];

echo elgg_view_entity($current_request);

echo "<div>";
echo elgg_view('input/checkbox', array(
		'class' => 'ccreq-required',
		'name' => 'agreement_biocaddie',
		'id' => 'agreement_biocaddie',
		'label' => elgg_echo("nihcp_commons_credit_request:ccreq:agreement_biocaddie")));
echo "</div>";
echo "<div>";
echo elgg_view('input/checkbox', array(
	'class' => 'ccreq-required',
    'name' => 'terms_and_conditions',
	'id' => 'ccreq-tandc',
    'label' => elgg_echo("nihcp_commons_credit_request:ccreq:agreement_terms_and_conditions")));
echo "</div>";
echo "<div>";
echo elgg_view('input/checkbox', array(
		'class' => 'ccreq-required',
		'name' => 'agreement_nih_policies_digital_objects',
		'id' => 'agreement_nih_policies_digital_objects',
		'label' => elgg_echo("nihcp_commons_credit_request:ccreq:agreement_nih_policies_digital_objects")));
echo "</div>";

echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$current_request->guid));

if ($current_request->isOwner(elgg_get_logged_in_user_guid())) {
	echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Submit', 'id' => 'ccreq-submit-button', 'class' => 'phl disabled', 'disabled' => true));
	if (!empty(CommonsCreditRequestDelegation::getDelegateForCCREQ($current_request->getGUID()))) {
		echo elgg_view('input/submit', array('name' => 'action', 'class'=>'elgg-button-submit confirmation-required', 'value' => 'Give control back to delegate'));
	}
} else { // is delegate
	echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Submit for PI Review', 'id' => 'ccreq-submit-button', 'class' => 'disabled confirmation-required', 'disabled' => true));
}
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Cancel'));

elgg_set_ignore_access($ia);