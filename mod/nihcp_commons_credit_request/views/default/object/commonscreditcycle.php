<?php
$cycle = elgg_extract('entity', $vars);
$ia = elgg_set_ignore_access();
$val = $cycle->start." &mdash; ".$cycle->finish;
elgg_set_ignore_access($ia);
echo $val;