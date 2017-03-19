<?php


elgg_register_event_handler('init', 'system', 'nihcp_blog_init');

function nihcp_blog_init() {

    // replace owner menu block link to blogs with one that goes to all blog posts
    elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');
    elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'nihcp_blog_owner_block_menu');

    elgg_extend_view("js/elgg", "js/nihcp_blog/nihcp_blog");
    elgg_register_ajax_view('nihcp_blog/search_results');

    $search_input = "<div class = 'mrl'>";
    $search_input .= elgg_view('input/text', array(
        "id" => "user-forum-search",
        "name" => "user-forum-search",
        "alt" => "User Forum Search",
        "placeholder" => elgg_echo("search"),
    ));

    $search_input .= elgg_view("input/submit",array(
        "id"=>"user-forum-search-button",
        "value"=>"Search"));

    $search_input .= "</div>";

    // add search bar
    if (elgg_get_context() === 'blog') {
        elgg_register_menu_item('title', array(
            'name' => "blog",
            'href' => false,
            'text' => $search_input,
            'link_class' => 'elgg-button elgg-button-action',
        ));
    }

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