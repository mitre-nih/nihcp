<?php
/**
 * Created by PhpStorm.
 * User: tomread
 * Date: 8/11/17
 * Time: 3:43 PM
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
