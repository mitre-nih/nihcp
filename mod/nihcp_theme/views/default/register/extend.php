<?php

elgg_require_js('jquery');

echo "<div>";

echo elgg_echo('nihcp_theme:password_requirements', array(MIN_PASSWORD_LENGTH));

echo "</div>";


echo "<div>" ;
echo "<label>" . elgg_echo('nihcp_theme:how_did_you_hear_about_us') . "</label>";
echo "<div>" ;

$options = ['word_of_mouth', 'email', 'webinar_seminar', 'twitter', 'facebook', 'conference', 'linkedin', 'nih_website', 'other'];
$option_values = array();
foreach ($options as $option) {
    $option_values[$option] = elgg_echo("nihcp_theme:how_did_you_hear_about_us:option:$option");
}

echo elgg_view('input/select', array(
    'id' => 'how_did_you_hear_about_us',
    'name' => 'how_did_you_hear_about_us',
    'options_values' => $option_values
));
echo "</div>";
echo "</div>";

echo "<div>" ;
echo "<label>" . elgg_echo('nihcp_theme:how_did_you_hear_about_us:other') . "</label>";
echo "<div>" ;
echo "<textarea id='how_did_you_hear_about_us_other' name='how_did_you_hear_about_us_other' maxlength='" . HOW_DID_YOU_HEAR_ABOUT_US_OTHER_MAXLENGTH . "'></textarea>";
echo "</div>";
echo "</div>";