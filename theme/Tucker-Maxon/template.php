<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		inner_page.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php'; ?>
	
		<aside class="sidebar">
			<img class="hero" src="<?php get_custom_field('hero'); ?>" />
			<h3>Programs</h3>
			<ul>
				<li>Preschool</li>
				<li>Kindergarten</li>
				<li>Elementary</li>
			</ul>
		</aside>
		
		<hgroup>
			<h1><?php get_page_title(); ?></h1>	
			<h2><?php get_custom_field('subhead'); ?></h2>
		</hgroup>
		<div class="content>
			<?php get_page_content(); ?>
		</div>