<?php
$roles = \Nihcp\Manager\RoleManager::getConfig();
$content = elgg_view('input/select', ['name' => 'role', 'options' => $roles]);
$content .= elgg_view('input/submit', ['value' => "Export"]);
echo $content;