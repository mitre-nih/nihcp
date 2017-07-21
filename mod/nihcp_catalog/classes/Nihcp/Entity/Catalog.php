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

class Catalog extends \ElggObject {
	const SUBTYPE = 'catalog';

	/**
	 * @var array
	 */
	public static $table_columns = [
		//'title',
		'author',
		'created_by',
		'created_on',
	];

	public function getURL() {
		return "/catalog/view/{$this->guid}";
	}

	public function getAttachmentURL() {
		$file_guid = $this->file_guid;

		return "/file/download/$file_guid";
	}

	public function hasAttachment() {
		return ($this->file_guid ? true : false);
	}

	public function initializeAttributes() {
		parent::initializeAttributes();

		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

	/**
	 * Always save with ACCESS_LOGGED_IN
	 */
	public function save() {
		$this->access_id = ACCESS_LOGGED_IN;

		return parent::save();
	}

	public static function getFields() {
		return [
			/*'title' => [
				'label' => 'Title',
				'type' => 'input/hidden',
				'sortable' => true,
				'function' => function (Catalog $item) {
					$link = elgg_view('output/url', [
						'href' => $item->getURL(),
						'text' => $item->title,
						'is_trusted' => true,
					]);

					return "<td>$link</td>";
				},

			],*/
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

	/**
	 * @return mixed
	 */
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

	public function canEdit($guid = 0) {
		return nihcp_vendor_admin_gatekeeper(false, $guid);
	}
}