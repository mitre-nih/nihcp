<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 
$ia = elgg_set_ignore_access(true);
$request_guid = $vars['request_guid'];
$project_title = get_entity($request_guid)->project_title;
$decision = $vars['decision'];
$feedback = get_entity(\Nihcp\Entity\Feedback::getFeedback($request_guid));
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