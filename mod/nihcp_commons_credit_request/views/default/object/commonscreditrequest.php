<?php
	$request = elgg_extract('entity', $vars);
	$pricing_upload_file = get_entity($request->pricing_upload_guid);
	$supplementary_materials_upload_file = get_entity($request->supplementary_materials_upload_guid);
?>



<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:project_title"); ?></b>
<br/>
<?php echo $request->project_title; ?>
<br/>

<b>Submitter</b>
<br/>
<?php echo get_entity($request->owner_guid)->getDisplayName(); ?>
<br/>

<b>Submission Date</b>
<br/>
<?php echo $request->submission_date; ?>
<br/>


<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:grant_linkage"); ?></b>
<br/>
<?php echo $request->grant_linkage; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:proposed_research"); ?></b>
<br/>
<?php echo $request->proposed_research; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:productivity_gain"); ?></b>
<br/>
<?php echo $request->productivity_gain; ?>
<br/>
<br/>
<?php echo $request->productivity_gain_explanation; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:unique_resource_access"); ?></b>
<br/>
<?php echo $request->unique_resource_access; ?>
<br/>
<br/>
<?php echo $request->unique_resource_access_explanation; ?>
<br/>



<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:past_experience"); ?></b>
<br/>
<?php echo $request->past_experience; ?>
<br/>
<br/>
<?php echo $request->past_experience_explanation; ?>
<br/>




<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:datasets"); ?></b>
<br/>
<?php echo $request->datasets; ?>
<br/>
<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"); ?></b>
<br/>
<?php
    if($request->datasets_has_access_restrictions === 'on') {
        echo 'Yes';
    } else {
        echo 'No';
    }
?>
<br/><br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:applications_tools"); ?></b>
<br/>
<?php echo $request->applications_tools; ?>
<br/>
<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"); ?></b>
<br/>
<?php
    if($request->applications_tools_has_access_restrictions === 'on') {
        echo 'Yes';
    } else {
        echo 'No';
}
?>
<br/><br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:workflows"); ?></b>
<br/>
<?php echo $request->workflows; ?>
<br/>
<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"); ?></b>
<br/>
<?php
if($request->workflows_has_access_restrictions === 'on') {
    echo 'Yes';
} else {
	echo 'No';
}
?>
<br/><br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:digital_object_retention_plan"); ?></b>
<br/>
<?php echo $request->digital_object_retention_plan; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:server_compute"); ?></b>
<br/>
<?php echo $request->server_compute_expected_cost; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:storage"); ?></b>
<br/>
<?php echo $request->storage_expected_cost; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:network_services"); ?></b>
<br/>
<?php echo $request->network_services_expected_cost; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:webservers"); ?></b>
<br/>
<?php echo $request->web_servers_expected_cost; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:databases"); ?></b>
<br/>
<?php echo $request->databases_expected_cost; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:other"); ?></b>
<br/>
<?php echo $request->other_expected_cost; ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:total_cost"); ?></b>
<br/>
<?php echo $request->getExpectedCostTotal(); ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:pricing"); ?></b>
<br/>
<?php if($pricing_upload_file) echo elgg_view("output/url", ['text' => $pricing_upload_file->title, 'href' => "/file/download/$pricing_upload_file->guid"]); ?>
<br/>

<b><?php echo elgg_echo("nihcp_commons_credit_request:ccreq:supplementary_materials"); ?></b>
<br/>
<?php if($supplementary_materials_upload_file) echo elgg_view("output/url", ['text' => $supplementary_materials_upload_file->title, 'href' => "/file/download/$supplementary_materials_upload_file->guid"]); ?>
<br/>