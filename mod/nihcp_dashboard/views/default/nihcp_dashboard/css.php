<?php
/**
 * User dashboard CSS
 */
?>

#dashboard-info {
	border: 2px solid #dedede;
	margin-bottom: 15px;
}

/* items grabbed state */
[draggable="true"][aria-grabbed="true"]
{
    outline:dashed;
    box-shadow:0 0 0 2px #4ba065, inset 0 0 0 1px #ddd;
}

[aria-dropeffect]:focus {
    outline:none;
    box-shadow:0 0 0 3px #4ba065, inset 0 0 0 1px #ddd;	
}