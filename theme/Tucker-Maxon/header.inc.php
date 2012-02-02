<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		header.inc.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/
?>
<header>
	
	<nav>
		<ul>
			<?php get_navigation(return_page_slug()); ?>
		</ul>
	</nav>
	
	<span class="logo2" href="<?php get_site_url(); ?>"><?php get_site_name(); ?></span>
	<a class="logo" href="<?php get_site_url(); ?>"><?php get_site_name(); ?></a>

</header>