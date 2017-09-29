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
	$menu = elgg_view_menu('walled_garden', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-general elgg-menu-hz',
	));

?>
<style>

	/* Only apply this on this page */
	.elgg-body-walledgarden {
		width: 100%;
		margin-top: -2.1em;
	}

	#elgg-walledgarden-login {
		padding: 0;
		margin: 0;
	}

	.elgg-module-walledgarden > .elgg-body {
		margin: 0;
		padding: 0;
	}

	.elgg-module-walledgarden > .elgg-foot {
		padding: 0;
	}

</style>

<script>
	// This takes into account the fixed header
	function scroll_if_anchor(href) {
		href = typeof(href) == "string" ? href : $(this).attr("href");

		var fromTop = 89;

		if(href.indexOf("#") == 0) {
			var $target = $(href);
			if ($target.length) {
				$('html, body').animate({scrollTop: $target.offset().top - fromTop});
				if (history && 'pushState' in history) {
					history.pushState({}, document.title, window.location.pathname + href);
					return false;
				}
			}
		}
	}
	scroll_if_anchor(window.location.hash);
	$("body").on("click", "a", scroll_if_anchor);

	function doScroll() {
		$("#nihcp_site_header").css("top", $(this).scrollTop()+129);
	}	
	$(document).on("scroll", doScroll);

</script>

<div class="nihcp_theme_login">
	<div class="header" id="nihcp_site_header">

		<div class="logo">
			<img alt="nihcp logo" src="mod/nihcp_theme/graphics/nihcp_logo.png"/>
		</div>

		<div class="left">Commons Credits Pilot Portal</div>

		<div class="right">
			<a href="#features" onclick="$('html', 'body').animate({scrollTop: $('#features').offset().top - 100 }, 'slow');">Features</a>
			<a href="#faq">FAQ</a>
			<a id="loginLink" href="login">Login</a>
		</div>

	</div>

	<div class="jumbotron">

		<div class=transbox>
			<div class="transbox_text">
				The NIH Commons is a program dedicated to providing access to 
				scalable storage and computation capabilities to support 
				NIH-funded research programs. It also encourages the sharing of 
				digital objects resulting from NIH research including data,
				metadata, software, workflows, and other electronic artifacts.
				<br/><br/>
				The Commons Credits Pilot Portal is part of a new pay-as-you-go
				funding approach for gaining computational resources for your
				scientific investigations. It is your place to submit credit
				applications for additional resources for extending computational
				research on active NIH grants.
			</div>
			<a id="signUp" href="register">Sign Up</a>
		</div>

	</div>

	<div id="features">

		<div id="features-section" class="title">Features</div>

		<div class="title2">Research Conformant Cloud Services</div>
		<div>
			Use the Cloud Services Catalog to help you choose which cloud provider 
			would best fit your study needs.
		</div>
		<img alt="nih cloud service provider" src="mod/nihcp_theme/graphics/nih-cloud-service-provider.png"/>

		<div class="title2">Fill Out Credit Applications</div>
		<div>
			The Commons Credits application has been designed to be easy to fill out.
		</div>
		<img alt="nih request form" src="mod/nihcp_theme/graphics/nih-request-form.png"/>

		<div class="title2">Track Your Credit Applications and Balances</div>
		<div>
			Stay on top of your project credit balances.
		</div>
		<img alt="nih credit allocation" src="mod/nihcp_theme/graphics/nih-credit-allocation.png"/>

	</div>

	<div id="faq">	

		<div class="title">Frequently Asked Questions</div>

		<div class="question">Who can use the Commons Credits Pilot Portal?</div>
		<div class="answer">
			<span class="label">A:</span>
			Any NIH investigator in need of cloud-based computational
			resources to complete their NIH study.
		</div>

		<div class="question">What do I need to sign up?</div>
		<div class="answer">
			<span class="label">A:</span>
			You need either a .edu or .org email address to sign up.
		</div>

		<div class="question">How can I see what cloud service providers you have?</div>
		<div class="answer">
			<span class="label">A:</span>
			You need to sign up to view the cloud services providers.
		</div>

		<div class="question">What services are eligble for purchase with Commons Credits?</div>
		<div class="answer">
			<span class="label">A:</span>
			Commons Credits may be used to obtain IT services from conformant vendors, including data storage, hosting, virtual machines, and computational cycles.
		</div>

	</div>

	<div class="footer">
		<div>
			For questions or comments regarding this site, please email 
			<a href="mailto:commons_credits@mitre.org">commons_credits@mitre.org</a>
		</div>
		<div>
			<?php echo $menu ?>
		</div>
	</div>
</div>