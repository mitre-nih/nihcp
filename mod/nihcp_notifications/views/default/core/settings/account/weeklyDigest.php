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
 

/**
 * Created by PhpStorm.
 * User: tomread
 * Date: 2/27/17
 * Time: 8:54 AM
 */

//so the admin can change settings?
$user = elgg_get_page_owner_entity();

if($user) {

    $title = elgg_echo('nihcp_notifications:profile:weekly_digest_title');
    $t = $user->weeklyDigestOptOut;
    $checked = false;
    if($t === "1"){
        $checked = true;
    }
    $checkbox = elgg_view('input/checkbox', array(
        'label' => elgg_echo('nihcp_notifications:profile:weekly_digest_opt_out'),
        'name' => 'weekly_digest_opt_out',
        'id' => 'weekly_digest_opt_out',
        'checked' => $checked,
    ));
    echo elgg_view_module('info', $title, $checkbox);


}
?>