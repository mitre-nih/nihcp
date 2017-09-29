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
