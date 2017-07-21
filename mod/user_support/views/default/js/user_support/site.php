<?php
/*
Copyright 2017 The MITRE Corporation

This software was written for the NIH Commons Credit Portal.
General questions can be forwarded to:
 
opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
 

?>
//<script>
elgg.provide("elgg.user_support");

elgg.user_support.search = function(event) {
	if (event.which == $.ui.keyCode.ENTER) {
		$('#user_support_help_search_result_wrapper').hide();
		var toSearch = $("#user-support-help-center-search").val();
		//elgg.ajax("user_support/search/?q=" + $(this).val(), function(data) {
        elgg.ajax("user_support/search/?q=" + toSearch, function(data) {
			$('#user_support_help_search_result_wrapper').html(data).show();
			elgg.user_support.lightbox_resize();
		});
	}
};

elgg.user_support.buttonSearch = function(event){
    //probably a more robust way of doing this
    $('#user_support_help_search_result_wrapper').hide();
    var toSearch = $("#user-support-help-center-search").val();
    //elgg.ajax("user_support/search/?q=" + $(this).val(), function(data) {
    elgg.ajax("user_support/search/?q=" + toSearch, function(data) {
        $('#user_support_help_search_result_wrapper').html(data).show();
        elgg.user_support.lightbox_resize();
    });
}

elgg.user_support.ask_question = function(event) {
	event.preventDefault();
	


	elgg.user_support.lightbox_resize();
}

elgg.user_support.add_help = function (event) {
	event.preventDefault();
	


	elgg.user_support.lightbox_resize();
};

elgg.user_support.lightbox_resize = function() {
	$.colorbox.resize();
};

elgg.user_support.init = function() {
	
	$(document).on("keypress", "#user-support-help-center-search", elgg.user_support.search);
    $(document).on("click", "#user-support-help-center-search-button", elgg.user_support.buttonSearch);
	$(document).on("keypress", "#user-support-help-center-search-button", elgg.user_support.search);
	$(document).on("click", "#user-support-help-center-ask", elgg.user_support.ask_question);

	$(document).on("click", "#user-support-help-center-add-help", elgg.user_support.add_help);
	$(document).on("click", "#user-support-help-center-edit-help", elgg.user_support.add_help);
	$(document).on("click", ".elgg-form-user-support-help-edit .elgg-button-cancel", elgg.user_support.add_help);
};

elgg.register_hook_handler('init', 'system', elgg.user_support.init);