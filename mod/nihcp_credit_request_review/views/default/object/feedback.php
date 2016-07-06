<?php

$entity = elgg_extract('entity', $vars);
echo "<h3 class='pvm'>" . elgg_echo('nihcp_commons_credit_request:ccreq:feedback') . "</h3>";

echo "<div class='pvs'>";
echo "<div><b>Decision</b></div>";
echo "<div>$entity->decision</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Comments</b></div>";
echo "<div>$entity->comments</div>";
echo "</div>";