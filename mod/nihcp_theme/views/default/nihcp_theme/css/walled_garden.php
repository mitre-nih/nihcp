<?php

$url = elgg_get_site_url();

?>
/* <style> /**/

.elgg-button-submit {
	background: #2F5A7A;
}

.nihcp_theme_login {
	color: white;
	font-size: large;
	display: block;
	margin: 0;
	min-width: 60em;
}

.nihcp_theme_login .header {
	margin-top: -5.5em;
	position: absolute;
	background-color: white;
	width: 100%;
	height: 5em;
	min-width: 60em;
}

.nihcp_theme_login .header div,
.nihcp_theme_login .header div a {
	color: #666666;
}

.nihcp_theme_login .header .left {
	font-size: xx-large;
	border: none;
	padding-top: 1.25em;
	padding-left: 1em;
	float: left;
}

.nihcp_theme_login .header .right {
	float: right;
	padding-top: 1.7em;
	padding-right: 3.25em;
}

.nihcp_theme_login .header .right a {
	margin-right: 2.5em;
}

.nihcp_theme_login .header .right #loginLink {
	margin-right: 1em;
}

.nihcp_theme_login .header .right #loginLink {
	padding-top: 0.5em;
	padding-bottom: 0.5em;
	padding-left: 2em;
	padding-right: 2em;
}

.nihcp_theme_login .header .logo  {
	padding-top: 1em;
	padding-left: 1em;
	width: 100px;
	height: 100px;
	float: left;
}

.nihcp_theme_login .header .logo img {
	width: 100%;
	height: auto;
}

#loginLink, #signUp {
	color: white;
	background-color: #2F5A7A;
	font-size: large;
	border: 1px solid #666666;
}

.nihcp_theme_login .background {
	background: url(<?php echo $url; ?>mod/nihcp_theme/graphics/dollar.jpg);
	background-size: 100% 100%;
	background-repeat: no-repeat;
	margin-top: 5.5em;
}

.nihcp_theme_login .jumbotron {
	background: url(<?php echo $url; ?>mod/nihcp_theme/graphics/dollar.jpg);
	background-size: cover;
	background-position: center center;
	background-repeat: no-repeat;
	margin-top: 5.5em;
}

.nihcp_theme_login .transbox {
	/*background-color: #000221;
	opacity: 0.75;*/
	/* 	Note: the following line is equivalent to the above 2 lines.
		This is needed to make the sign up button be opaque. Need to
		set opacity and color on the background rather than separately.
	*/
	background: rgba(0, 2, 33, 0.5);
	padding-left: 53%;
	padding-right: 0.75em;
	padding-top: 0.5em;
	padding-bottom: 1.25em;
}

.nihcp_theme_login .transbox .transbox_text {
	background: rgba(7, 55, 99, .85);
	padding: 0.75em 0.75em 1.75em 0.75em;
}

#signUp {
	display: inline-block;
	width: 100%;
	padding-top: 0.5em;
	padding-bottom: 0.5em;
	margin-top: 3.5em;
	text-align: center;
}

.nihcp_theme_login #features {
	background-color: #073763;
	color: white;
	padding-bottom: 5em;
}

.nihcp_theme_login #features div,
.nihcp_theme_login #features img,
.nihcp_theme_login #faq div {
	width: 80%;
	margin: auto;
	display: block;
}

.nihcp_theme_login div div {
	color: white;
	font-size: large;
	padding-top: 1.75em;
	padding-bottom: 2em;
}

.nihcp_theme_login div div.title {
	font-size: xx-large;
	padding-top: 2em;
	padding-bottom: 0em;
}

.nihcp_theme_login div div.title2 {
	font-size: x-large;
	padding-top: 2.5em;
	padding-bottom: 0em;
}

.nihcp_theme_login #faq {
	padding-bottom: 4em;
	border-bottom: none;
}

.nihcp_theme_login #faq .title {
	color: #666666;
}

.nihcp_theme_login #faq .question {
	font-weight: bolder;
	color: #004FBA;
	padding-top: 2.25em;
	padding-bottom: 0em;
}

.nihcp_theme_login #faq .answer {
	color: #666666;
	padding-top: 1.25em;
	padding-bottom: 0em;
}

.nihcp_theme_login #faq .answer .label {
	padding-right: 0.5em;
}


.nihcp_theme_login .footer {
	background-color: #E4E3DA;
	padding-top: 1.5em;
	padding-bottom: 1em;
	border-top: none;
}

.nihcp_theme_login .footer div {
	color: #333333;
	text-align: center;
	font-size: x-small;
	font-weight: bold;
	padding-top: 0.5em;
	padding-bottom: 0.5em;
}