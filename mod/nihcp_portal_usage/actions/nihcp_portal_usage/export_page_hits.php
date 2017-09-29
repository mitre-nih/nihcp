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

$ia = elgg_set_ignore_access();

$date = date('Ymd');

$fo = new ElggFile();

$title = "page_view_statistics_$date";
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

fputcsv_eol($fh, ['url', 'hits'], $newline);

$fh = $fo->open('append');

$options = array(
    'calculation' => "sum",
    'limit' => $limit,
    'annotation_names' => array('pageHit'),
);
$results = elgg_get_entities_from_annotation_calculation($options);


foreach($results as $result) {
    $s = $result->getSubtype();
    $u = $result->getURL();

    if(in_array($s,array('services','catalog','equivalency','glossary'))){

        $u = elgg_get_site_url() . $result->elggURI;
    }
    if($result->title == "User Manual"){
        $t = $result->title;
        $u = elgg_get_site_url() . "nihcp_commons_credit_request/investigator-portal-user-manual";
    }else if($result->title == "FAQ Listing Page"){
        $t = $result->title;
        $u = $result->elggURI;
    }else if($result->title == "Help Center") {
        $t = $result->title;
        $u = $result->elggURI;
    }
    $sum = $result->getAnnotationsSum("pageHit");
    fputcsv_eol($fh, array($u,$sum), $newline);
}

$fo->close();
elgg_set_ignore_access($ia);
forward(str_replace('view', 'download', $fo->getURL()));
