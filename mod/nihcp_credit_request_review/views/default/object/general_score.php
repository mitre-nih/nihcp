<?php

$entity = elgg_extract('entity', $vars);
$class= $entity->digital_object_class;

echo "<h3 class='pvm'>" . elgg_echo('nihcp_credit_request_review:crr:general_score') . " for " . elgg_echo("nihcp_commons_credit_request:ccreq:$class") . "</h3>";

echo "<div class='pvs'>";
echo "<div><b>" . elgg_echo("nihcp_credit_request_review:crr:general_score:number_of_dos") . "</b></div>";
echo "<div>" . $entity->num_digital_objects . "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>" . elgg_echo("nihcp_credit_request_review:crr:mean_general_score") . "</b></div>";
echo "<div>" . round($entity->general_score) . "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Comments</b></div>";
echo "<div>" . (empty($entity->general_score_comments) ? "N/A" : $entity->general_score_comments) . "</div>";
echo "</div>";