<?php

use Nihcp\Entity\PostImplementationReport;
use Nihcp\Entity\DigitalObjectReport;
use Nihcp\Entity\CommonsCreditRequest;

$ia = elgg_set_ignore_access();
$ccreq_guid = sanitize_int(get_input("ccreq_guid"));
$ccreq = get_entity($ccreq_guid);


$pir = get_entity(PostImplementationReport::getPirGuidFromCcreqGuid($ccreq_guid));

if (empty($pir)) {

    register_error(elgg_echo("error:404:content"));
    forward(REFERER);

}

$pir_guid = $pir->guid;


$content = "<div>";

$content .= "<div class='pvl'>";
$content .= "<div class='pvs'>";
$content .= "<b>Project Name: </b> " . $ccreq->project_title;
$content .= "</div>";
$content .= "<div class='pbs'>";
$content .= "<b>CCREQ ID: </b> " . $ccreq->getRequestId();
$content .= "</div>";
$content .= "<div class='pbs'>";
$content .= "<b>Submitted Date: </b> " . date(CommonsCreditRequest::DATE_FORMAT,$pir->submitted_date);
$content .= "</div>";
$content .= "</div>";

$content .= "<div class='pvl'>";
$content .= "<div><b>" . elgg_echo("nihcp_pir:do_reuse") . "</b></div>";
$content .= "<div class='phl'>$pir->do_reuse</div>";
$content .= "<div><b>" . elgg_echo("nihcp_pir:overall_issues") . "</b></div>";
$content .= "<div class='phl'>$pir->overall_issues</div>";
$content .= "</div>";

$content .= "<div class='pvl'>";

$dors = DigitalObjectReport::getDigitalObjectReportsFromPirGuid($pir_guid);
$dor_form_fields = PostImplementationReport::getPirDigitalObjectReportFormFields();
if (!empty($dors)) {
    foreach ($dors as $dor) {
        $content .= "<div class='pvm'>";
        $content .= "<div><b>Digital Object (or Object set)</b></div>";
        foreach ($dor_form_fields as $form_field) {
            $field_name =  $form_field->name;

            $value = $dor->$field_name;
            switch ($form_field->input_type) {
                case 'select':
                    // find the text value of the option selected
                    $value = $form_field->input_vars[$value];
                    break;
                case 'file':
                    $file = get_entity($dor->file_guid);
                    if (!empty($file)) {
                        $value = elgg_view("output/url", ['text' => $file->title, 'href' => "/nihcp_post_implementation_report/attachment/$dor->guid?file_guid=$file->guid"]);

                    } else {
                        $value = "No attached file.";
                    }
                    break;
                default:
                    break;
            }




            $content .= "<div>";
            $content .= "<span class='prm'><b>" . elgg_echo("nihcp_pir:do_form:" . $field_name) ."</b></span>";
            $content .= "<div class='phl'>$value</div>";
            $content .= "</div>";
        }
        $content .= "</div>";
    }
}

$content .= "</div>";

$content .= "</div>";


echo $content;


elgg_set_ignore_access($ia);