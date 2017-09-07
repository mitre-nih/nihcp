<?php

use \Nihcp\Entity\PostImplementationReport;
use \Nihcp\Entity\DigitalObjectReport;


elgg_require_js('pir');

$ccreq_guid = sanitize_int(get_input("ccreq_guid"));
$ccreq = get_entity($ccreq_guid);

if (empty(PostImplementationReport::getPirGuidFromCcreqGuid($ccreq_guid))) {

    $pir = new PostImplementationReport();
    $pir->status='Draft';
    $pir_guid = $pir->save();
    add_entity_relationship($ccreq_guid, PostImplementationReport::RELATIONSHIP_CCREQ_TO_PIR, $pir_guid);
    $pir->save();
} else {
    $pir = get_entity(PostImplementationReport::getPirGuidFromCcreqGuid($ccreq_guid));
    $pir_guid = $pir->guid;
}

$content = "<div>";


$content .= "<div class='pvl'>";
$content .= "<div class='pvs'>";
$content .= "<b>Please Review the Guidance: </b> <a href='" . elgg_get_site_url() . "nihcp_post_implementation_report/guidance'>Link to the guidance</a>";
$content .= "</div>";
$content .= "<div class='pvs'>";
$content .= "<b>Project Name: </b> " . $ccreq->project_title;
$content .= "</div>";
$content .= "<div class='pvs'>";
$content .= "<b>CCREQ ID: </b> " . $ccreq->getRequestId();
$content .= "</div>";
$content .= "</div>";

// url path includes guid for the PIR
$do_link = elgg_get_site_url() . "nihcp_post_implementation_report/do/" . $pir_guid . "/new";
$content .= "<div>";
$content .= "<button class='elgg-button elgg-button-submit' onclick='location.href=\"$do_link\"'>Add DO Description</button>";
$content .= "</div>";

$digital_object_reports = DigitalObjectReport::getDigitalObjectReportsFromPirGuid($pir_guid);

$content .= "<div class='pvl'>";

if (empty($digital_object_reports)) {
    $content .= elgg_echo('nihcp_pir:no_digital_objects');
    // checkbox for confirming no digital objects
    $content .= "<div class='pvl'>";
    $content .= elgg_view("input/checkbox", array(
        'label' => elgg_echo('nihcp_pir:no_digital_objects:checkbox'),
        'id' => 'no-digital-objects-checkbox'
    ));
    $content .= "</div>";
} else {

    $form_fields = PostImplementationReport::getPirDigitalObjectReportFormFields();

    $content .= "<table class =\"elgg-table\" summary=\"List of DOs for this PIR.\">";
    $content .= "<thead><tr><th>#</th><th>Digital Object</th><th>Name</th><th>Action</th></tr></thead>";

    $i = 1;

    $delete_button = elgg_view('input/button', array(
        'value' => 'Delete',
        'class' => 'elgg-button-cancel dor-delete-button'
    ));

    $content .= "<tbody>";
    foreach($digital_object_reports as $dor) {

        $dor_link = elgg_get_site_url() . "nihcp_post_implementation_report/do/$pir_guid/" . $dor->guid;

        $content .= "<tr id='$dor->guid'><td>$i</td><td>" . $form_fields['class']->input_vars[$dor->class] . "</td><td><a href=\"$dor_link\">$dor->title</a></td><td>$delete_button</td></tr>";
        $i++;
    }
    $content .= "</tbody></table>";


}

$disabled = empty($digital_object_reports) ? "disabled='true'" : "";


// link to submission form includes PIR guid
$next_link = elgg_get_site_url() . "nihcp_post_implementation_report/submission/" . $pir_guid;
$content .= "<div class='pvl'>";
$content .= "<button id='pir-next-button' $disabled onclick='location.href=\"$next_link\"'>Next</button>";
$content .= "</div>";

$content .= "</div>";



$content .= "</div>";

echo $content;