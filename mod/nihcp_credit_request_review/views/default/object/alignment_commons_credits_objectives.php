<?php

$entity = elgg_extract('entity', $vars);

echo "<h3 class='pvm'>" . elgg_echo('nihcp_credit_request_review:crr:align_cc_obj') . " - ";

if ($entity->pass()) {
    echo "Pass";
} else {
    echo "Fail";
}

echo "</h3>";

echo "<div class='pvm'>"
    . ($entity->question1 ? elgg_view_icon('checkmark-hover') : elgg_view_icon('delete-hover'))
    . elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question1") . "</div>";
