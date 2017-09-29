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
 

?>
/* <style> /**/

.ccreq-delegate-button a {
    color: white;
}

.controlled-access-text {
    color: blue;
}

.hiviz {
    color: red;
}

.required-icon:before {
	content: '*';
}

.elgg-menu-content {
    padding: 5px;
}

table a {
    display:block;
    text-decoration:none;
}

.elgg-widget-content .nihcp-ccreq-cycle-select {
	margin-bottom: 5px;
	display:block;
}

#proposed_research {
    height: 300px;
}

#datasets, #applications_tools, #workflows {
    height: 200px;
}

textarea {
    resize:vertical;
}

/* Terms and Conditions page formatting */

p.MsoNormal, li.MsoNormal, div.MsoNormal
{margin-top:0in;
    margin-right:0in;
    margin-bottom:8.0pt;
    margin-left:0in;
    line-height:107%;
    font-size:11.0pt;
    font-family:Calibri;}
p.MsoCommentText, li.MsoCommentText, div.MsoCommentText
{margin-top:0in;
    margin-right:0in;
    margin-bottom:8.0pt;
    margin-left:0in;
    font-size:10.0pt;
    font-family:Calibri;}
p.MsoHeader, li.MsoHeader, div.MsoHeader
{margin:0in;
    margin-bottom:.0001pt;
    font-size:11.0pt;
    font-family:Calibri;}
p.MsoFooter, li.MsoFooter, div.MsoFooter
{margin:0in;
    margin-bottom:.0001pt;
    font-size:11.0pt;
    font-family:Calibri;}

p.MsoCommentSubject, li.MsoCommentSubject, div.MsoCommentSubject
{margin-top:0in;
    margin-right:0in;
    margin-bottom:8.0pt;
    margin-left:0in;
    font-size:10.0pt;
    font-family:Calibri;
    font-weight:bold;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
{margin:0in;
    margin-bottom:.0001pt;
    font-size:9.0pt;
    font-family:"Segoe UI","sans-serif";}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
{margin-top:0in;
    margin-right:0in;
    margin-bottom:8.0pt;
    margin-left:.5in;
    line-height:107%;
    font-size:11.0pt;
    font-family:Calibri;}
span.CommentSubjectChar
{font-weight:bold;}
span.BalloonTextChar
{font-family:"Segoe UI","sans-serif";}
.MsoChpDefault
{font-size:11.0pt;
    font-family:Calibri;}
.MsoPapDefault
{margin-bottom:8.0pt;
    line-height:107%;}
/* Page Definitions */
@page WordSection1
{size:8.5in 11.0in;
    margin:1.0in 1.0in 1.0in 1.0in;}
div.WordSection1
{page:WordSection1;}

.rationale{
    display: none;
}

.verified_status{
    display: none;
}

.ccreq-overview-table th{
    cursor: pointer;
    background-repeat: no-repeat;
    background-position: center right;
}

    .ccreq-overview-table .tablesorter-headerUnSorted{
        background-image: url(/mod/nihcp_commons_credit_request/vendor/bower-asset/jquery.tablesorter/dist/css/images/black-unsorted.gif);
    }

    .ccreq-overview-table .tablesorter-headerDesc{
        background-image: url(/mod/nihcp_commons_credit_request/vendor/bower-asset/jquery.tablesorter/dist/css/images/black-desc.gif);
    }

    .ccreq-overview-table .tablesorter-headerAsc{
        background-image: url(/mod/nihcp_commons_credit_request/vendor/bower-asset/jquery.tablesorter/dist/css/images/black-asc.gif);
    }
