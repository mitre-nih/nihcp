<?php

$ia = elgg_set_ignore_access(true);
$request_guid = $vars['request_guid'];
$project_title = get_entity($request_guid)->project_title;
$decision = $vars['decision'];
$feedback = \Nihcp\Entity\Feedback::getFeedback($request_guid);
if (!empty($feedback)) {
	$feedback_comments = $feedback->comments;
}

elgg_set_ignore_access($ia);
echo "<h3>" . elgg_echo('nihcp_commons_credit_request:ccreq:feedback') . "</h3>";

if (!empty($request_guid)) {
	echo "<div class='ptm'>";
	echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_request_review/overview\">Back to Overview</a>";
	echo "</div>";
	echo "<div>";
	$project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request_guid";
	echo "Project : <a href=\"$project_url\">$project_title</a>";
	echo "</div>";
}

echo "<div class='ptm'>";
echo "<div><b>Decision</b></div>";
echo "<div>" . $decision . "</div>";
echo "</div>";

?>

	<div class="ptm">
		<label for='feedback_comments'>
			<?php echo "Comments (" . \Nihcp\Entity\Feedback::COMMENT_CHAR_LIMIT . " character limit)";?>
		</label>
		<br />
		<textarea name='feedback_comments' id='feedback_comments' maxlength='<?php echo \Nihcp\Entity\Feedback::COMMENT_CHAR_LIMIT;?>'><?php echo $feedback_comments;?></textarea>
	</div>

<?php
echo "<div>";
echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_guid));
echo elgg_view('input/hidden', array('name' => 'decision', 'id'=>'decision', 'value'=>$decision));
echo elgg_view('input/button', array(
	'id' => 'crr-final-decision-submit-button',
	'name' => 'action',
	'class' => 'elgg-button elgg-button-submit crr-final-decision-submit-button',
	'value' => elgg_echo("nihcp_credit_request_review:crr:decision:save")));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Cancel'));
echo "</div>";