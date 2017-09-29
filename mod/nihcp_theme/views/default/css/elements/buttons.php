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
 * CSS buttons
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style> /**/

/* **************************
	BUTTONS
************************** */
.elgg-button {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	color: #FFF;
	width: auto;
	padding: 6px 12px;
	cursor: pointer;
	border-radius: 3px;
	box-shadow: inset 0 0 1px rgba(255, 255, 255, 0.6);

	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.elgg-button:hover,
.elgg-button:focus {
	text-decoration: none;
}
.elgg-button-submit {	
	border: 1px solid rgba(0, 0, 0, 0.2);
	/* darken the background for color contrast */
	background: #2F5A7A;
}
.elgg-button-submit:hover,
.elgg-button-submit:focus {
	/* changed colors for color contrast */
	color: #000000;
	background: #60B6F7;
}
.elgg-button-submit.elgg-state-disabled {
	background: #DEDEDE;
	cursor: default;
}
.elgg-button-cancel {
	border: 1px solid rgba(0, 0, 0, 0.2);
	background: #6C4403;
}
.elgg-button-cancel:hover,
.elgg-button-cancel:focus {
	/* changed colors for color contrast */
	color: #000000;	
	background: #FBC46B;
}
.elgg-button-action {
	border: 1px solid rgba(0, 0, 0, 0.2);
	background: #F0F0F0;
	color: #333;
}
.elgg-button-action:hover,
.elgg-button-action:focus {
	background: #DEDEDE;
	color: #333;
}
.elgg-button-delete {
	border: 1px solid rgba(0, 0, 0, 0.2);
	background: #FF3300;
}
.elgg-button-delete:hover,
.elgg-button-delete:focus {
	background: #D63006;
}
.elgg-button-dropdown {
	background: none;
	text-decoration: none;
	display: block;
	position: relative;
	margin-left: 0;
	color: #FFF;
	border: none;
	box-shadow: none;
	border-radius: 0;
}
.elgg-button-dropdown:hover,
.elgg-button-dropdown:focus,
.elgg-button-dropdown.elgg-state-active {
	color: #FFF;
	background: #60B8F7;
	text-decoration: none;
}
.elgg-button-special {
	border: 1px solid rgba(0, 0, 0, 0.2);
	background: #42C5B8;
}
.elgg-button-special:hover,
.elgg-button-special:focus {
	background: #5ED9CD;
}
/* Use .elgg-size-small or .elgg-size-large for additional sizes */
.elgg-button.elgg-size-small {
	font-size: 12px;
	padding: 4px 8px;
}
.elgg-button.elgg-size-large {
	font-size: 20px;
	padding: 14px 20px;
	margin: 15px 0;
	border-radius: 5px;
}
