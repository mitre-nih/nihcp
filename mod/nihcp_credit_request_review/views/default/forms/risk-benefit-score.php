<?php

$ia = elgg_set_ignore_access(true);
$request_guid = $vars['request_guid'];
$rb_guid = $vars['rb_guid'];
$project_title = get_entity($request_guid)->project_title;

$rb_entity = get_entity($rb_guid);

if (isset($rb_entity)) {
    $benefit_score = $rb_entity->benefit_score;
    $risk_score = $rb_entity->risk_score;
    $score_comments = $rb_entity->comments;
}

elgg_set_ignore_access($ia);


if (!empty($request_guid)) {

    echo "<div>";
    echo "Project : <a href=\"" . elgg_get_site_url() . "nihcp_commons_credit_request/request/$request_guid\">$project_title</a>";
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