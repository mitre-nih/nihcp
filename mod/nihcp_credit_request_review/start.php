<?php

elgg_register_event_handler('init', 'system', 'credit_request_review_init');

function credit_request_review_init() {
	// Rename this function based on the name of your plugin and update the
	// elgg_register_event_handler() call accordingly

	elgg_define_js('autoNumeric', [
			"src" => elgg_get_site_url() . "mod/nihcp_commons_credit_request/vendor/bower-asset/autoNumeric/autoNumeric.js"
	]);

	// Register a script to handle (usually) a POST request (an action)
	$action_path = __DIR__ . '/actions';
	elgg_register_action('align-cc-obj', "$action_path/nihcp_credit_request_review/align_cc_obj.php");
	elgg_register_action('general-score', "$action_path/nihcp_credit_request_review/general_score.php");
	elgg_register_action('assign-de', "$action_path/nihcp_credit_request_review/assign_de.php");
	elgg_register_action('risk-benefit-score', "$action_path/nihcp_credit_request_review/risk_benefit_score.php");
	elgg_register_action('final-score', "$action_path/nihcp_credit_request_review/final_score.php");
	elgg_register_action('final-recommendation', "$action_path/nihcp_credit_request_review/final_recommendation.php");
	elgg_register_action('decide-request', "$action_path/nihcp_credit_request_review/decide_request.php");
	elgg_register_action('credit_request_cycle/edit', "$action_path/nihcp_credit_request_cycle/save.php");
	elgg_register_action('credit_request_cycle/delete', "$action_path/nihcp_credit_request_cycle/delete.php");

	// Extend the main CSS file
	elgg_extend_view('css/elements/layout', 'css/nihcp_credit_request_review/credits_request_review');

	elgg_register_ajax_view('nihcp_credit_request_review/overview/requests');
	elgg_register_ajax_view('nihcp_credit_request_review/decide-request');

	// Add a new widget
	elgg_register_widget_type('nihcp_credit_request_review', elgg_echo("nihcp_credit_request_review"), elgg_echo("nihcp_credit_request_review:widget:description"));

	elgg_register_page_handler('nihcp_credit_request_review', 'nihcp_credit_request_review_page_handler');

	elgg_register_page_handler('credit_request_cycle', 'nihcp_credit_request_cycle_page_handler');
}


function nihcp_credit_request_review_page_handler($page) {
	$crr_dir = elgg_get_plugins_path() . 'nihcp_credit_request_review/pages/nihcp_credit_request_review';

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	if (isset($page[1])) {
		set_input('request_guid', $page[1]);
	}
	switch ($page_type) {
		case 'overview':
			include "$crr_dir/overview.php";
			break;
		case 'review':
			include "$crr_dir/review.php";
			break;
		case 'align-cc-obj':
			include "$crr_dir/align-cc-obj.php";
			break;
		case 'general-score':
			$class = $page[2];
			if (!($class == 'datasets' || $class == 'applications_tools' || $class == 'workflows')) {
				return false;
			}
			set_input('review_class', $class);
			include "$crr_dir/general-score.php";
			break;
		case 'final-recommendation':
			include "$crr_dir/final-recommendation.php";
			break;
		case 'general-score-overview':
			include "$crr_dir/general-score-overview.php";
			break;
		case 'risk-benefit-score-overview':
			include "$crr_dir/risk-benefit-score-overview.php";
			break;
		case 'risk-benefit-score':
			set_input('rb_guid', $page[2]);
			include "$crr_dir/risk-benefit-score.php";
			break;
		case 'assign-de':
			include "$crr_dir/assign-de.php";
			break;
		case 'final-score-overview':
			include "$crr_dir/final-score-overview.php";
			break;
		case 'final-score':
			$class = $page[2];
			if (!($class == 'datasets' || $class == 'applications_tools' || $class == 'workflows')) {
				return false;
			}
			set_input('review_class', $class);
			include "$crr_dir/final-score.php";
			break;
		case 'feedback':
			include "$crr_dir/feedback.php";
			break;
		case 'all':
			include "$crr_dir/overview.php";
			break;
		default:
			return false;
	}
	return true;
}

function nihcp_credit_request_cycle_page_handler($page) {
	$cycle_dir = elgg_get_plugins_path() . 'nihcp_credit_request_review/pages/nihcp_credit_request_cycle';
	$page_type = $page[0];
	if (isset($page[1])) {
		set_input('cycle_guid', $page[1]);
	}
	switch ($page_type) {
		case 'all':
			include "$cycle_dir/overview.php";
			break;
		case 'edit':
			include "$cycle_dir/edit.php";
			break;
		default:
			include "$cycle_dir/overview.php";
			break;
	}
	return true;
}