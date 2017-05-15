<?php

echo "<div>";
echo elgg_view("input/text", array("title" => "search_text", "name" => "q", "value" => get_input("q"), "placeholder" => elgg_echo("search"), "alt" => "Search"));
echo "</div>";