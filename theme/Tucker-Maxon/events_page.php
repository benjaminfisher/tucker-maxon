<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File:      template.php
* @Package:   GetSimple
* @Action:    Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php';
include 'sidebar.inc.php';
?>
  <div class="content">
    <hgroup>
      <h2><?php get_page_title(); ?></h2>
    </hgroup>
  
    <?php get_page_content(); ?>

    <?php echo events_list() ?>
  </div>
    
<?php include 'footer.inc.php'; ?>
