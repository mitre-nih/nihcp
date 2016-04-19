<?php

$overview_page_url = elgg_get_site_url() . 'nihcp_commons_credit_request/overview';

?>
<div>
    <a class="elgg-button-submit elgg-button" href="<?php echo $overview_page_url; ?>">Back to Overview</a>
</div>
<?php

$guid = get_input('request_guid');
$request = get_entity($guid);

echo elgg_view_entity($request);
