<?php

$guid = get_input('request_guid');
if($guid) {
    $request = get_entity($guid);
    $request->status = 'Withdrawn';
    return true;
}
return false;