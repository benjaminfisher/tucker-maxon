<?php
  /**
   * Returns image meta data as JSON
   */
  include('../../../gsconfig.php');
  $admin = defined('GSADMIN') ? GSADMIN : 'admin';
  include("../../../${admin}/inc/common.php");
  $loggedin = cookie_check();
  if (!$loggedin) die("Not logged in!");
  $datadir = substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR.'plugins')) . '/data/';
  $picfile = $datadir . 'uploads/'.preg_replace('/\.+\//','',$_GET['p']);

  $exif = exif_read_data($picfile);
  header("Content-Type: text/plain");
  print_r($exif);
  
  ob_start();
  readfile($picfile);
  $source = ob_get_contents();
  ob_end_clean();
  
  $xmpdata_start = strpos($source, "<x:xmpmeta");
  $xmpdata_end = strpos($source, "</x:xmpmeta>");
  $xmpdata = substr($source, $xmpdata_start, $xmpdata_end-$xmpdata_start+12);
  print_r($xmpdata);
  
  $arrSize = getimagesize($picfile,$arrInfo);
  $arrIPTC = iptcparse($arrInfo['APP13']);
  $arrReturn = array();
  if (is_array($arrIPTC)) {
    $arrReturn['title']         = $arrIPTC['2#105'][0];
    $arrReturn['documentTitle'] = $arrIPTC['2#005'][0];
    $arrReturn['description']   = $arrIPTC['2#120'][0];
    $arrReturn['descriptionAuthor']   = $arrIPTC['2#122'][0];
    $arrReturn['author']        = $arrIPTC['2#080'][0];
    $arrReturn['authorTitle']   = $arrIPTC['2#085'][0];
    $arrReturn['documentTitle'] = $arrIPTC['2#005'][0];
    $arrReturn['copyright']     = $arrIPTC['2#116'][0];
    $arrReturn['keywords']      = $arrIPTC['2#025'][0];
    $arrReturn['category']      = $arrIPTC['2#015'][0];
    $arrReturn['city']          = $arrIPTC['2#090'][0];
    $arrReturn['state']         = $arrIPTC['2#095'][0];
    $arrReturn['country']       = $arrIPTC['2#101'][0];
    $arrReturn['instruction']   = $arrIPTC['2#040'][0];
    $arrReturn['creationTime']  = substr($arrIPTC['2#055'][0],6,2).'.'.substr($arrIPTC['2#055'][0],4,2).'.'.substr($arrIPTC['2#055'][0],0,4);
  }
  print_r($arrIPTC);
  
  print_r($arrReturn);
  