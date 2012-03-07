<?php
  /**
   * Basic File Browser for I18N Gallery
   *
   * Displays and selects file link to insert
   */
  include('../../../gsconfig.php');
  $admin = defined('GSADMIN') ? GSADMIN : 'admin';
  include("../../../${admin}/inc/common.php");
  $loggedin = cookie_check();
  if (!$loggedin) die("Not logged in!");
  if (isset($_GET['path'])) {
    $subPath = preg_replace('/\.+\//','',$_GET['path']);
    if ($subPath) $subPath .= '/';
    $path = "../../../data/uploads/".$subPath;
  } else {
    $subPath = "";
    $path = "../../../data/uploads/";
  }
  $path = tsl($path);

  // check if host uses Linux (used for displaying permissions
  $isUnixHost = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? false : true);
  $path_parts = pathinfo($_SERVER['PHP_SELF']);
  $dir = str_replace("/plugins/i18n_gallery/browser", "", $path_parts['dirname']);
  $fullPath = htmlentities("http://".$_SERVER['SERVER_NAME'].($dir == '/' ? "" : $dir)."/data/uploads/", ENT_QUOTES);
  $sitepath = htmlentities("http://".$_SERVER['SERVER_NAME'].($dir == '/' ? "" : $dir)."/", ENT_QUOTES);

  $func = @$_GET['func'];
  $w = (int) @$_GET['w'];
  $h = (int) @$_GET['h'];
  if (!$w && !$h) { $w = 160; $h = 120; }

  if(!defined('IN_GS')){ die('you cannot load this page directly.'); }

  global $LANG;
  $LANG_header = preg_replace('/(?:(?<=([a-z]{2}))).*/', '', $LANG);
	$count="0";
	$dircount="0";
	$counter = "0";
	$totalsize = 0;
	$filesArray = array();
	$dirsArray = array();

  clearstatcache();
  $dir_handle = opendir($path) or die("Unable to open $path");
	while ($file = readdir($dir_handle)) {
		if ($file == "." || $file == ".." || $file == ".htaccess" ){
		  // not a upload file
		} elseif (is_dir($path . $file)) {
		  $dirsArray[$dircount]['name'] = $file;
		  $dircount++;
		} else {
			$ext = @strtolower(substr($file, strrpos($file, '.') + 1));
      if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') {
			  $ss = @stat($path . $file);
        list($width,$height) = getimagesize($path . $file);
        $filesArray[] = array('name' => $file, 'date' => @date('M j, Y',$ss['ctime']), 'size' => fSize($ss['size']), 
                              'bytes' => $ss['size'], 'width' => $width, 'height' => $height);
			  $totalsize = $totalsize + $ss['size'];
			  $count++;
      }
		}
	}
	$filesSorted = subval_sort($filesArray,'name');
	$dirsSorted = subval_sort($dirsArray,'name');

	$pathParts=explode("/",$subPath);
	$urlPath="";
?>
<!DOCTYPE html>
<html lang="<?php echo $LANG_header; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"  />
	<title><?php echo i18n_r('FILE_BROWSER'); ?></title>
	<link rel="shortcut icon" href="../../../<?php echo $admin; ?>/favicon.png" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="../../../<?php echo $admin; ?>/template/style.php?v=<?php echo GSVERSION; ?>" media="screen" />
	<style>
		.wrapper, #maincontent, #imageTable { width: 100% }
	</style>
	<script type='text/javascript'>
	function submitLink(url, size, width, height) {
		if(window.opener){
			window.opener.<?php echo $func; ?>(url, size, width, height);
		}
	}
	</script>
</head>
<body id="imagebrowser" >	
  <div class="wrapper">
  <div id="maincontent">
	  <div class="main" style="border:none;">
		  <h3><?php i18n('UPLOADED_FILES'); ?></h3>
      <div class="h5">/ <a href="?func=<?php echo $func; ?>&amp;w=<?php echo $w; ?>&amp;h=<?php echo $h; ?>">uploads</a> / 
<?php 
  foreach ($pathParts as $pathPart){
		if ($pathPart!=''){
			$urlPath.=$pathPart."/";
?>
        <a href="?path=<?php echo $urlPath; ?>&amp;func=<?php echo $func; ?>&amp;w=<?php echo $w; ?>&amp;h=<?php echo $h; ?>"><?php echo $pathPart; ?></a> / 
<?php
		}
	}
?>
      </div>
      <table class="highlight" id="imageTable">
        <tbody>
<?php
	if (count($dirsSorted) != 0) {       
		foreach ($dirsSorted as $upload) {
			$p = ($subPath ? $subPath . "/" : "") . $upload['name']; 
?>
          <tr class="All" > 
		        <td class="" colspan="5">
		          <img src="../../../<?php echo $admin; ?>/template/images/folder.png" width="11" /> 
              <a href="imagebrowser.php?path=<?php echo $p; ?>&amp;func=<?php echo $func; ?>&amp;w=<?php echo $w; ?>&amp;h=<?php echo $h; ?>" title="<?php echo $upload['name']; ?>"><strong><?php echo $upload['name']; ?></strong></a>
		        </td>
          </tr>
<?php
		}
	}

	if (count($filesSorted) != 0) { 			
		foreach ($filesSorted as $upload) {
      $onclick = 'submitLink(\''.$subPath.$upload['name'].'\','.$upload['bytes'].','.$upload['width'].','.$upload['height'].')';
			if ($isUnixHost && defined('GSDEBUG') && function_exists('posix_getpwuid')) {
				$filePerms = substr(sprintf('%o', fileperms($path.$upload['name'])), -4);
				$fileOwner = posix_getpwuid(fileowner($path.$upload['name']));
			}
?>
          <tr class="All images">
            <td>
              <a href="javascript:void(0)" title="<?php i18n('SELECT_FILE').': '.htmlspecialchars(@$upload['name']); ?>" onclick="<?php echo $onclick; ?>">
                <img src="pic.php?p=<?php echo $subPath.$upload['name']; ?>&amp;w=<?php echo $w; ?>&amp;h=<?php echo $h; ?>"/>
              </a>
            </td>
            <td>
              <a class="primarylink" href="javascript:void(0)" title="<?php i18n('SELECT_FILE').': '.htmlspecialchars(@$upload['name']); ?>" onclick="<?php echo $onclick; ?>">
                <?php echo htmlspecialchars($upload['name']); ?>
              </a>
            </td>
            <td><span><?php echo $upload['width']; ?> x <?php echo $upload['height']; ?></span></td>
			      <td style="width:80px;text-align:right;" ><span><?php echo $upload['size']; ?></span></td>
<?php	if (isset($filePerms) && isset($fileOwner['name'])) { ?>
					  <td style="width:70px;text-align:right;"><span><?php echo $fileOwner['name']; ?>/<?php echo $filePerms; ?></span></td>
<?php } ?>
            <td style="width:85px;text-align:right;" ><span><?php echo shtDate($upload['date']); ?></span></td>
			    </tr>
<?php
		}
	}
?>
        </tbody>
      </table>
	    <p><em><b><?php echo $counter; ?></b> <?php i18n('TOTAL_FILES'); ?> (<?php echo fSize($totalsize); ?>)</em></p>
    </div>
  </div>
  </div>	
</body>
</html>
