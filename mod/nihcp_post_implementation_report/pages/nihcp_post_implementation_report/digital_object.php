<?php

pseudo_atomic_set_ignore_access(function () {

    $form_vars = array(
        'enctype' => 'multipart/form-data',
        'id' => 'dor-form'
    );

    $params = array(
        'title' => elgg_echo("nihcp_pir")  . " - DO Description",
        'content' => elgg_view_form('digital_object', $form_vars),
        'filter' => '',
    );

    $body = elgg_view_layout('one_column', $params);

    echo elgg_view_page(elgg_echo("nihcp_pir"), $body, 'default');

});