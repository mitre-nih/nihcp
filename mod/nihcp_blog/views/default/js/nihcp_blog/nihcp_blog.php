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
