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
use Nihcp\Manager\RoleManager;

elgg_require_js('jquery');
elgg_require_js('crr');

echo "<h3>Assign Domain Expert</h3>";

$ia = elgg_set_ignore_access(true);
$request_guid = $vars['request_guid'];
$project_title = get_entity($request_guid)->project_title;


if (!empty($request_guid)) {
    echo "<div>";
    echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_request_review/risk-benefit-score-overview/$request_guid\">Back to Risk/Benefit Score Overview</a>";
    echo "</div>";
    echo "<div>";
    $project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request_guid";
    echo "Project : <a href=\"$project_url\">$project_title</a>";
    echo "</div>";
}

$domain_expert_role = RoleManager::getRoleByName(RoleManager::DOMAIN_EXPERT);
$domain_experts = $domain_expert_role->getMembers(array("limit"=>0));


usort($domain_experts, function($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return strcasecmp($a->getDisplayName(), $b->getDisplayName());
});

$already_assigned_experts = RiskBenefitScore::getAssignedDomainExperts($request_guid);

$already_assigned_experts_guids = array();
foreach($already_assigned_experts as $expert) {
    $already_assigned_experts_guids[] = $expert->getGUID();
}

$content = "
    <table class=\"elgg-table\">
         <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th># of D.O.s</th>
            <th>Assign</th>
        </tr>
    ";


foreach ($domain_experts as $de) {

    $content .= "<tr id='" . $de->getGUID(). "'>";
    $content .= "<td>" . $de->getGUID() . "</td>";
    $content .= "<td>" . $de->getDisplayName() . "</td>";
    $content .= "<td>" . $de->email. "</td>";
    $content .= "<td>" . count(RiskBenefitScore::getRiskBenefitScoreEntitiesForDomainExpert($de->getGUID())) . "</td>";

    if (RiskBenefitScore::isAllAssignedCompleted($request_guid, $de->getGUID())) {
        $content .= "<td>Completed</td>";
    } else {

        if (in_array($de->getGUID(), $already_assigned_experts_guids)) {
            $content .= "<td>" . elgg_view('input/button', array(
                    'value' => 'Unassign',
                    'class' => 'elgg-button elgg-button-submit crr-de-unassign',
                    'id' => $request_guid
                )) . "</td>";
        } else {
            $content .= "<td>" . "<button type='submit' name='assign' class='elgg-button elgg-button-submit crr-de-assign-button' value='" . $de->getGUID() . "'>Assign</button>" . "</td>";
        }
    }
    $content .= "</tr>";
}


elgg_set_ignore_access($ia);
$content .= "
    </table>
    ";
$content .= elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_guid));
echo $content;
