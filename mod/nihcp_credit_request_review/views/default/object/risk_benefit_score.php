<?php

use Nihcp\Entity\RiskBenefitScore;

$entity = elgg_extract('entity', $vars);
$do_class = $entity->class;

echo "<h3>Benefit and Risk Score for " . elgg_echo("nihcp_commons_credit_request:ccreq:$do_class") . "</h3>";

echo "<div class='pvs'>";
echo "<div><b>Digital Object Class</b></div>";
echo "<div>" . elgg_echo("nihcp_commons_credit_request:ccreq:$entity->class") . "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Reviewer</b></div>";
echo "<div>" . RiskBenefitScore::getDomainExpertForRiskBenefitScore($entity->guid)->getDisplayName() . "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Mean Benefit Score</b></div>";
echo "<div>" . $entity->benefit_score . "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Mean Risk Score</b></div>";
echo "<div>" . $entity->risk_score . "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Comments</b></div>";
echo "<div>" . $entity->comments . "</div>";
echo "</div>";