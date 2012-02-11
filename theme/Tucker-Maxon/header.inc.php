<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		header.inc.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/
?>

<!-- GLOBAL HEADER: BENJAMIN F. -->
<header>
	<a class="logo" href="<?php get_site_url(); ?>">
		<img src="<?php get_theme_url() ?>/assets/images/logo.png" alt="Tucker-Maxon School" />
	</a>
	
	<nav>
		<ul>
			<?php get_navigation(return_page_slug()); ?>
		</ul>
	</nav>

</header>