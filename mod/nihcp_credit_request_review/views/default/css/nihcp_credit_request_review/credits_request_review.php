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


    .crr-overview-page {
        overflow: auto;
    }

    .crr-overview-table {
        table-layout: fixed;
        width: 1500px;
    }

    .crr-approver-button {
        float:left;
        width:100px;
        margin:2px;
    }

	.ccr-align-cc-obj .elgg-body, .crr-overview-page, .crr-overview-table {
		overflow:visible!important;
	}

    .crr-overview-incomplete-icon {
        font-size: 300%;
        padding-top: 8%;
    }

    .align-left {
        text-align: left;
    }

    .crr-overview-table th{
        cursor: pointer;
        background-repeat: no-repeat;
        background-position: center right;
    }

    .crr-overview-table .tablesorter-headerUnSorted{
        background-image: url(/mod/nihcp_commons_credit_request/vendor/bower-asset/jquery.tablesorter/dist/css/images/black-unsorted.gif);
    }

    .crr-overview-table .tablesorter-headerDesc{
        background-image: url(/mod/nihcp_commons_credit_request/vendor/bower-asset/jquery.tablesorter/dist/css/images/black-desc.gif);
    }

    .crr-overview-table .tablesorter-headerAsc{
        background-image: url(/mod/nihcp_commons_credit_request/vendor/bower-asset/jquery.tablesorter/dist/css/images/black-asc.gif);
    }
