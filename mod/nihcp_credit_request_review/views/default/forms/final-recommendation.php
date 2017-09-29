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


elgg_require_js('jquery');
elgg_require_js('crr');

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
    'id' => 'final_recommendation',
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
echo elgg_view('input/button', array(
    'id'=> 'crr-final-recommendation-submit-button',
    'class' => 'elgg-button elgg-button-submit crr-final-recommendation-submit-button',
    'value' => elgg_echo("nihcp_credit_request_review:crr:final_recommendation:save")));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Cancel'));
echo "</div>";
