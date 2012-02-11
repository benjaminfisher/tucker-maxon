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
	<?php get_footer(); ?>
	<p class="credits"><?php get_site_credits(); ?></p>
	<p class="page-meta">Published on &nbsp;<span><?php get_page_date('F jS, Y'); ?></span></p>
</footer>