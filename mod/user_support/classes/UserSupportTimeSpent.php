<?php
/*
Copyright 2017 The MITRE Corporation

This software was written for the NIH Commons Credit Portal.
General questions can be forwarded to:
 
opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
 

/**
 * Class UserSupportTimeSpent
 *
 * Time is stored as minutes in the "time_spent" property set in the corresponding action file.
 *
 * TIME_INTERVAL: the number of minutes to round up to for each helpdesk admin's time spent on a give ticket.
 */
class UserSupportTimeSpent extends ElggObject {

    const SUBTYPE = "time_spent";

    const TIME_INTERVAL = 10;

    const RELATIONSHIP_TICKET_TO_TIME_SPENT = "time_spent_on_ticket";
    const RELATIONSHIP_USER_TO_TIME_SPENT = "time_spent_by_user";

	/**
	 * Initialize base attributes
	 *
	 * @see ElggObject::initializeAttributes()
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes["subtype"] = self::SUBTYPE;
	}

	public function getTimeSpent() {
		return $this->time_spent;
	}

	public function setTimeSpent($time_spent) {
		$this->time_spent = $time_spent;
	}

    // Tries to find the amount of time a certain helpdesk admin user has spent on a given user support ticket.
    // returns null if ticket doesn't exist or if user didn't record time spent on it
    public static function get_time_spent_for_ticket_by_user($ticket_guid, $user_guid) {

        // get the different times spent on this ticket
        $times_spent_for_ticket = elgg_get_entities_from_relationship(array(
            'relationship' => UserSupportTimeSpent::RELATIONSHIP_TICKET_TO_TIME_SPENT,
            'relationship_guid' => $ticket_guid,
			'type' => 'object',
			'subtype' => UserSupportTimeSpent::SUBTYPE
        ));

        // get the different times spent by currently logged-in helpdesk admin
        $times_spent_for_user = elgg_get_entities_from_relationship(array(
            'relationship' => UserSupportTimeSpent::RELATIONSHIP_USER_TO_TIME_SPENT,
            'relationship_guid' => $user_guid,
			'type' => 'object',
			'subtype' => UserSupportTimeSpent::SUBTYPE
        ));

        foreach ($times_spent_for_ticket as $time_spent_ticket) {
            foreach ($times_spent_for_user as $time_spent_user) {
                if ($time_spent_ticket->getGUID() === $time_spent_user->getGUID()) {
                    return $time_spent_ticket;
                }
            }
        }

        // didn't find an existing time spent by this helpdesk admin on this ticket
        return null;
    }

	public static function sum_time_spent_for_ticket($ticket_guid) {
		// get the different times spent on this ticket
		$times_spent_for_ticket = elgg_get_entities_from_relationship(array(
			'relationship' => UserSupportTimeSpent::RELATIONSHIP_TICKET_TO_TIME_SPENT,
			'relationship_guid' => $ticket_guid,
			'type' => 'object',
			'subtype' => UserSupportTimeSpent::SUBTYPE
		));

		$time_total = 0;
		foreach ($times_spent_for_ticket as $time_spent_ticket) {
			$time_total += $time_spent_ticket->getTimeSpent();
		}
		return $time_total;
	}
}