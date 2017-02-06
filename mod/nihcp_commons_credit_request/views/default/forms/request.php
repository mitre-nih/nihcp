<?php

use \Nihcp\Entity\CommonsCreditRequest;

elgg_require_js('jquery');
elgg_require_js('autoNumeric');
elgg_require_js('request');


if(elgg_is_sticky_form('request')) {
	extract(elgg_get_sticky_values('request'));
	elgg_clear_sticky_form('request');
}


// set the form fields if this is an existing draft
if (isset($vars['current_request'])) {
    $request_draft = $vars['current_request'];
    $project_title = $request_draft->project_title;

    // Grant Linkage Fields
    $nih_program_officer_name = $request_draft->nih_program_officer_name;
    $nih_program_officer_email = $request_draft->nih_program_officer_email;
    $alt_grant_verification_contact = $request_draft->alt_grant_verification_contact;
    $alt_grant_verification_contact_title = $request_draft->alt_grant_verification_contact_title;
    $alt_grant_verification_contact_email = $request_draft->alt_grant_verification_contact_email;
    $grant_id = $request_draft->grant_id;

    $proposed_research = $request_draft->proposed_research;
    $productivity_gain = $request_draft->productivity_gain;
    $productivity_gain_explanation = $request_draft->productivity_gain_explanation;
    $unique_resource_access = $request_draft->unique_resource_access;
    $unique_resource_access_explanation = $request_draft->unique_resource_access_explanation;
    $past_experience = $request_draft->past_experience;
    $past_experience_explanation = $request_draft->past_experience_explanation;
    $datasets = $request_draft->datasets;
    $datasets_has_access_restrictions = $request_draft->datasets_has_access_restrictions;
    $applications_tools = $request_draft->applications_tools;
    $applications_tools_has_access_restrictions = $request_draft->applications_tools_has_access_restrictions;
    $workflows = $request_draft->workflows;
    $workflows_has_access_restrictions = $request_draft->workflows_has_access_restrictions;
    $digital_object_retention_plan = $request_draft->digital_object_retention_plan;
    $server_compute_expected_cost = $request_draft->server_compute_expected_cost;
    $storage_expected_cost = $request_draft->storage_expected_cost;
    $network_services_expected_cost = $request_draft->network_services_expected_cost;
    $web_servers_expected_cost = $request_draft->web_servers_expected_cost;
    $databases_expected_cost = $request_draft->databases_expected_cost;
    $other_expected_cost = $request_draft->other_expected_cost;
    $other_expected_cost_explanation = $request_draft->other_expected_cost_explanation;
    $pricing_upload = $request_draft->pricing_upload;
    $supplementary_materials_upload = $request_draft->supplementary_materials_upload;
	$pricing_upload_file = get_entity($request_draft->pricing_upload_guid);
	$supplementary_materials_upload_file = get_entity($request_draft->supplementary_materials_upload_guid);
}

$required_fields = array(
	$project_title,
    $grant_id,
	$proposed_research,
	$server_compute_expected_cost,
	$storage_expected_cost,
	$network_services_expected_cost,
	$web_servers_expected_cost,
	$databases_expected_cost,
);
$all_filled = true;
foreach($required_fields as $field) {
	if($field === null || strlen(trim($field)) === 0) {
		$all_filled = false;
	}
}

?>



<div class="hiviz">
	<span class="required-icon"></span>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:required"); ?>
</div>

<div class="required-field">
    <label for='project_title'>
        <span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:project_title");?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:project_title:desc"); ?>
    <br />
    <textarea name='project_title' id='project_title' required='true' maxlength='<?php echo CommonsCreditRequest::PROJECT_TITLE_MAX_LENGTH ?>'><?php echo $project_title;?></textarea>

</div>

<!-- Grant Linkage Fields-->
<div class="required-field">
    <label for='grant_id'>
        <span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:grant_id");?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:grant_id:desc"); ?>
     <span class="rationale"><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:grant_id:rationale"); ?></span>
    <br />
    <textarea name='grant_id' id='grant_id' maxlength='<?php echo CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH ?>'><?php echo $grant_id;?></textarea>
    <span name="grant_id_verify" id="grant_id_verify" class="elgg-button elgg-button-submit" value="Verify">Verify</span>
    <span><?php echo elgg_view('graphics/ajax_loader', array(
            'class' => 'embed-throbber mtl',
            'id' => 'verify_loading',
        )); ?></span>
    <span name="verification_status" id="verification_status" class="verification_status"><span class="checkmark"></span></span>
</div>
<div class="required-field">
    <label for='nih_program_officer_name'>
        <span class="hiviz required-icon"></span><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_name");?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_name:desc"); ?>
    <br />
    <textarea name='nih_program_officer_name' id='nih_program_officer_name' maxlength='<?php echo CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH ?>'><?php echo $nih_program_officer_name;?></textarea>

</div>

<div class="required-field">
    <label for='nih_program_officer_email'>
        <span class="hiviz required-icon"></span><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_email");?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_email:desc"); ?>
    <br />
    <textarea name='nih_program_officer_email' id='nih_program_officer_email' maxlength='<?php echo CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH ?>'><?php echo $nih_program_officer_email;?></textarea>

</div>

<div>
    <label for='alt_grant_verification_contact'>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact");?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact:desc"); ?>
    <br />
    <textarea name='alt_grant_verification_contact' id='alt_grant_verification_contact' maxlength='<?php echo CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH ?>'><?php echo $alt_grant_verification_contact;?></textarea>

</div>

<div>
    <label for='alt_grant_verification_contact_title'>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_title");?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_title:desc"); ?>
    <br />
    <textarea name='alt_grant_verification_contact_title' id='alt_grant_verification_contact_title' maxlength='<?php echo CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH ?>'><?php echo $alt_grant_verification_contact_title;?></textarea>

</div>

<div>
    <label for='alt_grant_verification_contact_email'>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_email");?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_email:desc"); ?>
    <br />
    <textarea name='alt_grant_verification_contact_email' id='alt_grant_verification_contact_email' maxlength='<?php echo CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH ?>'><?php echo $alt_grant_verification_contact_email;?></textarea>

</div>

<!--End Grant Linkage Fields-->

<div class="required-field">
    <label for='proposed_research'>
        <span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:proposed_research");?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:proposed_research:desc");?>
    <br />
    <textarea name='proposed_research' id='proposed_research' maxlength='<?php echo CommonsCreditRequest::PROPOSED_RESEARCH_MAX_LENGTH ?>'><?php echo $proposed_research;?></textarea>
</div>


<div>
    <label for="supplementary_materials">
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:supplementary_materials"); ?>
    </label>
    <div class="ccreq-file-upload">
        <?php
        if($supplementary_materials_upload_file) {
            echo elgg_view("output/url", ['file_guid' => $supplementary_materials_upload_file->guid, 'class' => 'elgg-button elgg-button-action ccreq-delete-file-button mrs mbs', 'text' => '&#10006;', 'title'=>elgg_echo('nihcp_commons_credit_request:ccreq:deletefiletooltip')]);
            echo elgg_view("output/url", ['text' => $supplementary_materials_upload_file->title, 'href' => "/file/download/$supplementary_materials_upload_file->guid"]);
        }
        ?>
    </div>
    <?php
    echo elgg_view('input/file', array(
        'name' => 'supplementary_materials_upload',
        'id' => 'supplementary_materials'));
    ?>
</div>


<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:productivity_gain"); ?>
    </label>


    <br />
    <?php echo elgg_view('input/select', array(
        'name' => 'productivity_gain',
		'value' => $productivity_gain,
        'options_values' => array (
            '' => '',
            'Yes' => 'Yes',
            'No' => 'No',
            'N/A' => 'N/A')
    ))?>
</div>
<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:answer_yes"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:productivity_gain:desc"); ?>
    <br />
    <textarea name='productivity_gain_explanation' id='productivity_gain_explanation' maxlength='<?php echo CommonsCreditRequest::PRODUCTIVITY_GAIN_MAX_LENGTH ?>'><?php echo $productivity_gain_explanation;?></textarea>
</div>

<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:unique_resource_access"); ?>
    </label>


    <br />
    <?php echo elgg_view('input/select', array(
        'name' => 'unique_resource_access',
		'value' => $unique_resource_access,
        'options_values' => array (
            '' => '',
            'Yes' => 'Yes',
            'No' => 'No',
            'N/A' => 'N/A')
    ))?>
</div>
<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:answer_yes"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:unique_resource_access:desc"); ?>
    <br />
    <textarea name='unique_resource_access_explanation' id='unique_resource_access_explanation' maxlength='<?php echo CommonsCreditRequest::UNIQUE_RESOURCE_ACCESS_MAX_LENGTH ?>'><?php echo $unique_resource_access_explanation;?></textarea>
</div>

<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:past_experience"); ?>
    </label>

    <br />
    <?php echo elgg_view('input/select', array(
        'name' => 'past_experience',
		'value' => $past_experience,
        'options_values' => array (
            '' => '',
            'Yes' => 'Yes',
            'No' => 'No',
            'N/A' => 'N/A')
    ))?>
</div>
<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:answer_yes"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:past_experience:desc"); ?>
    <br />
    <textarea name='past_experience_explanation' id='past_experience_explanation' maxlength='<?php echo CommonsCreditRequest::PAST_EXPERIENCE__MAX_LENGTH ?>'><?php echo $past_experience_explanation;?></textarea>
</div>

<div>

    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:digital_objects:desc"); ?>
    </label>


</div>

<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:datasets"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:datasets:desc"); ?>
    <br/>
    <?php

        if ($datasets_has_access_restrictions === 'on') {
            echo elgg_view('input/checkbox', array(
                'name' => 'datasets_has_access_restrictions',
                'checked' => 'true',
                'label' => elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"),
                'label_class' => 'controlled-access-text'
            ));
        } else {
            echo elgg_view('input/checkbox', array(
                'name' => 'datasets_has_access_restrictions',
                'label' => elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"),
                'label_class' => 'controlled-access-text'
            ));
        }
    ?>
    <br />
    <textarea name='datasets' id='datasets' maxlength='<?php echo CommonsCreditRequest::DATASETS_MAX_LENGTH ?>'><?php echo $datasets;?></textarea>
</div>

<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:applications_tools"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:applications_tools:desc"); ?>
    <br/>
    <?php
    if ($applications_tools_has_access_restrictions === 'on') {
        echo elgg_view('input/checkbox', array(
            'name' => 'applications_tools_has_access_restrictions',
            'checked' => 'true',
            'label' => elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"),
            'label_class' => 'controlled-access-text'
        ));
    } else {
        echo elgg_view('input/checkbox', array(
            'name' => 'applications_tools_has_access_restrictions',
            'label' => elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"),
            'label_class' => 'controlled-access-text'
        ));
    }
    ?>
    <br />
    <textarea name='applications_tools' id='applications_tools' maxlength='<?php echo CommonsCreditRequest::APPLICATIONS_TOOLS_MAX_LENGTH ?>'><?php echo $applications_tools;?></textarea>
</div>

<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:workflows"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:workflows:desc"); ?>
    <br/>
    <?php
    if ($workflows_has_access_restrictions === 'on') {
        echo elgg_view('input/checkbox', array(
            'name' => 'workflows_has_access_restrictions',
            'checked' => 'true',
            'label' => elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"),
            'label_class' => 'controlled-access-text'
        ));
    } else {
        echo elgg_view('input/checkbox', array(
            'name' => 'workflows_has_access_restrictions',
            'label' => elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"),
            'label_class' => 'controlled-access-text'
        ));
    }
    ?>
    <br />
    <textarea name='workflows' id='workflows' maxlength='<?php echo CommonsCreditRequest::WORKFLOWS_MAX_LENGTH ?>'><?php echo $workflows;?></textarea>
</div>

<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:digital_object_retention_plan"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:digital_object_retention_plan:desc"); ?>
    <br />
    <textarea name='digital_object_retention_plan' id='digital_object_retention_plan' maxlength='<?php echo CommonsCreditRequest::DIGITAL_OBJECT_RETENTION_PLAN_MAX_LENGTH ?>'><?php echo $digital_object_retention_plan;?></textarea>
</div>

<div>
    <label>
        <span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:cloud_service_request"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:cloud_service_request:desc"); ?>
    <br />
</div>

<div>
    <label for="pricing">
        <span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:pricing"); ?>
    </label>
    <div class="ccreq-file-upload">
        <?php
        if($pricing_upload_file) {
            echo elgg_view("output/url", ['file_guid' => $pricing_upload_file->guid, 'class' => 'elgg-button elgg-button-action ccreq-delete-file-button mrs mbs', 'text' => '&#10006;', 'title'=>elgg_echo('nihcp_commons_credit_request:ccreq:deletefiletooltip')]);
            echo elgg_view("output/url", ['text' => $pricing_upload_file->title, 'href' => "/file/download/$pricing_upload_file->guid"]);
        }
        ?>
    </div>
    <?php
    echo elgg_view('input/file', array(
        'name' => 'pricing_upload',
        'id' => 'pricing'));
    ?>
</div>


<div class="required-field">
    <label>
		<span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:server_compute"); ?>
    </label>

    <br />
    <?php echo elgg_view('input/text', array(
        'name' => 'server_compute_expected_cost',
        'value' => empty($server_compute_expected_cost) ? 0 : $server_compute_expected_cost,
		'class' => 'ccreq-cost')); ?>
</div>

<div class="required-field">
    <label>
		<span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:storage"); ?>
    </label>

    <br />
    <?php echo elgg_view('input/text', array(
        'name' => 'storage_expected_cost',
        'value' => empty($storage_expected_cost) ? 0 : $storage_expected_cost,
		'class' => 'ccreq-cost')); ?>
</div>

<div class="required-field">
    <label>
		<span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:network_services"); ?>
    </label>

    <br />
    <?php echo elgg_view('input/text', array(
        'name' => 'network_services_expected_cost',
        'value' => empty($network_services_expected_cost) ? 0 : $network_services_expected_cost,
		'class' => 'ccreq-cost')); ?>
</div>

<div class="required-field">
    <label>
		<span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:webservers"); ?>
    </label>

    <br />
    <?php echo elgg_view('input/text', array(
        'name' => 'web_servers_expected_cost',
        'value' => empty($web_servers_expected_cost) ? 0 : $web_servers_expected_cost,
		'class' => 'ccreq-cost')); ?>
</div>


<div class="required-field">
    <label>
		<span class="hiviz required-icon"></span> <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:databases"); ?>
    </label>

    <br />
    <?php echo elgg_view('input/text', array(
        'name' => 'databases_expected_cost',
        'value' => empty($databases_expected_cost) ? 0 : $databases_expected_cost,
		'class' => 'ccreq-cost')); ?>
</div>

<div class="required-field">
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:other"); ?>
    </label>

    <br />
    <?php echo elgg_view('input/text', array(
        'name' => 'other_expected_cost',
        'value' => empty($other_expected_cost) ? 0 : $other_expected_cost,
		'class' => 'ccreq-cost')); ?>
</div>

<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:other_explanation"); ?>
    </label>
    <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:other_explanation:desc"); ?>

    <br />
    <textarea name='other_expected_cost_explanation' id='other_expected_cost_explanation' maxlength='<?php echo CommonsCreditRequest::OTHER_EXPECTED_COSTS_EXPLANATION ?>'><?php echo $other_expected_cost_explanation;?></textarea>

</div>

<div>
    <label>
        <?php echo elgg_echo("nihcp_commons_credit_request:ccreq:total_cost"); ?>
    </label>
    <div id="ccreq-total-cost"></div>
</div>







<div class="elgg-foot">
    <?php
		echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_draft->guid));
        echo elgg_view('input/submit', array('name' => 'action', 'id' => 'ccreq-next-button', 'class' => $all_filled ? null : 'disabled', 'value' => 'Next', 'disabled'=>!$all_filled));
        echo elgg_view('input/submit', array('name' => 'action', 'id' => 'ccreq-save-button', 'class' => isset($project_title) ? null : 'disabled', 'value' => 'Save', 'disabled'=>!isset($project_title)));
        echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Discard Changes'));
    ?>
</div>