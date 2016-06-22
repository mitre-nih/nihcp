<?php

use \Nihcp\Entity\CommonsCreditRequest;

elgg_require_js('confirm');

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
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Submit', 'id' => 'ccreq-submit-button', 'class' => 'disabled', 'disabled'=>true));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Cancel'));