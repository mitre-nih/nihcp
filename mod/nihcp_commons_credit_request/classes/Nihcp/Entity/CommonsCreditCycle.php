<?php

namespace Nihcp\Entity;

class CommonsCreditCycle extends \ElggObject {

	const SUBTYPE = 'commonscreditcycle';
	const RELATIONSHIP_CCREQ_TO_CYCLE = 'requested_in';

	public function initializeAttributes() {
		parent::initializeAttributes();

		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

//	public function getURL() {
//		return "/commons_credit_cycle/view/{$this->guid}";
//	}

	// Get the form values and save them
	public static function saveCycleFromForm($new_cycle) {
		$ia = elgg_set_ignore_access();
		$proposed_start = htmlspecialchars(get_input('application_start', '', false), ENT_QUOTES, 'UTF-8');
		$proposed_finish = htmlspecialchars(get_input('application_finish', '', false), ENT_QUOTES, 'UTF-8');
		$proposed_threshold = htmlspecialchars(get_input('stratification_threshold', '', false), ENT_QUOTES, 'UTF-8');
		if($new_cycle->guid && $new_cycle->isActive() && !elgg_is_admin_logged_in()) {
			$now = date('Y-m-d');
			if(($proposed_start !== $new_cycle->start) || ($proposed_threshold !== $new_cycle->threshold) || ($proposed_finish < $now)) {
				register_error('Cannot change start date or threshold or move finish date to before today on an active cycle');
				elgg_set_ignore_access($ia);
				return false;
			}
		}
        $new_cycle->start = $proposed_start;
		$new_cycle->finish = $proposed_finish;
		$new_cycle->threshold = $proposed_threshold;
		$saved = false;
		if($new_cycle->isValid(true)) {
			$saved = $new_cycle->save() ? true : false;
		}
		elgg_set_ignore_access($ia);
		return $saved ? $new_cycle->getGUID() : false;
    }

	public function isValid($show_error = false) {
		$isSane = $this->sanityCheck();
		if($show_error && !$isSane) {
			register_error('Invalid inputs');
		}
		$hasOverlap = $this->hasOverlap();
		if($show_error && $hasOverlap) {
			register_error("Cycle period cannot overlap an existing cycle");
		}
		return $isSane && !$hasOverlap;
	}

	private static function validateDate($date) {
		$d = \DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') === $date;
	}

	private function sanityCheck() {
		return $this->validateDate($this->start) && $this->validateDate($this->finish) && is_numeric($this->threshold);
	}

	private function hasOverlap() {
		$ia = elgg_set_ignore_access();
		$starts_during_cycles = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditCycle::SUBTYPE,
			'limit' => 2,
			'metadata_name_value_pairs' => [
				['name' => 'start', 'value' => $this->start, 'operand' => '<='],
				['name' => 'finish', 'value' => $this->start, 'operand' => '>='],
			]
		]);
		$ends_during_cycles = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditCycle::SUBTYPE,
			'limit' => 2,
			'metadata_name_value_pairs' => [
				['name' => 'start', 'value' => $this->finish, 'operand' => '<='],
				['name' => 'finish', 'value' => $this->finish, 'operand' => '>='],
			]
		]);
		elgg_set_ignore_access($ia);
		$occurs_during_cycles = array_unique(array_merge($starts_during_cycles, $ends_during_cycles));
		return empty($occurs_during_cycles) ? false : !(sizeof($occurs_during_cycles) === 1 && $occurs_during_cycles[0]->guid === $this->guid);
	}

	public function __toString() {
		return strval($this->guid);
	}

	// if $now is not set to a valid date string in Y-m-d, we use the current date
	public static function getActiveCycleGUID($now = true) {
		if(!$now || !CommonsCreditCycle::validateDate($now)) {
			$now = date('Y-m-d');
		}
		$ia = elgg_set_ignore_access();
		$result = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditCycle::SUBTYPE,
			'limit' => 1,
			'metadata_name_value_pairs' => [
				['name' => 'start', 'value' => $now, 'operand' => '<='],
				['name' => 'finish', 'value' => $now, 'operand' => '>=']
			]
		]);
		elgg_set_ignore_access($ia);
		if(empty($result)) {
			return false;
		}
		if(count($result) > 1) {
			register_error('Found an unexpected number of cycles');
		}
		return $result[0]->guid;
	}

	public static function getCycles($omit_future = false) {
		$now = date('Y-m-d');
		$ia = elgg_set_ignore_access();
		$result = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditCycle::SUBTYPE,
			'limit' => 0,
			'metadata_name_value_pairs' => $omit_future ? [
				['name' => 'start', 'value' => $now, 'operand' => '<']
			] : null,
			'order_by_metadata' => array(
				'name' => 'start',
				'direction' => ASC
			)
		]);
		elgg_set_ignore_access($ia);
		return $result;
	}

	public function assignRequest($request) {
		return add_entity_relationship($request->guid, CommonsCreditCycle::RELATIONSHIP_CCREQ_TO_CYCLE, $this->guid);
	}

	public function getRequests() {
		$ia = elgg_set_ignore_access();
		$requests = elgg_get_entities_from_relationship([
			'type' => 'object',
			'subtype' => CommonsCreditRequest::SUBTYPE,
			'relationship' => CommonsCreditCycle::RELATIONSHIP_CCREQ_TO_CYCLE,
			'relationship_guid' => $this->guid,
			'inverse_relationship' => true,
		]);
		elgg_set_ignore_access($ia);
		return $requests;
	}
	
	public function isActive() {
		$now = date('Y-m-d');
		if($now >= $this->start && $now <= $this->finish) {
			return true;
		}
		return false;
	}

	public function canEdit($user_guid = 0) {
		if($user_guid === 0) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		if(nihcp_role_gatekeeper([\Nihcp\Manager\RoleManager::TRIAGE_COORDINATOR], false, $user_guid)) {
			return true;
		}
		return false;
	}

	public function canDelete($user_guid = 0) {
		if($user_guid === 0) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		if(elgg_is_admin_user($user_guid)) {
			return true;
		}

		$ccreqs = $this::getRequests();

		return nihcp_role_gatekeeper([\Nihcp\Manager\RoleManager::TRIAGE_COORDINATOR], false, $user_guid) && !$ccreqs && !$this->isActive();
	}
}