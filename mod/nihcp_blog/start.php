<?php


elgg_register_event_handler('init', 'system', 'nihcp_blog_init');

function nihcp_blog_init() {

    // register event handler for when ElggBlog entities get created
    elgg_register_event_handler('create', 'object', 'nihcp_blog_created');
    elgg_register_event_handler('update', 'object', 'nihcp_blog_updated');

    // replace owner menu block link to blogs with one that goes to all blog posts
    elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');
    elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'nihcp_blog_owner_block_menu');

}

/**
 * Add a menu item to an ownerblock
 */
function nihcp_blog_owner_block_menu($hook, $type, $return, $params) {
    $url = "blog/";
    $item = new ElggMenuItem('blog', elgg_echo('blog'), $url);
    $return[] = $item;


    return $return;
}