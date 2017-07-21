<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 
namespace Nihcp\Entity;

class Feedback extends \ElggObject {
	const SUBTYPE = 'feedback';

	const RELATIONSHIP_CCREQ_TO_FEEDBACK = "ccreq_to_feedback";

	const COMMENT_CHAR_LIMIT = 500;

	public function initializeAttributes() {
		parent::initializeAttributes();

		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

	public static function getFeedback($request_guid) {
		$entities = elgg_get_entities_from_relationship(array(
				'relationship' => self::RELATIONSHIP_CCREQ_TO_FEEDBACK,
				'relationship_guid' => $request_guid,
				'type' => 'object',
		));

		$result = empty($entities) ? null : $entities[0]->getGUID();

		return $result;
	}
}