<?php

$entity = elgg_extract('entity', $vars);

echo "<h3>Final Review</h3>";

echo "<div class='pvs'>";
echo "<div><b>" . elgg_echo('nihcp_credit_request_review:crr:final_recommendation') . "</b></div>";
echo "<div>$entity->final_recommendation</div>";
echo "</div>";


echo "<div class='pvs'>";
echo "<div><b>Comments</b></div>";
echo "<div>$entity->final_recommendation_comments</div>";
echo "</div>";