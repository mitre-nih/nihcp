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
    'type' => 'object',
    'limit' => 0,
    'metadata_name_value_pairs' => [
        ['name' => 'pageCounter', 'value' => '0', 'operand' => '>'],
    ],
    'order_by_metadata' => array(
        'name' => 'pageCounter','direction' => 'DESC', 'as' => 'integer',
    ),
);

$results = elgg_get_entities_from_metadata($options);

foreach($results as $result) {
    $s = $result->getSubtype();
    $u = $result->getURL();

    if(in_array($s,array('services','catalog','equivalence','glossary'))){
        $u = $result->elggURI;
    }
    fputcsv_eol($fh, array($u,$result->pageCounter), $newline);
}

$fo->close();
elgg_set_ignore_access($ia);
forward(str_replace('view', 'download', $fo->getURL()));
