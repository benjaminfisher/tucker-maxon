<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		footer.inc.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/
?>

<!-- GLOBAL FOOTER: BENJAMIN F. -->
<footer>
	<nav class="tertiary">
		<ul>
			<li class="section_heading"><a href="#">Admissions</a></li>
			<li><a href="#"></a></li>
			<li><a href="#"></a></li>
		</ul>
		<ul>
			<li class="section_heading"><a href="#">About</a></li>
			<li><a href="#"></a></li>
			<li><a href="#"></a></li>
		</ul>
		<ul>
			<li class="section_heading"><a href="#">Programs</a></li>
			<li><a href="#"></a></li>
			<li><a href="#"></a></li>
		</ul>
		<ul>
			<li class="section_heading"><a href="#">Events</a></li>
		</ul>
		<ul>
			<li class="section_heading"><a href="#">Calendar</a></li>
		</ul>
		<ul>
			<li class="section_heading"><a href="#">Contact</a></li>
		</ul>
		<ul>
			<li class="section_heading"><a href="#">Services</a></li>
			<li><a href="#"></a></li>
			<li><a href="#"></a></li>
		</ul>
		<ul>
			<li class="section_heading"><a href="#">Follow us</a></li>
			<li class="social_links"><a href="#">Facebook</a></li>
			<li class="social_links"><a href="#">Twitter</a></li>
			<li class="social_links"><a href="#">YouTube</a></li>
		</ul>
	</nav>
	
	<div class="locate">
		<img src="#" alt="Map" />
		<section class="address">
			<h3>The Tucker-Maxon School</h3>
			<p>2860 SE Holgate Blvd.</p>
			<p>Portland, OR 97202</p>
			<p>Phone: 503.235.6551</p>
		</section>
	</div>
	<p class="copy">&copy; The Tucker-Maxon School 2012. All rights reserved.</p>
</footer>

<script>
	// Put scripts that need to be loaded (internal or external) in a comma seperated
	// list of strings inside the head.js method parameters
	head.js('http://code.jquery.com/jquery.min.js', 
	function(){
		if(!$){
			head.js("<?php get_theme_url(); ?>/assets/js/lib/jquery-1.7.1.min.js")
		};
		
		// Put any polyfills required by IE inside the head.js method parameters in
		// a comma seperated list of strings
		if (head.browser.ie){
			head.js();
		}
	});
</script>