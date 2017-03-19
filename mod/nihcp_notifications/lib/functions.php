<?php

function nihcp_notifications_send_email($to, $subject, $body) {

    global $CONFIG;

    $from = elgg_get_config('siteemail');

    $headers = array(
        "Content-Type" => "text/plain; charset=UTF-8; format=flowed",
        "MIME-Version" => "1.0",
        "Content-Transfer-Encoding" => "8bit",
    );

    // return true/false to stop elgg_send_email() from sending
    $mail_params = array(
        'to' => $to,
        'from' => $from,
        'subject' => $subject,
        'body' => $body,
        'headers' => $headers,
    );

    // $mail_params is passed as both params and return value. The former is for backwards
    // compatibility. The latter is so handlers can now alter the contents/headers of
    // the email by returning the array
    $result = elgg_trigger_plugin_hook('email', 'system', $mail_params, $mail_params);
    if (is_array($result)) {
        foreach (array('to', 'from', 'subject', 'body', 'headers') as $key) {
            if (isset($result[$key])) {
                ${$key} = $result[$key];
            }
        }
    } elseif ($result !== null) {
        return $result;
    }

    $header_eol = "\r\n";
    if (isset($CONFIG->broken_mta) && $CONFIG->broken_mta) {
        // Allow non-RFC 2822 mail headers to support some broken MTAs
        $header_eol = "\n";
    }

    // Windows is somewhat broken, so we use just address for to and from
    if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
        // strip name from to and from
        if (strpos($to, '<')) {
            preg_match('/<(.*)>/', $to, $matches);
            $to = $matches[1];
        }
        if (strpos($from, '<')) {
            preg_match('/<(.*)>/', $from, $matches);
            $from = $matches[1];
        }
    }

    // make sure From is set
    if (empty($headers['From'])) {
        $headers['From'] = $from;
    }

    // stringify headers
    $headers_string = '';
    foreach ($headers as $key => $value) {
        $headers_string .= "$key: $value{$header_eol}";
    }

    // Sanitise subject by stripping line endings
    $subject = preg_replace("/(\r\n|\r|\n)/", " ", $subject);
    // this is because Elgg encodes everything and matches what is done with body
    $subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8'); // Decode any html entities
    if (is_callable('mb_encode_mimeheader')) {
        $subject = mb_encode_mimeheader($subject, "UTF-8", "B");
    }

    // Format message
    $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8'); // Decode any html entities
    $body = elgg_strip_tags($body); // Strip tags from message
    $body = preg_replace("/(\r\n|\r)/", "\n", $body); // Convert to unix line endings in body
    $body = preg_replace("/^From/", ">From", $body); // Change lines starting with From to >From
    //$body = wordwrap($body);

    mail($to, $subject, $body, $headers_string);
}