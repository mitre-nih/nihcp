<?php

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
            <th>Assign</th>
        </tr>
    ";


foreach ($domain_experts as $de) {

    $content .= "<tr id='" . $de->getGUID(). "'>";
    $content .= "<td>" . $de->getGUID() . "</td>";
    $content .= "<td>" . $de->getDisplayName() . "</td>";
    $content .= "<td>" . $de->email. "</td>";

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