<?php

$no_access_text = 'You do not have permission to view this resource';

return array(
	// override error message text
	'limited_access' => $no_access_text,
	'adminrequired' => $no_access_text,
	'membershiprequired' => $no_access_text,
	'noaccess' => $no_access_text,
	'avatar:noaccess' => $no_access_text,
	'groups:noaccess' => $no_access_text,
	'profile:noaccess' => $no_access_text,
	'user_support:staff_gatekeeper' => $no_access_text,
	'actiongatekeeper:missingfields' => $no_access_text,
	// these ones override reflected XSS vulnerability where scripts could be injected as bad action names
	'actionundefined' => "The requested action was not defined in the system.",
	'actionnotfound' => "The action file was not found.",

	// browser warning text
	'browser_support_warning' => 'This website may not function correctly and optimally with Internet Explorer or older versions of browsers. Please use a different browser for the best experience on this site.',

	'nihcp_theme:settings:portal_admin_email' => 'Portal admin email address',

	'nihcp_theme:password_requirements' => "Your password must be at least %u characters in length and contain at least one of each of the following: lowercase letter, uppercase letter, number, and special character. Acceptable special characters are: " . htmlspecialchars("` ~ ! @ # \$ %% ^ & * ( ) _ + - = { } | [ ] / \\ ; ' : \" < > ? , ."),

	// updates to the user registration form
	'name' => 'Legal name',
	'nihcp_theme:how_did_you_hear_about_us' => 'How did you first learn about the NIH commons credits pilot?',
	'nihcp_theme:how_did_you_hear_about_us:other' => 'If other, please specify (' . HOW_DID_YOU_HEAR_ABOUT_US_OTHER_MAXLENGTH . ' character limit)',
	'nihcp_theme:how_did_you_hear_about_us:option:word_of_mouth' => 'Word of mouth from a colleague',
	'nihcp_theme:how_did_you_hear_about_us:option:email' => 'Email invitation from pilot organizers',
	'nihcp_theme:how_did_you_hear_about_us:option:webinar_seminar' => 'Webinar or Seminar',
	'nihcp_theme:how_did_you_hear_about_us:option:twitter' => 'Twitter',
	'nihcp_theme:how_did_you_hear_about_us:option:facebook' => 'Facebook',
	'nihcp_theme:how_did_you_hear_about_us:option:conference' => 'Conference',
	'nihcp_theme:how_did_you_hear_about_us:option:linkedin' => 'LinkedIn',
	'nihcp_theme:how_did_you_hear_about_us:option:nih_website' => 'NIH Website',
	'nihcp_theme:how_did_you_hear_about_us:option:other' => 'Other',
	'nihcp_theme:how_did_you_hear_about_us:option:unspecified' => 'Unspecified',

	'nihcp_theme:rob_acknowledgement' => "By logging into the NIH CCP you have acknowledged that you have read and understood the <a href='%s'>Rules of Behavior</a>, <a href='%s'>Privacy Policy</a>, <a href='%s'>Terms of Use</a>, and other pertinent policies and that you have agreed to follow the aforementioned rules and policies.",

    'pages:all' => "Knowledgebase",

	'search' => 'Search (press Enter key)',

	'nihcp_email_revalidation:validated' => 'Your email has been updated successfully.',
	'nihcp_email_revalidation:email:subject' => 'Email Change Validation',
	'nihcp_email_revalidation:email:body' => 'You or someone has requested to change your email address on the NIH Commons Credits Pilot Portal. If you did not perform this action, you may ignore this message. Otherwise, please click the following email change revalidation link: %s',

	'session_expired' => "Your session has expired. Please <a href='javascript:location.reload(true)'>reload</a> the page to log in.",
	'session_changed_user' => "You have been logged in as another user. You should <a href='javascript:location.reload(true)'>reload</a> the page.",

);