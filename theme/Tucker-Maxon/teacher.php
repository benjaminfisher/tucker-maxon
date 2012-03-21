<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File:      staff.php
* @Package:   GetSimple
* @Action:    Tucker-Maxon theme for the GetSimple CMS
*
*
*****************************************************/

include 'header.inc.php';
include 'sidebar.inc.php';
?>
  <div class="content">
    <hgroup>
			<h2><?php get_custom_field('name'); ?></h2>
    </hgroup>

		<section>
			<h3><?php get_custom_field('class'); ?></h3>
			<p><?php get_custom_field('summary'); ?></p>
			<h3>Current Events</h3>
			<?php get_page_content(); ?>
		</section>

  </div>
    
<?php include 'footer.inc.php'; ?>
