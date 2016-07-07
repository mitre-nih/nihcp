<?php
elgg_require_js('cycle');

if (isset($vars['cycle_guid'])) {
	$cycle_guid = $vars['cycle_guid'];
	$cycle = get_entity($cycle_guid);
	$application_start = $cycle->start;
	$application_finish = $cycle->finish;
	$stratification_threshold = $cycle->threshold;
}

echo "<h3 class=\"mbl\">".elgg_echo('item:object:commonscreditcycle')."</h3>";

echo "<div class=\"required-field mbm\"><div class=\"mbs\"><label for ='application_start'>" . elgg_echo('nihcp_commons_credit_request:cycle:start') . "</label></div>";
echo elgg_view('input/date', array(
	'name' => 'application_start',
	'value' => $application_start,
));
echo "</div>";

echo "<div class=\"required-field mbm\"><div class=\"mbs\"><label for ='application_finish'>" . elgg_echo('nihcp_commons_credit_request:cycle:finish') . "</label></div>";
echo elgg_view('input/date', array(
	'name' => 'application_finish',
	'value' => $application_finish,
));
echo "</div>";

echo "<div class=\"required-field mbm\"><div class=\"mbs\"><label for ='stratification_threshold'>" . elgg_echo('nihcp_commons_credit_request:cycle:threshold') . "</label></div>";
echo elgg_view('input/text', array(
	'id' => 'ccr-cycle-stratification-threshold-input',
	'name' => 'stratification_threshold',
	'value' => $stratification_threshold,
));
echo "</div>";

echo "<div>";
echo elgg_view('input/hidden', array('name'=>'cycle_guid', 'value' => $cycle_guid));
echo elgg_view('input/submit', array('id' => 'ccr-cycle-save-button', 'name' => 'action', 'value' => 'Save'));
echo elgg_view('input/submit', array('id' => 'ccr-cycle-discard-button', 'name' => 'action', 'value' => 'Discard Changes'));
echo "</div>";