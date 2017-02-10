<?php
/**
 * Elgg topbar
 * The standard elgg top toolbar
 */
echo "<span class='skip-to-content' onclick=\"$('.elgg-page-body').focus();\">Skip to main content</span>";
//echo "<a href='#elgg-page-body'>Skip to content.</a>";
// Elgg logo
echo elgg_view_menu('topbar', array('sort_by' => 'priority', array('elgg-menu-hz')));

// elgg tools menu
// need to echo this empty view for backward compatibility.
echo elgg_view_deprecated("navigation/topbar_tools", array(), "Extend the topbar menus or the page/elements/topbar view directly", 1.8);
