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
$rb_guid = $vars['rb_guid'];
$project_title = get_entity($request_guid)->project_title;

$rb_entity = get_entity($rb_guid);

if (isset($rb_entity)) {
    $benefit_score = $rb_entity->benefit_score;
    $risk_score = $rb_entity->risk_score;
    $score_comments = $rb_entity->comments;
    $do_class = $rb_entity->class;
}

elgg_set_ignore_access($ia);

echo "<h3>Benefit and Risk Score for " . elgg_echo("nihcp_commons_credit_request:ccreq:$do_class") . "</h3>";

if (!empty($request_guid)) {

    echo "<div>";
    $project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request_guid";
    echo "Project : <a href=\"$project_url\">$project_title</a>";
    echo "</div>";
}

echo "<div>";
echo "<div><label for ='benefit_score'>" . elgg_echo('nihcp_credit_request_review:crr:benefit_score') . "</label></div>";
echo elgg_view('input/select', array(
    'name' => 'benefit_score',
    'value' => $benefit_score,
    'options_values' => array (
        '' => '',
        'No Benefit' => 'No Benefit',
        'Low' => 'Low',
        'Medium' => 'Medium',
        'High' => 'High',
        'Extremely High' => 'Extremely High')
));
echo "</div>";

echo "<div>";
echo "<div><label for ='risk_score'>" . elgg_echo('nihcp_credit_request_review:crr:risk_score') . "</label></div>";
echo elgg_view('input/select', array(
    'name' => 'risk_score',
    'value' => $risk_score,
    'options_values' => array (
        '' => '',
        'Low' => 'Low',
        'Medium' => 'Medium',
        'High' => 'High',
        'Extremely High' => 'Extremely High')
));
echo "</div>";

?>

    <div>
        <label for='score_comments'>
            <?php echo "Comments (" . \Nihcp\Entity\RiskBenefitScore::COMMENT_CHAR_LIMIT . " character limit)";?>
        </label>
        <br />
        <textarea name='score_comments' id='score_comments' maxlength="<?php echo \Nihcp\Entity\RiskBenefitScore::COMMENT_CHAR_LIMIT?>"><?php echo $score_comments;?></textarea>
    </div>

<?php
echo "<div>";
echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_guid));
echo elgg_view('input/hidden', array('name' => 'rb_guid', 'id'=>'rb_guid', 'value'=>$rb_guid));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Save'));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Discard Changes'));
echo "</div>";