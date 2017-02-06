<?php

$ia = elgg_set_ignore_access(true);
$request_guid = get_input('request_guid');
$req = get_entity($request_guid);
elgg_set_ignore_access($ia);

//echo "<h3>Benefit and Risk Score for " . elgg_echo("nihcp_commons_credit_request:ccreq:$do_class") . "</h3>";

/*if (!empty($request_guid)) {

    echo "<div>";
    $project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request_guid";
    echo "Project : <a href=\"$project_url\">$project_title</a>";
    echo "</div>";
}*/

echo "<div>";
echo "<div><label for ='is_active'>" . elgg_echo('nihcp_credit_request_review:crr:validate:is_active') . "</label></div>";
echo elgg_view('input/radio', array(
    'name' => 'is_active',
    'value' => $req->is_active,
    'options' => array (
        elgg_echo('option:yes') => 'yes',
        elgg_echo('option:no') => 'no'
    ),
));


echo "</div>";

echo "<div>";
echo "<div><label for ='is_active_comment'>" . elgg_echo('nihcp_credit_request_review:crr:validate:is_active_comment') . "</label></div>";?>
<textarea name='is_active_comment' id='is_active_comment' required='true' maxlength='200'><?php echo $req->is_active_comment;?></textarea>

<?php
echo "</div>";

?>



<?php
echo "<div>";
echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_guid));
echo elgg_view('input/hidden', array('name' => 'rb_guid', 'id'=>'rb_guid', 'value'=>$rb_guid));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Save'));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Discard Changes'));
echo "</div>";