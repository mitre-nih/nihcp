<?php
$guid = get_input('file_guid');
if($guid) {
	$file = get_entity($guid);
	return $file->delete();
}
return false;