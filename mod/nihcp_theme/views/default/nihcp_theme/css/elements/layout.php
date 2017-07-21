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
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 */
?>
/* <style> /**/


/***** PAGE HEADER ******/

.elgg-page-topbar{
    background-color:#20558a
}

.elgg-page-header {
    background-color: #fff;
}

.elgg-menu-site > .elgg-state-selected > a, .elgg-menu-site > li:hover > a {
    background-color: #20558a
}

.elgg-heading-site {
    color:#616265;
    font-family: "Source Sans Pro","Helvetica Neue", Helvetica, "Lucida Grande", Arial, sans-serif;
	text-shadow: none;
}

.elgg-heading-site:hover {
	color:#616265;
	font-family: "Source Sans Pro","Helvetica Neue", Helvetica, "Lucida Grande", Arial, sans-serif;
}

.elgg-page-header > .elgg-inner {
	margin:auto!important;
	width:60%;
}

.elgg-page-header h1 {

	position: absolute;
	left:120px;
	font-size:1.5vw;
	padding-left:0;
	margin-left:0;
	top: 50%;
	transform: translateY(-50%);
	/*white-space:nowrap;*/
}

@media screen and (min-width:1800px) {
	.elgg-page-header h1 {
		font-size: 2em;
	}
}

.elgg-page-header img {
	position:relative;
	float: left;
	height: 60px;
	width: auto;
	padding-right:0;
	margin-right:0;
}

@media screen and (max-width:480px) {
	.elgg-menu-topbar-alt {
		font-size: .6em;
	}
}

/***** PAGE FOOTER ******/


.elgg-menu-item-powered {
	display: none!important;
}

/* Page body */

body {
    background-color: #e4e2da;
    color: #333;
    font-family: "Source Sans Pro","Helvetica Neue", Helvetica, "Lucida Grande", Arial, sans-serif;
    font-size: 11pt;
}

a {
    color: #004fba;
}

.elgg-body .elgg-menu, .elgg-body li[class^="elgg-menu-item-"] > a {
    color:slategray;
}

.elgg-body {
    padding: 10px;
    background-color: #fff;
}

.elgg-page-navbar {
    background-color: #1e3b61;
}

.elgg-breadcrumbs, .elgg-icon-report-this, .elgg-icon-rss, .elgg-module-aside {
	display:none;
}

/* sidebar */

.elgg-sidebar {
    background-color: #fff;
    padding: 10px;
}

/* misc */

.elgg-button.disabled {
	color:#585858;
}

/* widgets */

.elgg-module-widget > .elgg-head {
	background-color:#fff;
}

.elgg-head .elgg-widget-title {
	color:#333333;
}

.elgg-page-footer > .elgg-inner, .elgg-sidebar,
.elgg-head, .elgg-item, .elgg-menu, .elgg-input-file {
	border-color: darkgrey!important;
}

.elgg-widgets .elgg-body {
	width:95%;
}

/* get rid of input number spinner */
input[type='number'] {
	-moz-appearance:textfield;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
	-webkit-appearance: none;
}

.border {
	border-width: 1px;
	border-color: black;
}

.dashed {
	border-style: dashed;

}

.solid {
	border-style: solid;
}

/* hide tabs for "Mine" and "Friends" content*/
.elgg-menu.elgg-menu-filter.elgg-menu-hz.elgg-menu-filter-default {
	display: none;
}

/* cloud service catalog */

.catalog-body {
	margin-top: 10px;
}
.catalog-page {
	width: 110%;
	height: 100%;
	position: absolute;
	left: 0;
	z-index: 10;
	margin:0;
	padding:0;
}

.catalog-page .elgg-head {
	display:none;
}

.catalog-page > .elgg-main {
	padding:0;
	margin:0;
	overflow:auto;
}

.catalog-page > .elgg-sidebar {
	float:left;
	width:10%;
}

.catalog-body td {
	white-space:normal!important;
}

/* styles for the tables in Commons Credit Portal */

/* Setting styles for all elgg tables */
.elgg-table caption {
	font-size: 1.25em;
	padding: 10px;
	background-color: #20558a;
	color: white; }
.elgg-table th {
	text-align: left; }
.elgg-table td {
	text-align: center;
	vertical-align: top; }

/* Setting styles for the cloud service comparison table */
.cloud-table thead.table-head-titles th {
	text-align: center;
	border: 1px solid #e4e2da;
	background-color: #e4e2da;
	font-size: 1.4em;
	width: 20%; }
.cloud-table thead.table-head-titles th:first-child {
	text-align: left; }
.cloud-table th p {
	font-size: 0.9em;
	font-weight: normal; }
.cloud-table tbody > tr:first-child > th {
	background-color: #f2f2ed;
	padding-top: 15px;
	font-size: 1.25em; }
.cloud-table tr.align-middle td {
	vertical-align: middle; }
.cloud-table td em {
	color: #616265; }

/* utility classes */
.positive {
	color: #008a00; }

.negative {
	color: red; }

.sr-only {
	position: absolute;
	width: 1px;
	height: 1px;
	margin: -1px;
	padding: 0;
	overflow: hidden;
	clip: rect(0, 0, 0, 0);
	border: 0; }

/* ***************************************
	Tables
*************************************** */

td {
    word-wrap: break-word;
}

.elgg-table {
	width: 100%;
	border-collapse: collapse; }

.elgg-table td, .elgg-table th {
	padding: 4px 8px;
	border: 1px solid #e2e2e2; }

.elgg-table th {
	background-color: #fff; }

.elgg-table td {
	max-width: 350px;
}

input {
	width: auto;
}

.elgg-widget-content table.elgg-table {
	table-layout: fixed;
}

.elgg-widget-content .elgg-table td:not(.ccreq-status), .elgg-widget-content .elgg-table td:not(.ccreq-status) * {
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

/* Tooltip container */
.tooltip {
	position: relative;
	display: inline-block;
}

/* Tooltip text */
.tooltip .tooltiptext {
	visibility: hidden;
	width: 300px;
	background-color: white;
	text-align: center;
	padding: 5px;
	border-radius: 5px;
	border: thick solid #1e3b61;

	/* Position the tooltip text - see examples below! */
	position: absolute;
	z-index: 1;
}

.tooltipborder {
	border-bottom: thin dashed black;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
	visibility: visible;
}

.ccr-overview-layout > .elgg-body, .crr-overview-layout > .elgg-body,
.elgg-widget-instance-nihcp-commons-credit-request > .elgg-body, .elgg-widget-instance-nihcp-commons-credit-request {
	overflow: visible!important;
}

.feedback {
	text-align: left!important;
}