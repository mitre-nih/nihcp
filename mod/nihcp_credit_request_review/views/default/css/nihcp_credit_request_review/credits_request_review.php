<?php
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
