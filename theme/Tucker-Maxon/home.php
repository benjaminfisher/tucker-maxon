<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		home.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php';

get_component('slideshow');

get_component('mission_statement');

echo '<div class="events">' .events_list() .'</div><!-- End Events -->';

get_page_content();

include 'footer.inc.php';

?>