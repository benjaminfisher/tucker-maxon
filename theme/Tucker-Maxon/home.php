<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		home.php
* @Package:		GetSimple
* @Action:		Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php';

get_i18n_gallery('slideshow'); ?>

<section class="home group">
  <div class="mission">
    <h2>Mission Statement</h2>
    <p><?php get_component('mission_statement'); ?></p>
    <a href="javascript:void(0)" class="learn-more">Learn More</a>
  </div>

  <div class="events">
    <h2>Latest News</h2>
    <article>
      <?php echo '<h2>Upcoming events</h2><div class="feature">'.upcoming_events($SITEURL.'events/', 'strong').'</div>' ?>
    </article>
    <a href="javascript:void(0)" class="learn-more">Learn More</a>
  </div>
</section>

<div role="main" class="content"><?php get_page_content(); ?></div>

<?php include 'footer.inc.php'; ?>
