<?php

namespace Nihcp\Entity;


use Elgg\Logger;

class CommonsCreditAllocationFile extends \ElggObject {

    const SUBTYPE = 'commonscreditallocationfile';

    const RELATIONSHIP_FILE_TO_CCA = 'file_updates_cca';

    const NUM_FIELDS = 6;

    public function initializeAttributes() {
        parent::initializeAttributes();

        $class = get_class($this);
        $this->attributes['subtype'] = $class::SUBTYPE;
    }

    public function getAttachmentURL() {
        $file_guid = $this->file_guid;

        return "/nihcp_credit_allocation/attachment?file_guid=$file_guid";
    }

    public function hasAttachment() {
        return ($this->file_guid ? true : false);
    }

    public static function getFields() {
        return [

            'author' => [
                'label' => 'Author',
                'type' => 'input/text',
                'sortable' => true,
            ],
            'description' => [
                'label' => 'Description',
                'type' => 'input/longtext',
            ],
            'upload' => [
                'label' => 'Attachment',
                'type' => 'input/file',
                'required' => true,
            ],
            'created_by' => [ // No data stored, exists to hold table display information
                'label' => 'Created By',
                'type' => 'input/hidden',
                'sortable' => true,
                'function' => function ($item) {
                    if ($item->getOwnerEntity() instanceof \ElggUser) {
                        $created_by = $item->getOwnerEntity();

                        $link = elgg_view('output/url', [
                            'href' => $created_by->getURL(),
                            'text' => $created_by->name,
                            'is_trusted' => true,
                        ]);

                        return "<td>$link</td>";
                    } else {
                        return '<td></td>';
                    }
                },

            ],
            'created_on' => [ // Display Information stored, sorted based on time_created
                'label' => 'Created On',
                'type' => 'input/hidden',
                'sortable' => true,
            ],
        ];
    }

    public function saveUploadedFile() {
        //no file uploaded, since a file is not needed we can skip the rest of this
        if (empty($_FILES['upload']['name'])) {
            return true;
        }

        // check if upload attempted and failed
        if (0 != $_FILES['upload']['error']) {
            $error = elgg_get_friendly_upload_error($_FILES['upload']['error']);

            register_error($error);

            return -1;
        }

        // check whether this is a new file or an edit
        $new_file = true;
        $guid = $this->file_guid;

        if ($guid) {
            $new_file = false;
        }

        if ($new_file) {
            // must have a file if a new file upload
            if (empty($_FILES['upload']['name'])) {
                $error = elgg_echo('file:nofile');
                register_error($error);

                return -1;
            }

            // file must be csv file
            $fileType = strtolower(pathinfo($_FILES['upload']['name'],PATHINFO_EXTENSION));
            if($fileType != "csv") {
                $error = elgg_echo('nihcp_credit_allocation:file:not_csv');
                register_error($error);

                return -1;
            }


            $file = new \FilePluginFile();
            $file->subtype = 'file';

            // grab title from file name
            $title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
        } else {
            // load original file object
            $file = new \FilePluginFile($guid);

            if (!$file) {
                register_error(elgg_echo('file:cannotload'));

                return -1;
            }

            // user must be able to edit file
            if (!$file->canEdit()) {
                register_error(elgg_echo('file:noaccess'));

                return -1;
            }

            // grab title from file name
            $title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
        }

        $file->title = $title;
        $file->description = $this->description;
        $file->access_id = $this->access_id;
        $file->container_guid = $this->guid;

        // we have a file upload, so process it
        if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {
            $prefix = 'file/';

            // if previous file, delete it
            if (false == $new_file) {
                $filename = $file->getFilenameOnFilestore();

                if (file_exists($filename)) {
                    unlink($filename);
                }

                // use same filename on the disk - ensures thumbnails are overwritten
                $filestorename = $file->getFilename();
                $filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
            } else {
                $filestorename = elgg_strtolower(time() . $_FILES['upload']['name']);
            }

            $file->setFilename($prefix . $filestorename);
            $file->originalfilename = $_FILES['upload']['name'];
            $mime_type = $file->detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);

            $file->setMimeType($mime_type);
            $file->simpletype = elgg_get_file_simple_type($mime_type);

            // Open the file to guarantee the directory exists
            $file->open('write');
            $file->close();
            $tmp_name = $_FILES['upload']['tmp_name'];
            move_uploaded_file($tmp_name, $file->getFilenameOnFilestore());



            $file->save();
            $guid = $file->guid;
            $this->file_guid = $guid;

            if (!$this->processFile()) {
                register_error(elgg_echo('nihcp_credit_allocation:validate:ingest_failed'));
                return -1;
            }


            // if image, we need to create thumbnails (this should be moved into a function)
            if ($guid && 'image' == $file->simpletype) {
                $file->icontime = time();

                $thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 60, 60, true);

                if ($thumbnail) {
                    $thumb = new \ElggFile();
                    $thumb->setMimeType($_FILES['upload']['type']);

                    $thumb->setFilename($prefix . 'thumb' . $filestorename);
                    $thumb->open('write');
                    $thumb->write($thumbnail);
                    $thumb->close();

                    $file->thumbnail = $prefix . 'thumb' . $filestorename;
                    unset($thumbnail);
                }

                $thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 153, 153, true);

                if ($thumbsmall) {
                    $thumb->setFilename($prefix . 'smallthumb' . $filestorename);
                    $thumb->open('write');
                    $thumb->write($thumbsmall);
                    $thumb->close();
                    $file->smallthumb = $prefix . 'smallthumb' . $filestorename;
                    unset($thumbsmall);
                }

                $thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 600, 600, false);

                if ($thumblarge) {
                    $thumb->setFilename($prefix . 'largethumb' . $filestorename);
                    $thumb->open('write');
                    $thumb->write($thumblarge);
                    $thumb->close();
                    $file->largethumb = $prefix . 'largethumb' . $filestorename;
                    unset($thumblarge);
                }
            } else if ($file->icontime) {
                // if it is not an image, we do not need thumbnails
                unset($file->icontime);

                $thumb = new \ElggFile();

                $thumb->setFilename($prefix . 'thumb' . $filestorename);
                $thumb->delete();
                unset($file->thumbnail);

                $thumb->setFilename($prefix . 'smallthumb' . $filestorename);
                $thumb->delete();
                unset($file->smallthumb);

                $thumb->setFilename($prefix . 'largethumb' . $filestorename);
                $thumb->delete();
                unset($file->largethumb);
            }
        } else {
            // not saving a file but still need to save the entity to push attributes to database
            $file->save();
        }

        if (!$guid) {
            // failed to save file object - nothing we can do about this
            $error = elgg_echo('file:uploadfailed');

            return -1;
        }

        return $this->file_guid = $guid;
    }

    /**
     * Helper function to ingest CSV file data of credit allocations.
     *
     * This function will attempt to update CommonsCreditAllocation objects based on lines in the CSV file.
     * If a corresponding CCA object doesn't exist, it will show an error to the Credit Admin and set an error status on the CCA object.
     *
     * Returns true if successful ingest; false otherwise.
     *
     */
    private function processFile() {
        $ia = elgg_set_ignore_access();
        $file = new \FilePluginFile($this->file_guid);

        ini_set('auto_detect_line_endings',TRUE);

        // read the file and check the data first before ingesting
        elgg_log("Begin file validation.", Logger::INFO);
        $csv = fopen($file->getFilenameOnFilestore(), 'r');
        if ($csv) {
            fgetcsv($csv); // consume first line, should just be header
            $line_number = 1;

            while (!feof($csv)) {
                $line_number++;
                $line = fgetcsv($csv);

                while (!feof($csv) && empty($line[0])) { // skip blank lines
                    $line_number++;
                    $line = fgetcsv($csv);
                }
                if (feof($csv) || empty($line[0])) { // check if we're at the end of the file
                    break;
                }

                if (!$this->validateData($line, $line_number)) {
                    return false;
                }

            }


        } else {
            elgg_log(elgg_echo('nihcp_credit_allocation:file:problem_opening'), Logger::ERROR);
            register_error(elgg_echo('file:noaccess'));
            return false;
        }
        fclose($csv);
        elgg_log("Finished validating file.", Logger::INFO);

        // if it got to this point, file passed validation so go ahead and ingest the data
        elgg_log("Begin file ingest.", Logger::INFO);
        $csv = fopen($file->getFilenameOnFilestore(), 'r');
        fgetcsv($csv); // consume first line, should just be header
        $line_number = 1;
		$users = [];
        while (!feof($csv)) {
            $line_number++;
            $line = fgetcsv($csv);

            while (!feof($csv) && empty($line[0])) { // skip blank lines
                $line_number++;
                $line = fgetcsv($csv);
            }
            if (feof($csv) || empty($line[0])) { // check if we're at the end of the file
                break;
            }

            array_push($users, $this->ingestLine($line, $line_number));
        }

        elgg_log("Finished ingesting file.", Logger::INFO);
		elgg_trigger_event('ingested', 'object:'.self::SUBTYPE, ['users' => array_unique($users)]);
        elgg_set_ignore_access($ia);
        return true;
    }

    // Checks account data content against the criteria below.
    // Function argument should be array of CSV elements from 1 line.
    // These tests must pass before ingesting the file. If any line fails,
    // then the ingest will be aborted.
    // Returns true if all tests pass; false otherwise.
    // $account_data_array is expected to contain:
    // [0]Account Holder Name,
    // [1]CCREQ ID,
    // [2]Vendor ID,
    // [3]Cloud Account ID,
    // [4]Remaining Credits,
    // [5]Initial Credits
    //
    // - Appropriate number of data elements
    // - CCREQ ID exists in our system
    // - Vendor ID exists in our system
    // - Numeric credit values
    // - Remaining value must be less than initial value
    private function validateData($account_data_array, $line_number) {

        // - Check for Appropriate number of data elements
        if (count($account_data_array) != self::NUM_FIELDS) {
            register_error(elgg_echo("nihcp_credit_allocation:validate:bad_format", [$line_number]));
            elgg_log(elgg_echo("nihcp_credit_allocation:validate:bad_format", [$line_number]), Logger::ERROR);
            return false;
        }

        $ia = elgg_set_ignore_access();
        $request_guid = CommonsCreditRequest::getRequestGUIDfromCCREQID(trim($account_data_array[1]));
        elgg_set_ignore_access($ia);

        // - Check for CCREQ ID exists in our system
        if (!$request_guid) {
            register_error(elgg_echo("nihcp_credit_allocation:validate:no_ccreq", [$line_number]));
            elgg_log(elgg_echo("nihcp_credit_allocation:validate:no_ccreq", [$line_number]), Logger::ERROR);
            return false;
        }

		$vendor_id = trim($account_data_array[2]);
		$vendor = CommonsCreditVendor::getByVendorId($vendor_id);
		if(!$vendor || !elgg_instanceof($vendor, 'object', CommonsCreditVendor::SUBTYPE)) {
			register_error(elgg_echo("nihcp_credit_allocation:validate:no_vendor", [$line_number]));
			elgg_log(elgg_echo("nihcp_credit_allocation:validate:no_vendor", [$line_number]), Logger::ERROR);
			return false;
		}

        $remaining_credits = $this->unformatMoney($account_data_array[4]);
        $initial_credits = $this->unformatMoney($account_data_array[5]);

        // - Check for Numeric credit values
        if (!is_numeric($remaining_credits) || !is_numeric($initial_credits)) {
            register_error(elgg_echo("nihcp_credit_allocation:validate:nan", [$line_number]));
            elgg_log(elgg_echo("nihcp_credit_allocation:validate:nan", [$line_number]), Logger::ERROR);
            return false;
        }

        // - Check for Remaining value must be less than initial value
        if ($remaining_credits > $initial_credits) {
            register_error(elgg_echo("nihcp_credit_allocation:validate:too_much_remaining", [$line_number]));
            elgg_log(elgg_echo("nihcp_credit_allocation:validate:too_much_remaining", [$line_number]), Logger::ERROR);
            return false;
        }

        return true;
    }

    // Checks account data content against the criteria below and ingests the data
    // Function argument should be array of CSV elements from 1 line.
    // These tests will not abort the ingest, but create a note of the failed check.
    // $account_data_array is expected to contain:
    // [0]Account Holder Name,
    // [1]CCREQ ID,
    // [2]Vendor ID,
    // [3]Cloud Account ID,
    // [4]Remaining Credits,
    // [5]Initial Credits
    //
    // Soft checks (only produces elgg_logged warnings):
    // - Cloud account did exist before
    // - Credit Remaining < previous Credit Remaining
    // - Initial Credits == previous Initial Credits

    private function ingestLine($account_data_array, $line_number) {


        $request_guid = CommonsCreditRequest::getRequestGUIDfromCCREQID(trim($account_data_array[1]));
        $status = CommonsCreditAllocation::UPDATED_STATUS;
		$vendor_id = trim($account_data_array[2]);
		$vendor = CommonsCreditVendor::getByVendorId($vendor_id);

        $existing_allocation = get_entity(CommonsCreditAllocation::getAllocationGUID($request_guid, $vendor->getGUID()));
        // - Check for Cloud account did exist before
        if (!$existing_allocation) {
            register_error(elgg_echo("nihcp_credit_allocation:validate:new_allocation", [$line_number]));
            elgg_log(elgg_echo("nihcp_credit_allocation:validate:new_allocation", [$line_number]), Logger::WARNING);
            $status = CommonsCreditAllocation::FLAGGED_STATUS;
        }

        // - Check for Credit Remaining < previous Credit Remaining
        $new_remaining = $this->unformatMoney($account_data_array[4]);
        if ($new_remaining > $existing_allocation->credit_remaining) {
            register_error(elgg_echo("nihcp_credit_allocation:validate:remaining_credits_increased", [$line_number]));
            elgg_log(elgg_echo("nihcp_credit_allocation:validate:remaining_credits_increased", [$line_number]), Logger::WARNING);
            $status = CommonsCreditAllocation::FLAGGED_STATUS;
        }

        // - Check for Initial Credits == previous Initial Credits
        $new_initial = $this->unformatMoney($account_data_array[5]);
        if ($new_initial != $existing_allocation->credit_allocated) {
            register_error(elgg_echo("nihcp_credit_allocation:validate:initial_changed", [$line_number]));
            elgg_log(elgg_echo("nihcp_credit_allocation:validate:initial_changed", [$line_number]), Logger::WARNING);

            $status = CommonsCreditAllocation::FLAGGED_STATUS;
        }

        $allocation = new CommonsCreditAllocation();
		$allocation->save();

		$allocation->owner_guid = get_entity($request_guid)->getOwnerGUID();

        $allocation->vendor = $vendor_id;
        $allocation->cloud_account_id = trim($account_data_array[3]);
        $allocation->credit_remaining = $new_remaining;
        $allocation->credit_allocated = $new_initial;
        $allocation->status = $status;

		$allocation->save();

		add_entity_relationship($request_guid, CommonsCreditAllocation::RELATIONSHIP_CCREQ_TO_ALLOCATION, $allocation->getGUID());
		add_entity_relationship($this->file_guid, CommonsCreditAllocationFile::RELATIONSHIP_FILE_TO_CCA, $allocation->getGUID());



		return $allocation->getOwnerGUID();
    }

    // Strips the $ and . symbols from a money string
    private function unformatMoney($money) {
        return preg_replace('/[$,]/', '', trim($money));
    }

}