<?php



elgg_require_js('pir');

$pir_guid = sanitize_int(get_input("pir_guid"));
$pir = get_entity($pir_guid);

if ($pir->status === "Submitted") {
    register_error(elgg_echo("error:404:content"));
    forward(REFERER);
}


echo "<div>";

echo "<div class='ptl'>";
echo "<label for='do_reuse'>"  . elgg_echo("nihcp_pir:do_reuse") . "</label> <200 chars>";
echo "<textarea id='do_reuse' name='do_reuse' maxlength='200'>$pir->do_reuse</textarea>";
echo "</div>";

echo "<div class='ptl'>";
echo "<label for='overall_issues'>" . elgg_echo("nihcp_pir:overall_issues") . "</label> <1000 chars>";
echo "<textarea id='overall_issues' name='overall_issues' maxlength='1000'>$pir->overall_issues</textarea>";
echo "</div>";

echo "</div>";

echo elgg_view('input/hidden', array('name' => 'pir_guid', 'id'=>'pir_guid', 'value'=>$pir_guid));
echo elgg_view('input/submit', array('name' => 'action', 'id' => 'pir-submit-button', 'value' => 'Submit'));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Cancel'));