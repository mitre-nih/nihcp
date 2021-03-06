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
 


$graphics_folder = elgg_normalize_url("mod/user_support/_graphics/");

?>
/* <style> /**/
/* User support button */
#user-support-button {
	background: transparent url(<?php echo $graphics_folder; ?>button_bg.png) top right repeat-y;
	border-color: #B6B6B6;
	border-style: solid;
	border-width: 1px 1px 1px 0px;
	
	font-size: 16px;
    font-weight: bold;
    position: fixed;
    padding: 4px 2px 4px 4px;
    line-height: 18px;
    text-align: left;
    width: 18px;
    z-index: 10000;
}

#user-support-button a {
	color: #FFFFFF;
	text-decoration: none;
	display: block;
	width: 16px;
	padding-bottom: 20px;
	text-align: center;
}

#user-support-button a:hover {
	color: #000;
}

.user-support-button-help-center {
	background: transparent url(<?php echo $graphics_folder; ?>help_center/helpcenter16_gray.png) no-repeat scroll right bottom;
}

.user-support-button-help-center.elgg-state-active {
	background-image: url(<?php echo $graphics_folder; ?>help_center/helpcenter16.png)
}

/* Help Center */
.user-support-help-center-popup {
	width: 650px;
	margin: 0px;
}

#user_support_help_center_help {
	max-height: 250px;
	overflow-x: hidden;
}

/* support ticket */
.time-spent > p {
    border: thin black dotted;
    color: red;
    margin-top: 20px;
}
