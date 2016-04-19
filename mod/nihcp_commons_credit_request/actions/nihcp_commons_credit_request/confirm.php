<?php

use \Nihcp\Entity\CommonsCreditRequest;

$guid = get_input('request_guid');

$current_request = get_entity($guid);

$action = get_input('action');

switch ($action) {
    case 'Submit':
        $current_request->status = 'Submitted';
		$current_request->submission_date = date('n/j/Y');
        break;
    default:
        break;
}


forward('nihcp_commons_credit_request/overview');