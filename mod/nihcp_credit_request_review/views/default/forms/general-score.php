<?php

namespace Nihcp\Entity;

elgg_require_js('jquery');
elgg_require_js('autoNumeric');
elgg_require_js('crr');
$ia = elgg_set_ignore_access(true);
$request_guid = $vars['request_guid'];
$project_title = get_entity($request_guid)->project_title;


$review_class = get_input('review_class');
$review_class_text = '';
$gs_guid = null;
switch($review_class) {
    case 'datasets':
        $review_class_text = 'Datasets';
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreDatasetsFromRequestGuid($request_guid);
        break;
    case 'applications_tools':
        $review_class_text = 'Applications/Tools';
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreAppsToolsFromRequestGuid($request_guid);
        break;
    case 'workflows':
        $review_class_text = 'Workflows';
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreWorkflowsFromRequestGuid($request_guid);
        break;
    default:
        break;
}

$gs_entity = get_entity($gs_guid);
if (isset($gs_entity)) {
    $num_digital_objects = $gs_entity->num_digital_objects;
    $general_score = $gs_entity->general_score;
    $general_score_comments = $gs_entity->general_score_comments;
}
elgg_set_ignore_access($ia);

echo "<h3>" . elgg_echo('nihcp_credit_request_review:crr:general_score') . " for " . $review_class_text . "</h3>";
echo "<br>";

if (!empty($request_guid)) {
    echo "<div>";
    echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_request_review/overview\">Back to Overview</a>";
    echo "</div>";
    echo "<div>";
    $project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request_guid";
    echo "Project : <a href=\"$project_url\">$project_title</a>";
    echo "</div>";
}

echo "<div>";
echo "<label for ='num_digital_objects'>" . elgg_echo("nihcp_credit_request_review:crr:general_score:number_of_dos") . "</label>";
echo "<div>";
echo elgg_view('input/number', array(
    'id' => 'num_digital_objects',
    'name' => 'num_digital_objects',
    'value' => $num_digital_objects,
));
echo "</div>";
echo "</div>";

echo "<div>";
echo "<label for ='general_score'>" . elgg_echo("nihcp_credit_request_review:crr:mean_general_score") . " " . elgg_echo("nihcp_credit_request_review:crr:general_score:range") . "</label>";
echo "<div>";
echo elgg_view('input/text', array(
    'id' => 'general_score',
    'name' => 'general_score',
    'value' => $general_score,
));
echo "</div>";
echo "</div>";
?>

<div>
    <label for='general_score_comments'>
        <?php echo "Comments (" . GeneralScore::COMMENT_CHAR_LIMIT . " character limit)";?>
    </label>
    <br />
    <textarea name='general_score_comments' id='general_score_comments' maxlength="<?php echo GeneralScore::COMMENT_CHAR_LIMIT;?>"><?php echo $general_score_comments;?></textarea>
</div>

<?php
echo "<div>";
echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_guid));
echo elgg_view('input/hidden', array('name' => 'review_class', 'id'=>'review_class', 'value'=>$review_class));
echo elgg_view('input/hidden', array('name' => 'gs_guid', 'id'=>'gs_guid', 'value'=>$gs_guid));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Save'));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Discard Changes'));
echo "</div>";