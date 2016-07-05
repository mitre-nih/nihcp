<?php

namespace Nihcp\Entity;

class CommonsCreditRequest extends \ElggObject {

	const SUBTYPE = 'commonscreditrequest';

    const DATASETS = 'datasets';
    const APPLICATIONS_TOOLS = 'applications_tools';
    const WORKFLOWS = 'workflows';

    // status constants
    const COMPLETED_STATUS = 'Completed';
	const APPROVED_STATUS = 'Approved';
    const DENIED_STATUS = 'Denied';
	const SUBMITTED_STATUS = 'Submitted';
	const DRAFT_STATUS = 'Draft';
	const WITHDRAWN_STATUS = 'Withdrawn';

    // form field max lengths
    const PROJECT_TITLE_MAX_LENGTH = 200;
    const GRANT_LINKAGE_MAX_LENGTH = 200;
    const PROPOSED_RESEARCH_MAX_LENGTH = 3000;
    const PRODUCTIVITY_GAIN_MAX_LENGTH = 300;
    const UNIQUE_RESOURCE_ACCESS_MAX_LENGTH = 300;
    const PAST_EXPERIENCE__MAX_LENGTH = 500;
    const DATASETS_MAX_LENGTH = 1500;
    const APPLICATIONS_TOOLS_MAX_LENGTH = 1500;
    const WORKFLOWS_MAX_LENGTH = 1500;
    const DIGITAL_OBJECT_RETENTION_PLAN_MAX_LENGTH = 300;
    const OTHER_EXPECTED_COSTS_EXPLANATION = 300;

	public function initializeAttributes() {
		parent::initializeAttributes();

		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

	public static function isValidStatus($s) {
		$statuses = [self::COMPLETED_STATUS, self::APPROVED_STATUS, self::DENIED_STATUS, self::SUBMITTED_STATUS, self::DRAFT_STATUS, self::WITHDRAWN_STATUS];
		return !(array_search($s, $statuses) === false);
	}

	public function getURL() {
		return elgg_get_site_url() . "nihcp_commons_credit_request/request/{$this->guid}";
	}

    public function isEditable() {
        return $this->status != self::COMPLETED_STATUS
            && $this->status != self::APPROVED_STATUS
            && $this->status != self::DENIED_STATUS;
    }

    public function isComplete() {
        return !$this->isEditable();
    }

    public function hasDatasets() {
        return !empty($this->datasets);
    }

    public function hasApplicationsTools() {
        return !empty($this->applications_tools);
    }

    public function hasWorkflows() {
        return !empty($this->workflows);
    }

    public function getExpectedCostTotal() {
        return $this->server_compute_expected_cost + $this->storage_expected_cost + $this->network_services_expected_cost + $this->web_servers_expected_cost + $this->databases_expected_cost + $this->other_expected_cost;
    }

	// Get the form values and save them
	public static function saveRequestFromForm($new_request, $submit=false) {

        $new_request->status = $submit ? 'Submit' : 'Draft';
        $new_request->submission_date = $submit ? date('n/j/Y') : null;

        $new_request->project_title = htmlspecialchars(get_input('project_title', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->grant_linkage = htmlspecialchars(get_input('grant_linkage', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->proposed_research = htmlspecialchars(get_input('proposed_research', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->productivity_gain = htmlspecialchars(get_input('productivity_gain', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->productivity_gain_explanation = htmlspecialchars(get_input('productivity_gain_explanation', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->unique_resource_access = htmlspecialchars(get_input('unique_resource_access', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->unique_resource_access_explanation = htmlspecialchars(get_input('unique_resource_access_explanation', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->past_experience = htmlspecialchars(get_input('past_experience', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->past_experience_explanation = htmlspecialchars(get_input('past_experience_explanation', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->datasets_has_access_restrictions = htmlspecialchars(get_input('datasets_has_access_restrictions', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->datasets = htmlspecialchars(get_input('datasets', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->applications_tools_has_access_restrictions = htmlspecialchars(get_input('applications_tools_has_access_restrictions', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->applications_tools = htmlspecialchars(get_input('applications_tools', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->workflows_has_access_restrictions = htmlspecialchars(get_input('workflows_has_access_restrictions', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->workflows = htmlspecialchars(get_input('workflows', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->digital_object_retention_plan = htmlspecialchars(get_input('digital_object_retention_plan', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->server_compute_expected_cost = htmlspecialchars(get_input('server_compute_expected_cost', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->storage_expected_cost = htmlspecialchars(get_input('storage_expected_cost', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->network_services_expected_cost = htmlspecialchars(get_input('network_services_expected_cost', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->web_servers_expected_cost = htmlspecialchars(get_input('web_servers_expected_cost', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->databases_expected_cost = htmlspecialchars(get_input('databases_expected_cost', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->other_expected_cost = htmlspecialchars(get_input('other_expected_cost', '', false), ENT_QUOTES, 'UTF-8');
        $new_request->other_expected_cost_explanation = htmlspecialchars(get_input('other_expected_cost_explanation', '', false), ENT_QUOTES, 'UTF-8');
		$new_pricing_upload_guid = CommonsCreditRequest::saveUploadFile('pricing_upload');
		if($new_pricing_upload_guid) {
			if(isset($new_request->pricing_upload_guid)) {
				$old_file = get_entity($new_request->pricing_upload_guid);
				if($old_file) $old_file->delete();
				unset($new_request->pricing_upload_guid);
			}
			$new_request->pricing_upload_guid = $new_pricing_upload_guid;
		}
        $new_supplementary_materials_upload_guid = CommonsCreditRequest::saveUploadFile('supplementary_materials_upload');
		if($new_supplementary_materials_upload_guid) {
			if(isset($new_request->supplementary_materials_upload_guid)) {
				$old_file = get_entity($new_request->supplementary_materials_upload_guid);
				if($old_file) $old_file->delete();
				unset($new_request->supplementary_materials_upload_guid);
			}
			$new_request->supplementary_materials_upload_guid = $new_supplementary_materials_upload_guid;
		}

		return $new_request->guid ?: $new_request->save();
    }

	private static function saveUploadFile($file_form_name) {

        // check if upload attempted and failed
        if (!empty($_FILES[$file_form_name]['name']) && $_FILES[$file_form_name]['error'] != 0) {
            $error = elgg_get_friendly_upload_error($_FILES[$file_form_name]['error']);
            register_error($error);
            forward(REFERER);
        }

        // must have a file if a new file upload
        if (empty($_FILES[$file_form_name]['name'])) {
			return false;
        }

        $file = new \FilePluginFile();
        $file->subtype = "file";

        // if no title on new upload, grab filename
        if (empty($title)) {
            $title = htmlspecialchars($_FILES[$file_form_name]['name'], ENT_QUOTES, 'UTF-8');
        }

        $file->title = $title;

		// we have a file upload, so process it
        if (isset($_FILES[$file_form_name]['name']) && !empty($_FILES[$file_form_name]['name'])) {

            $prefix = "file/";

            $filestorename = elgg_strtolower(time().$_FILES[$file_form_name]['name']);

            $file->setFilename($prefix . $filestorename);
            $file->originalfilename = $_FILES[$file_form_name]['name'];
            $mime_type = $file->detectMimeType($_FILES[$file_form_name]['tmp_name'], $_FILES[$file_form_name]['type']);

            $file->setMimeType($mime_type);
            $file->simpletype = elgg_get_file_simple_type($mime_type);

            // Open the file to guarantee the directory exists
            $file->open("write");
            $file->close();
            move_uploaded_file($_FILES[$file_form_name]['tmp_name'], $file->getFilenameOnFilestore());

            $saved = $file->save();

            // if image, we need to create thumbnails (this should be moved into a function)
            if ($saved && $file->simpletype == "image") {
                $file->icontime = time();

                $thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 60, 60, true);
                if ($thumbnail) {
                    $thumb = new \ElggFile();
                    $thumb->setMimeType($_FILES[$file_form_name]['type']);

                    $thumb->setFilename($prefix."thumb".$filestorename);
                    $thumb->open("write");
                    $thumb->write($thumbnail);
                    $thumb->close();

                    $file->thumbnail = $prefix."thumb".$filestorename;
                    unset($thumbnail);
                }

                $thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 153, 153, true);
                if ($thumbsmall) {
                    $thumb->setFilename($prefix."smallthumb".$filestorename);
                    $thumb->open("write");
                    $thumb->write($thumbsmall);
                    $thumb->close();
                    $file->smallthumb = $prefix."smallthumb".$filestorename;
                    unset($thumbsmall);
                }

                $thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 600, 600, false);
                if ($thumblarge) {
                    $thumb->setFilename($prefix."largethumb".$filestorename);
                    $thumb->open("write");
                    $thumb->write($thumblarge);
                    $thumb->close();
                    $file->largethumb = $prefix."largethumb".$filestorename;
                    unset($thumblarge);
                }
            } elseif ($file->icontime) {
                // if it is not an image, we do not need thumbnails
                unset($file->icontime);

                $thumb = new \ElggFile();

                $thumb->setFilename($prefix . "thumb" . $filestorename);
                $thumb->delete();
                unset($file->thumbnail);

                $thumb->setFilename($prefix . "smallthumb" . $filestorename);
                $thumb->delete();
                unset($file->smallthumb);

                $thumb->setFilename($prefix . "largethumb" . $filestorename);
                $thumb->delete();
                unset($file->largethumb);
            }
        } else {
            // not saving a file but still need to save the entity to push attributes to database
			$file->save();
        }

        return $saved ? $file->getGUID() : false;

    }

	public function assignToCycle($cycle_guid = 0) {
		if(!$cycle_guid) {
			$cycle_guid = CommonsCreditCycle::getActiveCycleGUID();
		}
		if($cycle_guid === false) {
			return false;
		}
		$ia = elgg_set_ignore_access();
		$cycle = get_entity($cycle_guid);
		elgg_set_ignore_access($ia);
		return $cycle && $cycle instanceof CommonsCreditCycle ? $cycle->assignRequest($this) : false;
	}

    public static function compareROI($ccreq1, $ccreq2) {
        if ($ccreq1 == $ccreq2) {
            return 0;
        } else {
            return FinalScore::calculateROI($ccreq2->guid) - FinalScore::calculateROI($ccreq1->guid);
        }
        
    }

    public static function compareCost($ccreq1, $ccreq2) {
        if ($ccreq1 == $ccreq2) {
            return 0;
        } else {
            return $ccreq1->getExpectedCostTotal() - $ccreq2->getExpectedCostTotal() ;
        }
    }

    public static function sortByRecommendation($requests, $user_guid = 0) {
		if(!$user_guid) {
			$user_guid = elgg_get_logged_in_user_guid();
		}

        $recommend = array();
        $downselect = array();
        $unreviewed = array();

        foreach($requests as $r) {
            $rec = get_entity(FinalRecommendation::getFinalRecommendation($r->guid));
            if (empty($rec) || !FinalRecommendation::isValidStatus($rec->final_recommendation)) {
                $unreviewed[] = $r;
            } else if ($rec->final_recommendation == FinalRecommendation::RECOMMEND) {
                $recommend[] = $r;
            } else { // must be downselect by this point
                $downselect[] = $r;
            }
        }

		if(nihcp_nih_approver_gatekeeper(false, $user_guid)) {
			return array_merge($recommend, $downselect, $unreviewed);
		}
		return array_merge($unreviewed, $recommend, $downselect);

    }

    public static function sortByStatus($requests) {
        $pending = array();
        $approved = array();
        $denied = array();
        $withdrawn = array();

        foreach($requests as $r) {
            $status = $r->status;
            if (!CommonsCreditRequest::isValidStatus($status) || $status == CommonsCreditRequest::WITHDRAWN_STATUS) {
                $withdrawn[] = $r;
            } else if ($status == CommonsCreditRequest::APPROVED_STATUS) {
                $approved[] = $r;
            } else if ($status == CommonsCreditRequest::DENIED_STATUS) {
                $denied[] = $r;
            } else { // pending
                $pending[] = $r;
            }
        }

        return array_merge($pending, $approved, $denied, $withdrawn);


    }



    // sorts given set of requests
    // sort criteria:
    // 1. approved/denied/withdrawn
    // 2. recommended/downselected
    // 3. ROI
    // 4. credit amount (ascending)
    public static function sort($requests, $user_guid = 0) {

        usort($requests, ['self', 'compareCost']);
        usort($requests, ['self', 'compareROI']);
        $requests = self::sortByRecommendation($requests, $user_guid);
        $requests = self::sortByStatus($requests);

        return $requests;
    }

    public static function getAll() {
        return elgg_get_entities([
            'type' => 'object',
            'subtype' => CommonsCreditRequest::SUBTYPE,
        ]);
    }

	public static function getByUser($status = 'all', $user_guid = 0) {
		if(!$user_guid) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		$requests = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditRequest::SUBTYPE,
			'owner_guid' => $user_guid,
			'limit' => 0,
			'metadata_name_value_pairs' => $status !== 'all' && self::isValidStatus($status) ? [
				['name' => 'status', 'value' => $status, 'operand' => '=']
			] : null,
		]);
		return $requests;
	}

	public static function getByCycle($cycle_guid = 0) {
		if(!$cycle_guid) {
			$cycle_guid = CommonsCreditCycle::getActiveCycleGUID();
		}
		$ia = elgg_set_ignore_access();
		$cycle = get_entity($cycle_guid);
		elgg_set_ignore_access($ia);
		if(!$cycle || !$cycle instanceof CommonsCreditCycle) {
			return false;
		}
		return $cycle->getRequests();
	}

	public static function getByUserAndCycle($status = 'all', $user_guid = 0, $cycle_guid = 0) {
		$user_requests = self::getByUser($status, $user_guid);
		$cycle_requests = self::getByCycle($cycle_guid);
		if(!$user_requests || !$cycle_requests) {
			return false;
		}
		$intersect_requests = array_intersect($user_requests, $cycle_requests);
		return $intersect_requests;
	}

	public function getFeedback() {
		return Feedback::getFeedback($this->guid);
	}

	public function __toString() {
		return strval($this->guid);
	}
}