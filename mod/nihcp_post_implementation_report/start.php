<?php

elgg_register_event_handler('init', 'system', 'nihcp_pir_init');

function nihcp_pir_init() {
    elgg_register_page_handler('nihcp_post_implementation_report', 'nihcp_pir_page_handler');

    elgg_register_widget_type('nihcp_post_implementation_report', elgg_echo("nihcp_pir"), elgg_echo("nihcp_pir:widget:description"));

    elgg_register_ajax_view('nihcp_post_implementation_report/overview/reports');

    // Register a script to handle (usually) a POST request (an action)
    $action_path = __DIR__ . '/actions';
    elgg_register_action('submission', "$action_path/nihcp_post_implementation_report/submission.php");
    elgg_register_action('digital_object', "$action_path/nihcp_post_implementation_report/digital_object.php");
    elgg_register_action('delete_digital_object_report', "$action_path/nihcp_post_implementation_report/delete_digital_object_report.php");

}

function nihcp_pir_page_handler($page) {

    $pir_dir = elgg_get_plugins_path() . 'nihcp_post_implementation_report/pages/nihcp_post_implementation_report';

    $page_type = $page[0];

    switch($page_type) {
        case 'overview' :
            include "$pir_dir/overview.php";
            break;
        case 'pir' :
            if (isset($page[1])) { //expecting ccreq guid here

                set_input('ccreq_guid', $page[1]);
            }
            include "$pir_dir/post_implementation_report.php";
            break;
        case 'do' :
            if (isset($page[1])) { //expecting pir guid here
                if (empty(get_entity($page[1]))) {
                    register_error(elgg_echo("error:404:content"));
                    forward(REFERER);
                }
                set_input('pir_guid', $page[1]);

                if (isset($page[2]) && $page[2] !== 'new') { //this is the digital object report guid if one exists
                    set_input('dor_guid', $page[2]);
                }

            }

            include "$pir_dir/digital_object.php";
            break;
        case 'submission' :
            if (isset($page[1])) { //expecting pir guid here
                if (empty(get_entity($page[1]))) {
                    register_error(elgg_echo("error:404:content"));
                    forward(REFERER);
                }
                set_input('pir_guid', $page[1]);
            }
            include "$pir_dir/submission.php";
            break;
        case 'attachment':
            include "$pir_dir/attachment.php";
            break;
        case 'guidance':
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Post Implementation Report Guidance.pdf"');
            readfile(elgg_get_data_path() . '/docs/Post Implementation Report Guidance.pdf');
            break;
        default:
            register_error(elgg_echo("error:404:content"));
            forward(REFERER);
            break;
    }
}