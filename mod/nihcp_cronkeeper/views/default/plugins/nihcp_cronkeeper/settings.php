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

$limitToLocal = elgg_get_plugin_setting('limit_to_local', 'nihcp_cronkeeper');
$sel1 = $sel2 = "";
if($limitToLocal == "yes") {
    $sel1 = "selected";
}else if($limitToLocal == "no"){
    $sel2 = "selected";
}

$keyName = elgg_get_plugin_setting('key_name', 'nihcp_cronkeeper');
$keyValue = elgg_get_plugin_setting('key_value', 'nihcp_cronkeeper');
?>

<p>
    <?php echo elgg_echo('nihcp_cronkeeper:settings:limit_to_local'); ?>
    <select name="params[limit_to_local]">
        <option value="yes" <?php echo $sel1?>>Yes</option>
        <option value="no"  <?php echo $sel2?>>No</option>
    </select>

</p>

<p>
    <?php echo elgg_echo('nihcp_cronkeeper:settings:key_name_label'); ?>
    <input name="params[key_name]" type="text" value=<?php echo $keyName ?>>
    <?php echo elgg_echo('nihcp_cronkeeper:settings:key_value_label'); ?>
    <input name="params[key_value]" type="text" value=<?php echo $keyValue ?>>
</p>
