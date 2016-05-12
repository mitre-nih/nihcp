<?php

$overview_page_url = elgg_get_site_url() . 'nihcp_commons_credit_request/overview';


$guid = get_input('request_guid');

if (nihcp_triage_coordinator_gatekeeper(false) || nihcp_nih_approver_gatekeeper(false)) {

    $ia = elgg_set_ignore_access(true);
    $request = get_entity($guid);
    elgg_set_ignore_access($ia);

} else {
    $request = get_entity($guid);
}
?>

<div>
    <a class="elgg-button-submit elgg-button" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a>
</div>
<?php
echo elgg_view_entity($request);

?>

