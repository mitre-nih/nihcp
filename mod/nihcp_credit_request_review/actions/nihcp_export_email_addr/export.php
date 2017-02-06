<?php
$role_name = get_input('role');

$ia = elgg_set_ignore_access();

$role = \Nihcp\Manager\RoleManager::getRoleByName($role_name);
$members = $role->getMembers(['limit' => 0]);
$users = [];
foreach($members as $member) {
	$users[] = [$member->name, $member->email];
}

elgg_set_ignore_access($ia);

$date = date('Ymd');

$fo = new ElggFile();

$title = "${role_name}s_$date";
$fo->setFilename("$title.csv");
$fo->originalfilename = $fo->getFilename();
$fo->title = $title;
$fo->setMimeType("text/csv");

$fo->save();

$fh = $fo->open('write');

function fputcsv_eol($fp, $array, $eol) {
	fputcsv($fp, $array);
	if("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
		fwrite($fp, $eol);
	}
}

$newline = "\n";

fputcsv_eol($fh, ['name', 'email'], $newline);

$fh = $fo->open('append');
foreach($users as $row) {
	fputcsv_eol($fh, $row, $newline);
}

$fo->close();

forward(str_replace('view', 'download', $fo->getURL()));
