<?php
/*
 * Filename: sidebar.inc.php
 * Desc: Sidebar for Tucker-Maxon site.
 */
?>
		<aside class="sidebar">
			<img class="hero" src="<?php get_custom_field('hero'); ?>" />
			
			<h3><?php if (!get_parent()) : get_page_clean_title(); else: get_parent(); endif ?></h3>
			<nav>
				<ul>
					<?php get_i18n_navigation(return_page_slug(), 1, 99, I18N_SHOW_NORMAL); ?>
				</ul>
			</nav>
		</aside>