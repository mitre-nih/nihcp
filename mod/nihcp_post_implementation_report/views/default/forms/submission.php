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