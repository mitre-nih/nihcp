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
/**
 * Walled garden JavaScript
 *
 * @since 1.8
 */

$cancel_button = elgg_view('input/button', array(
	'value' => elgg_echo('cancel'),
	'class' => 'elgg-button-cancel mlm',
));
$cancel_button = json_encode($cancel_button);

if (0) { ?><script><?php }
?>

elgg.provide('elgg.walled_garden');

elgg.walled_garden.init = function () {

	// make sure it is loaded before using it in the click events
	require(['elgg/spinner']);

	//$('.forgot_link').click(elgg.walled_garden.load('lost_password'));
	//$('.registration_link').click(elgg.walled_garden.load('register'));

	// Addition: add cancel button if Register or Lost Password
	$('.registration_link').find('input.elgg-button-submit').after(<?php echo $cancel_button; ?>);
	$('.forgot_link').find('input.elgg-button-submit').after(<?php echo $cancel_button; ?>);

	$('.registration_link').find('input.elgg-button-cancel').live('click', function(event) {
		window.history.back();
	});
	$('.forgot_link').find('input.elgg-button-cancel').live('click', function(event) {
		window.history.back();
	});

	$('input.elgg-button-cancel').live('click', function(event) {
		var $wgs = $('.elgg-walledgarden-single');
		if ($wgs.is(':visible')) {
			$('.elgg-walledgarden-double').fadeToggle();
			$wgs.fadeToggle();
			$wgs.remove();
		}
		event.preventDefault();
	});
};

/**
 * Creates a closure for loading walled garden content through ajax
 *
 * @param {String} view Name of the walled garden view
 * @return {Object}
 */
elgg.walled_garden.load = function(view) {
	return function(event) {
		require(['elgg/spinner'], function(spinner) {
			var id = '#elgg-walledgarden-' + view;
			id = id.replace('_', '-');
			// @todo display some visual element that indicates that loading of content is running
			elgg.get('walled_garden/' + view, {
				beforeSend: spinner.start,
				complete: spinner.stop,
				success: function(data) {
					var $wg = $('.elgg-body-walledgarden');
					$wg.append(data);
					$(id).find('input.elgg-button-submit').after(<?php echo $cancel_button; ?>);

					if (view == 'register' && $wg.hasClass('hidden')) {
						// this was a failed registration, display the register form ASAP
						$('#elgg-walledgarden-login').toggle(false);
						$(id).toggle();
						$wg.removeClass('hidden');
					} else {
						$('#elgg-walledgarden-login').fadeToggle();
						$(id).fadeToggle();
					}
				}
			});
		});
		event.preventDefault();
	};
};

elgg.register_hook_handler('init', 'system', elgg.walled_garden.init);
