<?php

$ia = elgg_set_ignore_access(true);
$request_guid = $vars['request_guid'];
$project_title = get_entity($request_guid)->project_title;
$final_recommendation_entity = get_entity(\Nihcp\Entity\FinalRecommendation::getFinalRecommendation($request_guid));
if (!empty($final_recommendation_entity)) {
    $final_recommendation = $final_recommendation_entity->final_recommendation;
    $final_recommendation_comments = $final_recommendation_entity->final_recommendation_comments;


}
elgg_set_ignore_access($ia);
echo "<h3>" . elgg_echo('nihcp_credit_request_review:crr:final_recommendation') . "</h3>";

if (!empty($request_guid)) {
    echo "<div class='ptm'>";
    echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_request_review/overview\">Back to Overview</a>";
    echo "</div>";
    echo "<div>";
    $project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request_guid";
    echo "Project : <a href=\"$project_url\">$project_title</a>";
    echo "</div>";
}

echo elgg_view('input/select', array(
    'name' => 'final_recommendation',
    'value' => $final_recommendation,
    'options_values' => array (
        '' => '',
        'Recommend' => \Nihcp\Entity\FinalRecommendation::RECOMMEND,
        'Down Select' => \Nihcp\Entity\FinalRecommendation::DOWNSELECT)
));
?>

<div class="ptm">
    <label for='final_recommendation_comments'>
        <?php echo "Comments (" . \Nihcp\Entity\FinalRecommendation::COMMENT_CHAR_LIMIT . " character limit)";?>
    </label>
    <br />
    <textarea name='final_recommendation_comments' id='final_recommendation_comments' maxlength='<?php echo \Nihcp\Entity\FinalRecommendation::COMMENT_CHAR_LIMIT;?>'><?php echo $final_recommendation_comments;?></textarea>
</div>

<?php
echo "<div>";
echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_guid));
echo elgg_view('input/submit', array('name' => 'action', 'value' => elgg_echo("nihcp_credit_request_review:crr:final_recommendation:complete")));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Cancel'));
echo "</div>";