<?php

$entity = elgg_extract('entity', $vars);

echo "<h3 class='pvm'>" . elgg_echo('nihcp_credit_request_review:crr:align_cc_obj') . " - ";

if ($entity->pass()) {
    echo "Pass";
} else {
    echo "Fail";
}

echo "</h3>";

echo "<div class='pvs'>"
    . ($entity->question1 ? elgg_view_icon('checkmark-hover') : elgg_view_icon('delete-hover'))
    . elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question1") . "</div>";
echo "<div class='pvs'>"
    . ($entity->question2 ? elgg_view_icon('checkmark-hover') : elgg_view_icon('delete-hover'))
    . elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question2") . "</div>";
echo "<div class='pvs'>"
    . ($entity->question3 ? elgg_view_icon('checkmark-hover') : elgg_view_icon('delete-hover'))
    . elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question3") . "</div>";
echo "<div class='pvs'>"
    . ($entity->question4 ? elgg_view_icon('checkmark-hover') : elgg_view_icon('delete-hover'))
    . elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question4") . "</div>";