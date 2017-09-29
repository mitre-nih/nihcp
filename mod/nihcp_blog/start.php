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
 



elgg_register_event_handler('init', 'system', 'nihcp_blog_init');

function nihcp_blog_init() {

    // replace owner menu block link to blogs with one that goes to all blog posts
    elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');
    elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'nihcp_blog_owner_block_menu');

    elgg_extend_view("js/elgg", "js/nihcp_blog/nihcp_blog");
    elgg_register_ajax_view('nihcp_blog/search_results');

    $search_input = "<div class = 'mrl'>";
    $search_input .= elgg_view('input/text', array(
        "title" => "user-forum-search",
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
