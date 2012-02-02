<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		template.php
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
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/global.css" media="screen" />

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
		
	<aside id="sidebar">
		<div class="section">
			<?php get_component('sidebar');	?>
		</div>
		<div class="section credits">
			<p><?php echo date('Y'); ?> - <strong><?php get_site_name(); ?></strong></p>
		</div>
	</aside>
	
	<footer>
		<?php get_footer(); ?>
		<p class="credits"><?php get_site_credits(); ?></p>
		<p class="page-meta">Published on &nbsp;<span><?php get_page_date('F jS, Y'); ?></span></p>
	</footer>
	
</body>
</html>