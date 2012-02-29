<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		header.inc.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

if(return_page_slug() == 'index') {
	$style_sheet = 'main.css';
} else {
	$style_sheet = 'inner.css';
	//if(get_parent(FALSE) == 'index') {$style_sheet = get_page_slug().'.css'; } else { $style_sheet = get_parent().'.css'; }
} ?>
<!DOCTYPE html>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<title><?php return_page_slug(); ?> | <?php get_site_name(); ?></title>
	
	<?php get_header(); ?>
	
	<script src="<?php get_theme_url(); ?>/assets/js/head.min.js" charset="utf-8"></script>
	<link rel="stylesheet" href="<?php echo get_theme_url() ."/assets/css/$style_sheet"; ?>" media="screen" />

<body class="<?php get_page_slug(); ?>" >

<header>
	<!-- GLOBAL HEADER: BENJAMIN F. -->
	<a class="logo" href="<?php get_site_url(); ?>">
		<img src="<?php get_theme_url() ?>/assets/images/logo.png" alt="Tucker-Maxon School" />
	</a>
	
	<nav class="global">
		<ul>
			<?php get_navigation(return_page_slug()); ?>
		</ul>
	</nav>
	
	<nav class="secondary">
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="#">Donations</a></li>
			<li><a href="#">Events</a></li>
			<li><a href="#">Calendar</a></li>
			<li><a href="#">Contact</a></li>
		</ul>
	</nav>
</header>
<div role="main" class="content">