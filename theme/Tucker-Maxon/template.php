<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		template.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php';
	
if(return_page_slug() != 'index' && strlen(return_parent()) != 0) get_component('sidebar');
?>

	<section class="content">
		<?php get_page_content(); ?>
		
	</section>
	
	<?php include 'footer.inc.php'; ?>
	
</body>
</html>
