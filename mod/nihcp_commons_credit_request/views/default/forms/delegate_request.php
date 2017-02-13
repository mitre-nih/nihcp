<?php

use \Nihcp\Entity\CommonsCreditRequestDelegation;

elgg_require_js('delegate');

$ia = elgg_set_ignore_access();
$delegation = $vars['delegation'];
$ccreq = CommonsCreditRequestDelegation::getCCREQForDelegation($delegation->getGUID());

echo "<div>";
echo "<h4>Project Title: $ccreq->project_title</h4>";
echo "</div>";

echo "<div id='delegate-instructions' class='pvm'>";
echo elgg_echo('nihcp_commons_credit_request:delegate:instructions');
echo "</div>";


echo "<div id='delegate-request-description' class='pvm'>";
echo "<b>" . elgg_echo('nihcp_commons_credit_request:delegate:request:description', array($delegation->getOwnerEntity()->getDisplayName(), $ccreq->project_title)). "</b>";
echo "</div>";

echo "<div>";
echo elgg_view('input/hidden', array('name' => 'delegation_guid', 'id'=>'delegation_guid', 'value'=>$delegation->guid));
echo elgg_view('input/submit', array('id' => 'ccreq-accept-delegate-button', 'class'=>'elgg-button-submit confirmation-required', 'name' => 'action', 'value' => elgg_echo("nihcp_commons_credit_request:delegate:request:accept")));
echo elgg_view('input/submit', array('id' => 'ccreq-decline-delegate-button', 'class'=>'elgg-button-submit confirmation-required', 'name' => 'action', 'value' => elgg_echo("nihcp_commons_credit_request:delegate:request:decline")));

echo "</div>";

elgg_set_ignore_access($ia);