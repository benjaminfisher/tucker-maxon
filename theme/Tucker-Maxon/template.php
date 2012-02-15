<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		template.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/
?>

<?php include 'header.inc.php'; ?>

<body id="<?php get_page_slug(); ?>" >
	
	<?php get_page_content(); ?>
	
	<?php include 'footer.inc.php'; ?>
	
</body>
</html>
