<?php

$entity = elgg_extract('entity', $vars);

$class_text = elgg_echo("nihcp_commons_credit_request:ccreq:" . $entity->class);

echo "<h3>" . elgg_echo('nihcp_credit_request_review:crr:final_score:sv') . " for " . $class_text . "</h3>";

echo "<div class='pvs'>";
echo "<div><b>" . elgg_echo("nihcp_credit_request_review:crr:final_score:sbr") . "</b></div>";
echo "<div>" . round($entity->sbr) ."</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>" . elgg_echo("nihcp_credit_request_review:crr:final_score:sv") . "</b></div>";
echo "<div>" . round($entity->sv) ."</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>" . elgg_echo("nihcp_credit_request_review:crr:final_score:cf") . "</b></div>";
echo "<div>" . round($entity->cf) ."</div>";
echo "</div>";