<?php

namespace Nihcp\Entity;

class CommonsCreditRequestDelegation extends \ElggObject {

    const SUBTYPE = 'commonscreditrequestdelegation';
    const DATE_FORMAT = 'Y-m-d';

    const RELATIONSHIP_DELEGATION_OWNER = 'delegation_owner';
    const RELATIONSHIP_DELEGATION_DELEGATED_TO = 'delegation_delegated_to';
    const RELATIONSHIP_CCREQ_TO_DELEGATION = 'ccreq_to_delegation';
    const RELATIONSHIP_CCREQ_TO_DELEGATE = 'ccreq_to_delegate';

    // Delegation statuses
    const DELEGATION_PENDING_STATUS = 'pending';
    const DELEGATION_DELEGATED_STATUS = 'delegated';
    const DELEGATION_INVALID_STATUS = 'invalid';
    const DELEGATION_REVIEW_STATUS = 'review';
    const DELEGATION_DECLINED_STATUS = 'declined';
    const DELEGATION_SUBMITTED_STATUS = 'submitted';

    public function initializeAttributes() {
        parent::initializeAttributes();

        $class = get_class($this);
        $this->attributes['subtype'] = $class::SUBTYPE;
    }

    // To be used with the delegate form + action
    // creates and returns a delegation for the given Commons Credit Request (ccreq)
    public static function createNewDelegation($ccreq) {
        $delegate_email = htmlspecialchars(get_input('delegate_email', '', false), ENT_QUOTES, 'UTF-8');
        $delegate_message = htmlspecialchars(get_input('delegate_message', '', false), ENT_QUOTES, 'UTF-8');


        if (empty($ccreq) || empty($delegate_email)) {
            return false;
        }


        $delegation = New CommonsCreditRequestDelegation();
        $delegation->save();

        // assumes that the ccreq owner (or admin) is initiating this action
        add_entity_relationship($ccreq->getGUID(), self::RELATIONSHIP_CCREQ_TO_DELEGATION, $delegation->getGUID());
        $delegation->setDelegateEmail($delegate_email);
        $delegation->setDelegateMessage($delegate_message);

        $users = get_user_by_email($delegate_email);
        if (empty($users)) {
            elgg_log("User " . elgg_get_logged_in_user_entity()->getDisplayName() . " requested delegate with invalid email " . $delegate_email . ".");
            $delegation->setStatus(self::DELEGATION_INVALID_STATUS);
        } else { // user with given email was found
            $delegate = $users[0];
            $delegation->setStatus(self::DELEGATION_PENDING_STATUS);

            elgg_log("Delegation requested for " . $delegate_email . " by user " . elgg_get_logged_in_user_entity()->getDisplayName());


            self::requestDelegation($delegation);
        }



    }

    // send an email to intended delegate with a link to a form to accept the delegation
    // the link is specific to this delegation of this ccreq by using their guids in the URL
    private static function requestDelegation($delegation_entity) {
        $delegate = self::getDelegateForDelegation($delegation_entity->getGUID());
        $ccreq = self::getCCREQForDelegation($delegation_entity->getGUID());
        $delegation_request_url = elgg_get_site_url() . "nihcp_commons_credit_request/delegate/" . $ccreq->getGUID() . "/request/" . $delegation_entity->getGUID();
        $delegation_entity->setURL($delegation_request_url);
        elgg_echo("Delegation request URL: " . $delegation_request_url);
        // send email to delegate
		$to = get_user_by_email($delegation_entity->getDelegateEmail())[0]->getGUID();
		$from = elgg_get_site_entity()->getGUID();
		$subject = elgg_echo('nihcp_commons_credit_request:delegate:request:subject');

        $message = '';

        if ($delegation_entity->getDelegateMessage()) {
            $message .= "Message from " . elgg_get_logged_in_user_entity()->getDisplayName() . "\r\n"
                . $delegation_entity->getDelegateMessage()."\r\n\r\n";
        }

		$message .= "Delegation Guidelines\r\n"
            . elgg_echo('nihcp_commons_credit_request:delegate:instructions')."\r\n"
            . $delegation_request_url;
		notify_user($to, $from, $subject, $message, $methods_override=["email"]);
    }

	public static function revokeDelegation($delegation_guid) {
		$delegation = get_entity($delegation_guid);
        $email_users = get_user_by_email($delegation->getDelegateEmail());
		if (empty($delegation) || !($delegation instanceof CommonsCreditRequestDelegation)) {
			return false;
		}
		$ccreq = CommonsCreditRequestDelegation::getCCREQForDelegation($delegation_guid);

		// send email to delegate
        if (!empty($email_users)) {
            $to = $email_users[0]->getGUID();
            $from = elgg_get_site_entity()->getGUID();
            $subject = elgg_echo('nihcp_commons_credit_request:delegate:revoke:subject');
            $message = elgg_echo('nihcp_commons_credit_request:delegate:revoke:description', [elgg_get_logged_in_user_entity()->getDisplayName(), $ccreq->project_title]);
            elgg_remove_subscription($to, 'email', $ccreq->getGUID());

            notify_user($to, $from, $subject, $message, $methods_override = ["email"]);
        }
		remove_entity_relationships($ccreq->getGUID(), CommonsCreditRequestDelegation::RELATIONSHIP_CCREQ_TO_DELEGATE);
		return $delegation->delete();
	}

    public static function getDelegateForCCREQ($ccreq_guid) {
        $results = elgg_get_entities_from_relationship(array(
            'type' => 'user',
            'relationship' => CommonsCreditRequestDelegation::RELATIONSHIP_CCREQ_TO_DELEGATE,
            'relationship_guid' => $ccreq_guid,
        ));
        return empty($results) ? false : $results[0];
    }

    public static function getDelegateForDelegation($delegation_guid) {
        $results = elgg_get_entities_from_relationship(array(
            'type' => 'user',
            "relationship" => self::RELATIONSHIP_DELEGATION_DELEGATED_TO,
            "relationship_guid" => $delegation_guid
        ));
        return empty($results) ? false : $results[0];
    }

    public static function getCCREQForDelegation($delegation_guid) {
        $results = elgg_get_entities_from_relationship(array(
            'type' => 'object',
            'subtype' => CommonsCreditRequest::SUBTYPE,
            "relationship" => self::RELATIONSHIP_CCREQ_TO_DELEGATION,
            "relationship_guid" => $delegation_guid,
            "inverse_relationship" => true
        ));
        return empty($results) ? false : $results[0];
    }

    public static function getDelegationForCCREQ($ccreq_guid) {
        $results = elgg_get_entities_from_relationship(array(
            'type' => 'object',
            'subtype' => self::SUBTYPE,
            "relationship" => self::RELATIONSHIP_CCREQ_TO_DELEGATION,
            "relationship_guid" => $ccreq_guid
        ));
        return empty($results) ? false : $results[0];
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getDelegateEmail() {
        return $this->delegate_email;
    }

    public function setDelegateEmail($delegate_email) {
        $this->delegate_email = $delegate_email;
    }

    public function getDelegateMessage() {
        return $this->delegate_message;
    }

    public function setDelegateMessage($delegate_message) {
        $this->delegate_message = $delegate_message;
    }

}