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
 * The helper class to support tickets
 *
 * @package User_Support
 */
class UserSupportTicket extends ElggObject {
	const SUBTYPE = "support_ticket";
	const OPEN = "open";
	const CLOSED = "closed";
	
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
		$this->attributes["access_id"] = ACCESS_PRIVATE;
		
		$this->status = self::OPEN;
	}
	
	/**
	 * Get the URL for this entity
	 *
	 * @see ElggEntity::getURL()
	 *
	 * @return string
	 */
	public function getURL() {
		$title = $this->title;
		if (strlen($title) > 50) {
			$title = elgg_get_excerpt($title, 50);
		}
		$title = str_replace("...", "", $title);
		
		return elgg_normalize_url("user_support/support_ticket/" . $this->getGUID() . "/" . elgg_get_friendly_title($title));
	}
	
	/**
	 * Get the status of the ticket
	 *
	 * @return string
	 */
	public function getStatus() {
		$result = self::OPEN;
		
		if ($this->status == self::CLOSED) {
			$result = self::CLOSED;
		}
		
		return $result;
	}
	
	/**
	 * Set the status of the ticket
	 *
	 * @param string $status the new status
	 *
	 * @return bool
	 */
	public function setStatus($status) {
		$result = false;
		
		switch ($status) {
			case self::OPEN:
			case self::CLOSED:
				$this->status = $status;
				$result = true;
				break;
		}
		
		return $result;
	}
	
	/**
	 * Get the icon URL
	 *
	 * @param string $size the size of the image
	 *
	 * @see ElggEntity::getIconURL()
	 *
	 * @return string
	 */
	public function getIconURL($size = "medium") {
		
		$support_type = strtolower($this->support_type);
		if (!in_array($support_type, array("bug", "request", "question"))) {
			$support_type = "question";
		}
		
		switch ($size) {
			case "tiny":
				$result = "mod/user_support/_graphics/" . $support_type . "/tiny.png";
				break;
			default:
				$result = "mod/user_support/_graphics/" . $support_type . "/small.png";
				break;
		}
		
		return elgg_normalize_url($result);
	}
}
