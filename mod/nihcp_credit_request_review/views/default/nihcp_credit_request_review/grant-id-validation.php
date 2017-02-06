<?php

$ia = elgg_set_ignore_access(true);
$request_guid = get_input('request_guid');
$req = get_entity($request_guid);
elgg_set_ignore_access($ia);

echo "<div>";
echo "<div><label for ='is_active'>" . elgg_echo('nihcp_credit_request_review:crr:validate:is_active') . "</label></div>";
echo $req->is_active;
echo "</div>";

echo "<div>";
echo "<div><label for ='is_active_comment'>" . elgg_echo('nihcp_credit_request_review:crr:validate:is_active_comment') . "</label></div>";
echo $req->is_active_comment;

echo "</div>";

?>