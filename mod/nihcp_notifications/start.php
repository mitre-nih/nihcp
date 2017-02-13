<?php

elgg_register_event_handler('init', 'system', 'nihcp_notifications_init');

function nihcp_notifications_init() {
	elgg_register_event_handler('submit', 'object:'.\Nihcp\Entity\CommonsCreditRequest::SUBTYPE, 'handle_submit_request_notifications');

	elgg_register_event_handler('risk_benefit_score_complete', 'object:'.\Nihcp\Entity\CommonsCreditRequest::SUBTYPE, 'handle_risk_benefit_score_complete_notifications');

	elgg_register_event_handler('withdraw_decision', 'object:'.\Nihcp\Entity\CommonsCreditRequest::SUBTYPE, 'handle_withdraw_decision_notifications');

	elgg_register_event_handler('ingest:after', 'object:'.\Nihcp\Entity\CommonsCreditAllocationFile::SUBTYPE, 'allocations_updated_trigger_events');

	elgg_register_notification_event('object', \Nihcp\Entity\CommonsCreditRequest::SUBTYPE, ['decide', 'allocations_updated']);

	elgg_register_plugin_hook_handler('get', 'subscriptions', 'modify_ccreq_subscriptions_for_notification');

	elgg_register_plugin_hook_handler('prepare', 'notification:decide:object:'.\Nihcp\Entity\CommonsCreditRequest::SUBTYPE, 'request_decided_prepare_notification');

	elgg_register_plugin_hook_handler('prepare', 'notification:allocations_updated:object:'.\Nihcp\Entity\CommonsCreditRequest::SUBTYPE, 'allocations_updated_prepare_notifications');

    //generate daily CCREQ stats for email
    elgg_register_plugin_hook_handler('cron', 'daily', 'ccreq_stats_cron');
    //generate daily helpdesk stats for email
    elgg_register_plugin_hook_handler('cron', 'daily', 'helpdesk_stats_cron');
}

function handle_submit_request_notifications($event, $object_type, $object) {
	global $CONFIG;
	$subject = elgg_echo('nihcp_notifications:notify:submit_request:subject');
	$body = elgg_echo('nihcp_notifications:notify:submit_request:body');

	$from = elgg_get_config('siteemail');
	$to = $object->getOwnerEntity()->email;

	$headers = array(
		"Content-Type" => "text/plain; charset=UTF-8; format=flowed",
		"MIME-Version" => "1.0",
		"Content-Transfer-Encoding" => "8bit",
	);

	// return true/false to stop elgg_send_email() from sending
	$mail_params = array(
		'to' => $to,
		'from' => $from,
		'subject' => $subject,
		'body' => $body,
		'headers' => $headers,
	);

	// $mail_params is passed as both params and return value. The former is for backwards
	// compatibility. The latter is so handlers can now alter the contents/headers of
	// the email by returning the array
	$result = elgg_trigger_plugin_hook('email', 'system', $mail_params, $mail_params);
	if (is_array($result)) {
		foreach (array('to', 'from', 'subject', 'body', 'headers') as $key) {
			if (isset($result[$key])) {
				${$key} = $result[$key];
			}
		}
	} elseif ($result !== null) {
		return $result;
	}

	$header_eol = "\r\n";
	if (isset($CONFIG->broken_mta) && $CONFIG->broken_mta) {
		// Allow non-RFC 2822 mail headers to support some broken MTAs
		$header_eol = "\n";
	}

	// Windows is somewhat broken, so we use just address for to and from
	if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
		// strip name from to and from
		if (strpos($to, '<')) {
			preg_match('/<(.*)>/', $to, $matches);
			$to = $matches[1];
		}
		if (strpos($from, '<')) {
			preg_match('/<(.*)>/', $from, $matches);
			$from = $matches[1];
		}
	}

	// make sure From is set
	if (empty($headers['From'])) {
		$headers['From'] = $from;
	}

	// stringify headers
	$headers_string = '';
	foreach ($headers as $key => $value) {
		$headers_string .= "$key: $value{$header_eol}";
	}

	// Sanitise subject by stripping line endings
	$subject = preg_replace("/(\r\n|\r|\n)/", " ", $subject);
	// this is because Elgg encodes everything and matches what is done with body
	$subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8'); // Decode any html entities
	if (is_callable('mb_encode_mimeheader')) {
		$subject = mb_encode_mimeheader($subject, "UTF-8", "B");
	}

	// Format message
	$body = html_entity_decode($body, ENT_QUOTES, 'UTF-8'); // Decode any html entities
	$body = elgg_strip_tags($body); // Strip tags from message
	$body = preg_replace("/(\r\n|\r)/", "\n", $body); // Convert to unix line endings in body
	$body = preg_replace("/^From/", ">From", $body); // Change lines starting with From to >From
	//$body = wordwrap($body);

	mail($to, $subject, $body, $headers_string);
}

function handle_risk_benefit_score_complete_notifications($event, $object_type, $object) {
	$ia = elgg_set_ignore_access();

	$users = \Nihcp\Manager\RoleManager::getRoleByName(\Nihcp\Manager\RoleManager::TRIAGE_COORDINATOR)->getMembers(['limit' => 0]);

	//$rbs_entities = \Nihcp\Entity\RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($object->getGUID());

	// Title for the notification
	$subject = elgg_echo('nihcp_notifications:notify:risk_benefit_score_complete:subject');

	// Message body for the notification
	$message = elgg_echo('nihcp_notifications:notify:risk_benefit_score_complete:body', [$object->getRequestId()]);

	$summary = elgg_echo('nihcp_notifications:notify:risk_benefit_score_complete:summary', array($object->getRequestId()));

	foreach($users as $user) {
		notify_user($user->getGUID(), elgg_get_site_entity()->getGUID(), $subject, $message,
			$params = ['object' => $object, 'action' => 'risk_benefit_score_complete', 'summary' => $summary],
			$methods_override = 'email');
	}

	elgg_set_ignore_access($ia);
}

function handle_withdraw_decision_notifications($event, $object_type, $object) {
	$ia = elgg_set_ignore_access();

	$users = \Nihcp\Manager\RoleManager::getRoleByName(\Nihcp\Manager\RoleManager::TRIAGE_COORDINATOR)->getMembers(['limit' => 0]);
	$feedback = \Nihcp\Entity\Feedback::getFeedback($object->getGUID());
	$users[] = $feedback->getOwnerEntity();

	$status_change = $feedback->getStatusChange();

	// Title for the notification
	$subject = elgg_echo('nihcp_notifications:notify:withdraw_decision:subject');

	// Message body for the notification
	$message = elgg_echo('nihcp_notifications:notify:withdraw_decision:body', array(
		$object->project_title,
		$object->getRequestId(),
		$feedback->decision,
		$object->status,
		$status_change->reason,
	));

	$summary = elgg_echo('nihcp_notifications:notify:withdraw_decision:summary', array($object->getRequestId()));

	foreach($users as $user) {
		elgg_log('Notifying '.$user->getDisplayName().' of withdrawn decision');
		notify_user($user->getGUID(), elgg_get_site_entity()->getGUID(), $subject, $message,
			$params = ['object' => $object, 'action' => 'withdraw_decision', 'summary' => $summary],
			$methods_override = 'email');
	}

	elgg_set_ignore_access($ia);
}

function allocations_updated_trigger_events($event, $object_type, $object) {
	$request_guids = $object['requests'];
	$ia = elgg_set_ignore_access();
	foreach($request_guids as $request_guid) {
		$request = get_entity($request_guid);
		elgg_trigger_event('allocations_updated', 'object', $request);
	}
	elgg_set_ignore_access($ia);
}

function modify_ccreq_subscriptions_for_notification($hook, $type, $subscriptions, $params) {
	if(in_array($params['event']->getAction(), ["decide", "allocations_updated"])) {
		$entity = $params['event']->getObject();
		$subscriptions[$entity->getOwnerGUID()] = ['email'];
	}
	return $subscriptions;
}

function request_decided_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $entity->getOwnerEntity();

	$ia = elgg_set_ignore_access();

	$notification->subject = elgg_echo('nihcp_notifications:notify:decide:subject', [$entity->status]);

	$notification->body = elgg_echo('nihcp_notifications:notify:decide:body', [$owner->getDisplayName(), $entity->getRequestId(), $entity->project_title, strtolower($entity->status)]);

	$notification->summary = elgg_echo('nihcp_notifications:notify:decide:summary', [$entity->getRequestId(), $entity->status]);

	elgg_set_ignore_access($ia);

	return $notification;
}

function allocations_updated_prepare_notifications($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $entity->getOwnerEntity();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$ia = elgg_set_ignore_access();

	$notification->subject = elgg_echo('nihcp_notifications:notify:allocations_updated:subject');
	$notification->body = elgg_echo('nihcp_notifications:notify:allocations_updated:body', [$owner->getDisplayName()]) . "\n";
	//$body .= "<table>";
	/*if(!empty($active_requests)) {
		//$body .= "<tr><th>CCREQ ID</th><th>Vendor</th><th>Credit Remaining</th></tr>";
		//$body .= "\nCCREQ ID\tVendor\tCredit Remaining\n";
	}*/

	$new_allocations = \Nihcp\Entity\CommonsCreditAllocation::getAllocations($entity->getGUID());
	if (!empty($new_allocations)) {
		//$body .= "<tr><td>{$request->getRequestId()}:</td><td></td><td></td></tr>";
		$notification->body .= "\t{$entity->getRequestId()}:\n";
	}

	foreach ($new_allocations as $allocation) {
		$vendor_name = \Nihcp\Entity\CommonsCreditVendor::getByVendorId($allocation->vendor)->getDisplayName();
		/*$body .= '<tr><td></td>';
		$body .= "<td>$vendor_name</td>";
		$body .= "<td>$allocation->credit_remaining</td>";
		$body .= "</tr>";*/
		$notification->body .= "\t\t$vendor_name:\t$allocation->credit_remaining\n";
	}
	//$body .= "</table>";

	$notification->summary = elgg_echo('nihcp_notifications:notify:allocations_updated:summary', [$entity->getRequestId()]);

	elgg_set_ignore_access($ia);

	return $notification;
}


function ccreq_stats_cron($hook, $entity_type, $returnvalue, $params){
    $status = "";

    $new = get_new_submissions_count();
    $nr = get_not_reviewed_in_last_week_count();
    $tnr = get_total_not_reviewed_count();

    $subj = elgg_echo('nihcp_notifications:notify:ccreq_stats_subj');
    $new24 = elgg_echo('nihcp_notifications:notify:ccreq_stats_daily',[$new]);
    $noReview = elgg_echo('nihcp_notifications:notify:ccreq_stats_weekly',[$nr]);
    $totalNoReview = elgg_echo('nihcp_notifications:notify:ccreq_stats_overall',[$tnr]);
    $msg = $new24 . "\n" . $noReview . "\n" . $totalNoReview;

    $options = array(
        "type" => "group",
        "limit" => 1,
        "attribute_name_value_pairs" => [
            ["name"=>"name","value"=>\Nihcp\Manager\RoleManager::TRIAGE_COORDINATOR],
        ],
        //"name" => \Nihcp\Manager\RoleManager::HELP_ADMIN,

    );
    $ia = elgg_set_ignore_access();
    $group = elgg_get_entities_from_attributes($options)[0];
    $users = $group->getMembers(array("limit"=>0));
    foreach($users as $user){
        notify_user($user->getGuid(),elgg_get_site_entity()->getGUID(),$subj,$msg);
    }
    elgg_set_ignore_access($ia);

    return $returnvalue . $status;
}

//todo: make this a static function in the ccreq object? (needs some thought as to the best location)
function get_new_submissions_count(){
    $retVal = 0;
    $time = time()-(60*60*24); //any created in the last 24 hours
    $request = \Nihcp\Entity\CommonsCreditRequest::getByCycle();

    //I tried doing a custom elgg_get_entities_by_metadata call with the time_created comparison as a name-value pair
    //but it wasn't working for whatever reason (likely my error in calling it).  Rather than spend more time debugging
    //it I'll just 'brute force' it.  Running once a day shouldn't be a big deal.
    $ia = elgg_set_ignore_access();
    foreach($request as $r){
        if($r->status == \Nihcp\Entity\CommonsCreditRequest::SUBMITTED_STATUS) {
            if ($r->time_created > $time) {
                $retVal += 1;
            }
        }
    }
    elgg_set_ignore_access($ia);
    return $retVal;
}

//todo: make this a static function in the ccreq object? (needs some thought as to the best location)
function get_not_reviewed_in_last_week_count(){
    $retVal = 0;
    $request = \Nihcp\Entity\CommonsCreditRequest::getByCycle();
    $time = time()-(60*60*24*7); //any created in the last 24 hours
    $ia = elgg_set_ignore_access();
    foreach($request as $r){
        if($r->status === \Nihcp\Entity\CommonsCreditRequest::SUBMITTED_STATUS) {
            if ($r->time_created > $time) {
                $retVal += 1;
            }
        }
    }
    elgg_set_ignore_access($ia);

    return $retVal;
}

//todo: make this a static function in the ccreq object? (needs some thought as to the best location)
function get_total_not_reviewed_count(){
    $ia = elgg_set_ignore_access();
    $requests = elgg_get_entities_from_metadata([
        'type' => 'object',
        'subtype' => \Nihcp\Entity\CommonsCreditRequest::SUBTYPE,
        'limit' => 0,
        'count' => true,
        'metadata_name_value_pairs' => [
            ['name' => 'status', 'value' => \Nihcp\Entity\CommonsCreditRequest::SUBMITTED_STATUS, 'operand' => "="]
        ],
    ]);
    elgg_set_ignore_access($ia);
    return $requests;
}

//todo: think about putting this in user_support?
function helpdesk_stats_cron($hook, $entity_type, $returnvalue, $params){
    $c = get_open_tickets_in_last_day_count();
    $c2 = get_open_tickets_in_last_week_count();
    $c3 = get_total_open_tickets_count();

    $subj = elgg_echo('nihcp_notifications:notify:helpdesk_stats_subj');
    $new24 = elgg_echo('nihcp_notifications:notify:helpdesk_stats_daily',[$c]);
    $new7 = elgg_echo('nihcp_notifications:notify:helpdesk_stats_weekly',[$c2]);
    $totalOpen = elgg_echo('nihcp_notifications:notify:helpdesk_stats_overall',[$c3]);
    $msg = $new24 . "\n" . $new7 . "\n" . $totalOpen;
    //get helpdesk
    $options = array(
        "type" => "group",
        "limit" => 1,
        "attribute_name_value_pairs" => [
            ["name"=>"name","value"=>\Nihcp\Manager\RoleManager::HELP_ADMIN],
        ],
    );
    $ia = elgg_set_ignore_access();
    $group = elgg_get_entities_from_attributes($options)[0];
    $users = $group->getMembers(array("limit"=>0));
    foreach($users as $user){
        notify_user($user->getGuid(),elgg_get_site_entity()->getGUID(),$subj,$msg);
    }
    elgg_set_ignore_access($ia);


    return $returnvalue;
}

function get_open_tickets_in_last_day_count(){
    $retVal = 0;
    $time = time()-(60*60*24); //any created in the last 24 hours
    $options = array(
        "type" => "object",
        "subtype" => UserSupportTicket::SUBTYPE,
        "full_view" => false,
        "metadata_name_value_pairs" => [
            ["name"=>"status","value" => UserSupportTicket::OPEN,"operand"=>"="],
        ],
        "limit" =>0,
    );
    $ia = elgg_set_ignore_access();
    $results = elgg_get_entities_from_metadata($options);
    foreach($results as $r){
        if ($r->time_created > $time) {
            $retVal += 1;
        }
    }
    elgg_set_ignore_access($ia);

    return $retVal;
}

function get_open_tickets_in_last_week_count(){
    $retVal = 0;
    $time = time()-(60*60*24*7); //any created in the last 7 days
    $mOptions = array(
        "type" => "object",
        "subtype" => UserSupportTicket::SUBTYPE,
        "full_view" => false,
        "metadata_name_value_pairs" => [
            ["name"=>"status","value" => UserSupportTicket::OPEN,"operand"=>"="],
        ],
        "limit" =>0,
    );

    $ia = elgg_set_ignore_access();
    $results = elgg_get_entities_from_metadata($mOptions);
    foreach($results as $r){
        if ($r->time_created > $time) {
            $retVal += 1;
        }
    }
    elgg_set_ignore_access($ia);

    return $retVal;
}

function get_total_open_tickets_count(){
    $options = array(
        "type" => "object",
        "subtype" => UserSupportTicket::SUBTYPE,
        "full_view" => false,
        "metadata_name_value_pairs" => [
            ["name"=>"status","value" => UserSupportTicket::OPEN,"operand"=>"="],
        ],
        "limit" =>0,
        "count" => true,
    );
    $ia = elgg_set_ignore_access();
    $results = elgg_get_entities_from_metadata($options);
    elgg_set_ignore_access($ia);
    return $results;
}