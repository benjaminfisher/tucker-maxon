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
	<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/inner_notsass.css" media="screen" />

</head>
<body id="<?php get_page_slug(); ?>" class="inner_page">
	
	<?php include 'header.inc.php'; ?>
	
	<section id="content">
		<div id="left-content">
			<img id="hero-image" src="<?php get_custom_field('hero'); ?>" />
			<h3>Programs</h3>
			<ul>
				<li>Preschool</li>
				<li>Kindergarten</li>
				<li>Elementary</li>
			</ul>
		</div>
		<h1><?php get_page_title(); ?></h1>	
		<h2><?php get_custom_field('subhead'); ?></h2>
			<div id="page-content">
				<div class="page-text">
					<?php get_page_content(); ?>
				</div>
			</div>
	</section>

</body>
</html>