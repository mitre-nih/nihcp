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
 


$entity = elgg_extract('entity', $vars);
$class= $entity->digital_object_class;

echo "<h3 class='pvm'>" . elgg_echo('nihcp_credit_request_review:crr:general_score') . " for " . elgg_echo("nihcp_commons_credit_request:ccreq:$class") . "</h3>";

echo "<div class='pvs'>";
echo "<div><b>" . elgg_echo("nihcp_credit_request_review:crr:general_score:number_of_dos") . "</b></div>";
echo "<div>" . $entity->num_digital_objects . "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>" . elgg_echo("nihcp_credit_request_review:crr:mean_general_score") . "</b></div>";
echo "<div>" . round($entity->general_score) . "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Comments</b></div>";
echo "<div>" . (empty($entity->general_score_comments) ? "N/A" : $entity->general_score_comments) . "</div>";
echo "</div>";
