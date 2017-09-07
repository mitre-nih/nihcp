<?php

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