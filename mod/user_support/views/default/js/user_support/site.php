<?php
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