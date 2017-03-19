<?php

use Nihcp\Entity\CommonsCreditCycle;


$ia = elgg_set_ignore_access();
$cycles = CommonsCreditCycle::getCycles($omit_future = true);

if(!empty($cycles)) {
    $cycle_options = [];
    foreach($cycles as $cycle) {
        $cycle_options[$cycle->guid] = elgg_view_entity($cycle);
    }
}

$cycle_options[0] = 'All';

elgg_set_ignore_access($ia);

$content = "<div class=\"nihcp-ccreq-top-bar ptl pbm\">
				<label class=\"prm\">".elgg_echo('item:object:commonscreditcycle').":</label>".
                elgg_view('input/select', array('options_values' => $cycle_options, 'name' => 'nihcp-ccreq-cycle-select', 'id' => 'nihcp-ccreq-cycle-select', 'class' => 'mts', 'alt' => 'NIHCP CCREQ Cycle Select')).
            "</div>";

$content .= elgg_view('input/submit', ['value' => "Export"]);
echo $content;