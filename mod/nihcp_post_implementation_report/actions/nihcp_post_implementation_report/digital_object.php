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
use \Nihcp\Entity\PostImplementationReport;
use \Nihcp\Entity\DigitalObjectReport;
use \Nihcp\Entity\CommonsCreditRequest;

// get which action was called
$action = get_input('action', '', false);

// get the pir_guid associated with this digital object
// the logged in user should be the one who created the PIR
$pir_guid = get_input('pir_guid', '', false);
$ccreq_guid = get_input('ccreq_guid', '', false);

// if there is a value here then we're editing an existing one
$dor_guid = get_input('dor_guid', '', false);

// if user doesn't have access to the pir, then there's something fishy happening
// abort the action
$pir = get_entity($pir_guid);
if (empty($pir)) {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
}



switch ($action) {
    case 'Save':

        if (empty($dor_guid)) {
            $dor = new DigitalObjectReport();
            $dor_guid = $dor->save();

            add_entity_relationship($pir_guid, DigitalObjectReport::RELATIONSHIP_PIR_TO_DOR, $dor_guid);
        } else {
            $dor = get_entity($dor_guid);
        }

        $form_fields = PostImplementationReport::getPirDigitalObjectReportFormFields();
        foreach ($form_fields as $form_field) {

            if ($form_field->input_type === "file") { //have to save files
                $file_guid = CommonsCreditRequest::saveUploadFile($form_field->name);
                if($file_guid) {
                    if(isset($dor->file_guid)) {
                        $old_file = get_entity($dor->file_guid);
                        if($old_file) $old_file->delete();
                        unset($dor->file_guid);
                    }
                    $dor->file_guid = $file_guid;
                }
            } else {
                $field_name = $form_field->name;
                $dor->$field_name = htmlspecialchars(get_input($field_name), ENT_NOQUOTES, 'UTF-8');
            }

        }

        $dor->save();

        forward('nihcp_post_implementation_report/pir/' . $ccreq_guid);
        break;
    default:
        forward('nihcp_post_implementation_report/pir/' . $ccreq_guid);
        break;

}