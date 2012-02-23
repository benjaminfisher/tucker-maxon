<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		inner_page.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/
?>
<!DOCTYPE html>
<html>
<head>

	<!-- Site Title -->
	<title><?php get_page_clean_title(); ?> | <?php get_site_name(); ?></title>
	
	<?php get_header(); ?>
	
	<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/inner.css" media="screen" />

</head>
<body id="<?php get_page_slug(); ?>" >
	
	<?php include 'header.inc.php'; ?>
	
	<section id="content">
		<h1><?php get_page_title(); ?></h1>	
			<div id="page-content">
				<div class="page-text">
					<?php get_page_content(); ?>
				</div>
			</div>
	</section>

</body>
</html>