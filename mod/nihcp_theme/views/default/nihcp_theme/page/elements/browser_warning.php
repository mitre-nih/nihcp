<?php
$src = elgg_get_site_url() . '/mod/nihcp_theme/assets/js/browser-update/update.min.js';
$text = elgg_echo('browser_support_warning');
?>
<script>
	var $buoop = {
		vs:{i:Number.POSITIVE_INFINITY},
		c:2,
		reminder:0,
		text:"<?php echo $text ?>",
		url:"#",
		newwindow:false
	};
	function $buo_f(){
		var e = document.createElement("script");
		e.src = "<?php echo $src ?>";
		document.body.appendChild(e);
	};
	try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
	catch(e){window.attachEvent("onload", $buo_f)}
</script>
