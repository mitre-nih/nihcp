<?php

namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, /*RoleManager::DOMAIN_EXPERT*/));

$ia = elgg_set_ignore_access(true);

$request_guid = get_input('request_guid');

/*if($request_guid) {
    $request = get_entity($request_guid);
    if (!$request instanceof \Nihcp\Entity\CommonsCreditRequest) {
        register_error(elgg_echo('error:404:content'));
        forward('/nihcp_credit_request_review/overview');
    }
}*/

//if user is triage coordinator

if(nihcp_role_gatekeeper(RoleManager::NIH_APPROVER,False) && !elgg_is_admin_logged_in()){
    $content = elgg_view('nihcp_credit_request_review/grant-id-validation',array('request_guid'=>$request_guid));
}else {
    $content = elgg_view_form('grant-id-validation', array('request_guid' => $request_guid));
}
//else if nih approver
    //$content = elgg_view('nihcp_credit_request_review/grant-id-validation',array('request_guid'=>$request_guid));


elgg_set_ignore_access($ia);

$params = array(
    'title' => elgg_echo("nihcp_credit_request_verify"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page(elgg_echo("nihcp_credit_request_verify"), $body);

?>