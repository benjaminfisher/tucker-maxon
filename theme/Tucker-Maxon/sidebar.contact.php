<?php
/*
 * Filename: sidebar.inc.php
 * Desc: Sidebar for Tucker-Maxon site.
 */
?>
		<aside class="sidebar">
			<img class="hero" src="<?php get_custom_field('hero'); ?>" />
			<nav>
				<ul>
					<?php get_i18n_navigation(return_page_slug(), 1, 99, I18N_SHOW_NORMAL); ?>
				</ul>
				<p class="address"><a href="http://maps.google.com/maps?q=2860+SE+Holgate+Blvd.Portland,+OR+97202&hl=en&ll=45.489908,-122.636207&spn=0.004001,0.006588&sll=45.523452,-122.676207&sspn=0.511863,0.843201&t=h&hnear=2860+SE+Holgate+Blvd,+Portland,+Oregon+97202&z=17" >2860 SE Holgate Blvd.<br />
					Portland, OR 97202<br />
					Phone: 503.235.6551</a>
				</p>
			</nav>
		</aside>
