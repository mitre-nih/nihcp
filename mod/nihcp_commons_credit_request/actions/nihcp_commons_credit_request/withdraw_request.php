<?php

$guid = get_input('request_guid');

if($guid) {
    $request = get_entity($guid);
	if(!$request instanceof \Nihcp\Entity\CommonsCreditRequest) {
		return false;
	}
    $request->status = 'Withdrawn';
    return true;
}
return false;