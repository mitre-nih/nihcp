<?php

$guid = get_input('dor_guid');
if($guid) {
    $dor = get_entity($guid);
    if(!$dor instanceof \Nihcp\Entity\DigitalObjectReport) {
        return false;
    }

    $file_guid = $dor->file_guid;
    if (!empty($file_guid)) {
        $file = get_entity($file_guid);
        $file->delete();
    }

    return $dor->delete();
}
return false;