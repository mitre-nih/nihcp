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