<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		home.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php';

get_component('slideshow'); ?>

<div class="mission">
	<p><?php get_component('mission_statement'); ?></p>
</div>

<?php echo '<div class="events">' .events_list() .'</div><!-- End Events -->';

echo '<div role="main" class="content">' .get_page_content() .'</div>';

include 'footer.inc.php';

?>