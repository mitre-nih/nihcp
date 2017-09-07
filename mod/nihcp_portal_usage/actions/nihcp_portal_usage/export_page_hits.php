<?php


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
    if($result->title == "investigator-portal-user-manual"){
        $t = $result->title;
        $u = elgg_get_site_url() . "nihcp_commons_credit_request/investigator-portal-user-manual";
    }
    $sum = $result->getAnnotationsSum("pageHit");
    fputcsv_eol($fh, array($u,$sum), $newline);
}

$fo->close();
elgg_set_ignore_access($ia);
forward(str_replace('view', 'download', $fo->getURL()));
