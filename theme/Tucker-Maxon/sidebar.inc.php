<?php
/*
 * Filename: sidebar.inc.php
 * Desc: Sidebar for Tucker-Maxon site.
 */
?>
		<aside class="sidebar">
			<img class="hero" src="<?php get_custom_field('hero'); ?>" />
			
			<nav>
				<?php get_i18n_navigation(return_page_slug(), 1, 99, I18N_SHOW_NORMAL); ?>
			</nav>
		</aside>