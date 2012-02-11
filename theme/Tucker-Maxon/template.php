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
	
	<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/main.css" media="screen" />

</head>
<body id="<?php get_page_slug(); ?>" >
	
	<?php include 'header.inc.php'; ?>
	
	<!-- INDEX BODY CONTENT: JAKE R. -->
	<section id="homeWrapper">
		
		<div id="slideshow">
			<header>
				<h1>Welcome</h1>
				<h2>Slogan or Desription for slide</h2>
			</header>
		</div><!-- END: slideshow -->
		
		<section id="feature">
		
			<article id="mission">
				<header>
					<h1>Mission Statement</h1>
				</header>
				<p>Here is the content for the mission statement.</p>
			</article>
			
			<article id="news">
				<header>
					<h1>News/Events</h1>
				</header>
				<p>Here is the content for the News/Events.</p>
			</article>
			
		</section><!-- END: featured -->
		
		<section id="schoolFeature">
		
			<div class="gradeLevel" id="preschool">
				<h1>Preschool</h1>
			</div>
			
			<div class="gradeLevel" id="elementary">
				<h1>Elementary</h1>
			</div>
			
			<div class="gradeLevel" id="kindergarten">
				<h1>Kindergarten</h1>
			</div>
			
		</section> <!-- END: schoolCategory -->
	</section> <!-- END: homeWrapper -->
	
	<?php include 'footer.inc.php'; ?>
	
</body>
</html>
