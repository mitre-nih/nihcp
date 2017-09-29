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
 * Elgg add user form.
 *
 * @package Elgg
 * @subpackage Core
 *
 */

if (elgg_is_sticky_form('useradd')) {
    $values = elgg_get_sticky_values('useradd');
    elgg_clear_sticky_form('useradd');
} else {
    $values = array();
}

$password = $password2 = '';
$name = elgg_extract('name', $values);
$username = elgg_extract('username', $values);
$email = elgg_extract('email', $values);
$admin = elgg_extract('admin', $values);
if (is_array($admin)) {
    $admin = array_shift($admin);
}

?>
<div>
    <label><?php echo elgg_echo('name');?></label><br />
    <?php
    echo elgg_view('input/text', array(
        'name' => 'name',
        'value' => $name,
    ));
    ?>
</div>
<div>
    <label><?php echo elgg_echo('username'); ?></label><br />
    <?php
    echo elgg_view('input/text', array(
        'name' => 'username',
        'value' => $username,
    ));
    ?>
</div>
<div>
    <label><?php echo elgg_echo('email'); ?></label><br />
    <?php
    echo elgg_view('input/text', array(
        'name' => 'email',
        'value' => $email,
    ));
    ?>
</div>
<div>
    <label><?php echo elgg_echo('password'); ?></label><br />
    <?php
    echo elgg_view('input/password', array(
        'name' => 'password',
        'value' => $password,
        'autocomplete' => 'off',
    ));
    ?>
</div>
<div>
    <label><?php echo elgg_echo('passwordagain'); ?></label><br />
    <?php
    echo elgg_view('input/password', array(
        'name' => 'password2',
        'value' => $password2,
        'autocomplete' => 'off',
    ));
    ?>
</div>
<div>
    <?php
    echo elgg_view('input/checkboxes', array(
        'name' => "admin",
        'options' => array(elgg_echo('admin_option') => 1),
        'value' => $admin,
    ));
    ?>
</div>

<div class="elgg-foot">
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('register'))); ?>
</div>