<?php
?>

//<script>
elgg.provide("elgg.nihcp_blog");

elgg.nihcp_blog.search = function(event) {
    if (event.which == $.ui.keyCode.ENTER || event.which == 1) { // trigger on enter key or mouseclick
        var search_term = $("#user-forum-search").val();
        elgg.get('ajax/view/nihcp_blog/search_results', {
            data: {
                search_term: search_term
            },
            success: function(output) {
                $('.elgg-list').html(output);
            }
        });
    }
};

elgg.nihcp_blog.init = function() {

    $(document).on("keypress", "#user-forum-search", elgg.nihcp_blog.search);
    $(document).on("keypress", "#user-forum-search-button", elgg.nihcp_blog.search);
    $(document).on("click", "#user-forum-search-button", elgg.nihcp_blog.search);
};

elgg.register_hook_handler('init', 'system', elgg.nihcp_blog.init);