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
