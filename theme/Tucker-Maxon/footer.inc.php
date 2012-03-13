<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		footer.inc.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/
?>

</div><!-- End Content Wrapper -->

<!-- GLOBAL FOOTER: BENJAMIN F. -->
<footer>
	<section class="top">
		<nav class="tertiary">
			<div class="column">
				<ul>
					<li class="heading"><a href=<?php get_site_url(); ?>admissions>Admissions</a></li>
					<li><a href=<?php get_site_url(); ?>admissions#hearing>Hearing</a></li>
					<li><a href=<?php get_site_url(); ?>admissions#hearing-impaired>Hearing Impaired</a></li>
				</ul>
				<ul>
					<li class="heading"><a href=<?php get_site_url(); ?>about>About</a></li>
					<li><a href="#">History</a></li>
					<li><a href="#">Board/Staff</a></li>
					<li><a href="#">Technology</a></li>
					<li><a href="#">Multimedia</a></li>
					<li><a href="#">Community Partnership</a></li>
				</ul>
			</div>

			<div class="column">
				<ul>
					<li class="heading"><a href="#">Programs</a></li>
					<li><a href="#">Preschool</a></li>
					<li><a href="#">Elementary</a></li>
					<li><a href="#">Kindergarten</a></li>
					<li><a href="#">Summer Program</a></li>
					<li><a href="#">Music and Art</a></li>
				</ul>
				<ul>
					<li class="heading"><a href="#">Events</a></li>
				</ul>
				<ul>
					<li class="heading"><a href="#">Calendar</a></li>
				</ul>
				<ul>
					<li class="heading"><a href="#">Contact</a></li>
				</ul>
			</div>

			<div class="column">
				<ul>
					<li class="heading"><a href="#">Services</a></li>
					<li><a href="#">Audiology</a></li>
					<li><a href="#">Speech Pathology</a></li>
					<li><a href="#">Before/After School Care</a></li>
					<li><a href="#">Facility Rental</a></li>
					<li><a href="#">Insurance</a></li>
				</ul>
				<ul class="social">
					<li class="heading"><a href="#">Follow us</a></li>
					<li class="social_link"><a href="#">Facebook</a></li>
					<li class="social_link"><a href="#">Twitter</a></li>
					<li class="social_link"><a href="#">YouTube</a></li>
				</ul>
			</div>
		</nav>

		<div class="locate">
			<a id="donate" href="javascript:void(0)">Donate</a>
			<section class="address">
				<h3>The Tucker-Maxon School</h3>
				<p>2860 SE Holgate Blvd.</p>
				<p>Portland, OR 97202</p>
				<p>Phone: 503.235.6551</p>
			</section>
		</div>
	</section>

	<p class="tagline"><?php get_component('tagline'); ?></p>
	<p class="copy">&copy; The Tucker-Maxon School 2012. All rights reserved.</p>
</footer>

<!--script language="JavaScript">
	// Put scripts that need to be loaded (internal or external) in a comma seperated
	// list of strings inside the head.js method parameters
head.js('http://code.jquery.com/jquery.min.js', '<?php get_theme_url(); ?>/assets/js/ga.js',
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
</script-->

</body>
</html>
