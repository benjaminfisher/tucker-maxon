<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		inner_page.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php';
include 'sidebar.inc.php';
?>
	<div class="content">
		<hgroup>
			<?php if (get_parent()) : ?>
				<h1><?php get_parent(); ?></h1>
			<?php endif; ?>
			<h2><?php get_page_title(); ?></h2>
		</hgroup>
	
		<?php get_page_content(); ?>
	</div>
		
<?php include 'footer.inc.php'; ?>