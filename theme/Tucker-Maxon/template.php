<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		inner_page.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php'; ?>
	
		<hgroup>
			<h1><?php get_parent(); ?></h1>	
			<h2><?php get_page_title(); ?></h2>
		</hgroup>
		
		<div class="content">
			<?php get_page_content(); ?>
		</div>
		
<?php include 'footer.inc.php'; ?>