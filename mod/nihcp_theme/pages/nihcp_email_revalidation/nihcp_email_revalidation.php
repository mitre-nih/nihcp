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
 


$user = elgg_get_logged_in_user_entity();
$code = get_input('code');

// verify that the logged in user is the same one assigned this code
if (empty($code) || empty($user->email_revalidation_code) || $user->email_revalidation_code != $code) {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
} else {

    // update the email
    $user->email = $user->unvalidated_email;

    // clear the unvalidated email and code fields
    $user->unvalidated_email = null;
    $user->email_revalidation_code = null;

    if (!$user->save()) {
        register_error(elgg_echo('email:save:fail'));
    }

    system_message(elgg_echo('nihcp_email_revalidation:validated'));
    forward(elgg_get_site_url() . "settings/user");
}
