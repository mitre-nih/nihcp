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
use \Nihcp\Entity\PirFormField;
use \Nihcp\Entity\PostImplementationReport;

elgg_require_js("pir");
elgg_require_js('autoNumeric');

$pir_guid = sanitize_int(get_input("pir_guid"));
$ccreq_guid = PostImplementationReport::getCcreqGuidFromPirGuid($pir_guid);
$ccreq = get_entity($ccreq_guid);

$dor_guid = sanitize_int(get_input("dor_guid"));
if ($dor_guid) {
    $dor = get_entity($dor_guid);
}

$form_fields = PostImplementationReport::getPirDigitalObjectReportFormFields();

echo "<div>";

echo "<div class='pvl'>";
echo "<div class='pbs'><b>Project Name: </b>" . $ccreq->project_title . "</div>";
echo "<div class='pbs'><b>CCREQ ID: </b>" . $ccreq->getRequestId() ."</div>";
echo "</div>";


$all_filled = true;
echo "<div class='pvl'>";
foreach($form_fields as $field) {
    echo "<div class='pvs'>";

    $field_name =  $field->name;
    $value = '';
    if (!empty($dor)) {
        $value = $dor->$field_name;
        // check to see if all form fields are filled out (except for file upload)
        if($field_name !== 'use_agreements_file' && ($value === null || $value === '')) {
            $all_filled = false;
        }
    } else {
        $all_filled = false;
    }

    switch ($field->input_type) {
        case "textarea":
            echo "<label for='$field->name'>" . elgg_echo("nihcp_pir:do_form:" . $field_name) . "</label> ";
            if ($field->alt_text) {
                echo "($field->alt_text) ";
            }
            echo "<" . $field->character_limit . " chars>";

            echo "<textarea name='$field->name' id='$field->name' class='required-field' maxlength='$field->character_limit' required='true'>$value</textarea>";
            break;
        case "number":
            echo "<span class='prm'>";
            echo "<label for='$field->name'>" . elgg_echo("nihcp_pir:do_form:" . $field_name) . "</label>";
            echo "</span>";
            echo elgg_view('input/text', array(
                'label' => $field_name,
                'id' => $field_name,
                'name' => $field_name,
                'value' => empty($value) ? 0 : $value,
                'class' => ['required-field', 'pir-form-number'],
                'options_values' => $field->input_vars,
                'required' => 'true'
            ));
            break;
        case "cost":
            echo "<span class='prm'>";
            echo "<label for='$field->name'>" . elgg_echo("nihcp_pir:do_form:" . $field_name) . "</label>";
            echo "</span>";
            echo elgg_view('input/text', array(
                'label' => $field_name,
                'id' => $field_name,
                'name' => $field_name,
                'value' => empty($value) ? 0 : $value,
                'class' => ['required-field', 'pir-form-cost'],
                'options_values' => $field->input_vars,
                'required' => 'true'
            ));
            break;
        case "file":

            $file = get_entity($dor->file_guid);
            // check if file exists so we can provide a link to it
            if (!empty($file)) {
                echo "<div class=\"dor-file-upload\">";
                echo elgg_view("output/url", ['file_guid' => $file->guid, 'class' => 'elgg-button elgg-button-action dor-delete-file-button mrs mbs', 'text' => '&#10006;', 'title'=>elgg_echo('nihcp_commons_credit_request:ccreq:deletefiletooltip')]);
                echo elgg_view("output/url", ['text' => $file->title, 'href' => "/file/download/$file->guid"]);
                echo "</div>";

            } else { // means there is no file so clear the value variable
                $value = null;
            }
            echo "<span class='prm'>";
            echo "<label for='$field->name'>" . elgg_echo("nihcp_pir:do_form:" . $field_name) . "</label>";
            echo "</span>";
            echo elgg_view('input/' . $field->input_type, array(
                'label' => $field_name,
                'id' => $field_name,
                'name' => $field_name,
                'value' => $value,
                'class' => ['required-field'],
                'options_values' => $field->input_vars,
            ));
            break;
        default:
            echo "<span class='prm'>";
            echo "<label for='$field->name'>" . elgg_echo("nihcp_pir:do_form:" . $field_name) . "</label>";
            echo "</span>";
            echo elgg_view('input/' . $field->input_type, array(
                'label' => $field_name,
                'id' => $field_name,
                'name' => $field_name,
                'value' => $value,
                'class' => ['required-field'],
                'options_values' => $field->input_vars,
                'required' => 'true'
            ));
            break;
    }

    echo "</div>";
}
echo "</div>";

echo "</div>";

echo "<div class=\"elgg-foot\">";
		echo elgg_view('input/hidden', array('name' => 'pir_guid', 'id'=>'pir_guid', 'value'=>$pir_guid));
        echo elgg_view('input/hidden', array('name' => 'ccreq_guid', 'id'=>'ccreq_guid', 'value'=>$ccreq_guid));
        echo elgg_view('input/hidden', array('name' => 'dor_guid', 'id'=>'dor_guid', 'value'=>$dor_guid));
        echo elgg_view('input/submit', array('name' => 'action', 'id' => 'do-save-button', 'value' => 'Save', 'class' => $all_filled ? null : 'disabled', 'disabled'=>!$all_filled));
        echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Discard Changes'));

echo "</div>";