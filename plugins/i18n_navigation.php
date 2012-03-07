<?php
/*
Plugin Name: I18N Navigation
Description: Multilevel navigation & breadcrumbs (I18N enabled)
Version: 2.6
Author: Martin Vlcek
Author URI: http://mvlcek.bplaced.net

The menu functions return a hierarchical menu based on the "parent", "menuStatus" and "menuOrder" attributes of the 
pages. The root menu consists of all pages with menuStatus = Y and no parent.

Public functions:
  return_i18n_pages()
      returns an associative array of pages with the attributes url (slug), menuData, menuOrder, title, menu,
      parent (the parent's url/slug) and the other languages' titles and menus (as e.g. title_en, menu_en).
      additionally a sorted array of the children urls/slugs is available in the attribute children.
      the page with url/slug null contains the toplevel pages in the children array.
  return_i18n_structure($slug=null, $menuOnly=true, $slugToIgnore=null)
      returns the structure of the site in a flat sorted array where each entry is an associative array with
      the attributes url (slug), menuStatus, title, menu and level.
      If $slug is given, only the children (and their children, ...) of this page are returned.
      $menuOnly=false returns all pages, even if they are not in the menu.
      if $slugToIgnore is given, this page and all its children (...) are ignored.      
  return_i18n_menu_data($slug, $minlevel=0, $maxlevel=0, $show=I18N_SHOW_NORMAL) {
      returns the menu tree from level $minlevel to $maxlevel, where the children of the $slug and of all (recursive)
      parents of the $slug are shown, if permitted by level and the menuStatus attribute of the child. A parent with
      menuStatus = N is not shown and neither are its children. With $show=I18N_SHOW_MENU all sub menus are shown independent
      of the current page, I18N_SHOW_PAGES shows all pages whether they are in the menu or not, I18N_SHOW_TITLES is the same
      but shows the titles instead of the menu texts and I18N_SHOW_LANGUAGE is like
      I18N_SHOW_NORMAL, but only shows pages in the current language
      A tree node has the attributes "parent", "url" (slug, String), "title", "menu" (localized strings), 
      "children" (array), "current" (true, if current page), "currentpath" (true, if in path to current page) and
      "haschildren" (true, if the page has children).
  return_i18n_breadcrumbs($slug)
      returns an array with breadcrumbs, each with attributes url, parent, menu, title

Display functions:
  get_i18n_navigation($slug, $minlevel=0, $maxlevel=0, $show=I18N_SHOW_NORMAL)
      outputs the (localized) menu for the $slug (the menu tree returned by return_i18n_menu_data) as list items and sub lists.
      You must enclose the result in <ul>...</ul>
      The list items have the following classes:
        - slug of the page
        - slug of the page's parent
        - "current", if the page is the current page
        - "currentpath", if the page is a parent, grandparent, etc. of the current page
        - "open", if the page has children whose menu items are currently displayed
        - "closed", if the page has children whose menu items are currently not displayed
  get_i18n_breadcrumbs($slug)
      outputs the breadcrumbs each as " &raquo; <span class="breadcrumb"><a ...</a></span>"
*/

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");
$i18n_pages = null;

# --- CHANGE THESE IF NEEDED - not recommended ---
define('I18N_USE_CACHE', true);
# --- CHANGE THESE END ---------

define('I18N_CACHE_FILE', 'i18n_menu_cache.xml'); # cache file in data/other

# parameter for the navigation function
define('I18N_SHOW_NORMAL', 0);       # show sub tree of current page including children of current page
define('I18N_SHOW_MENU', 1);         # show all menu entries independent of current page
define('I18N_SHOW_PAGES', 2);        # show all pages whether they are in the menu or not
define('I18N_SHOW_LANGUAGE', 3);     # like I18N_SHOW_NORMAL, but only pages available in current language
define('I18N_SHOW_TITLES', 4);       # show all pages whether they are in the menu or not, but show titles

# filter navigation items (vetoed items are removed from navigation)
#  - parameters: $url, $parent, $tags (tags as array)
#  - must return true, if item should not be included in the navigation
define('I18N_FILTER_VETO_NAV_ITEM', 'navigation-veto');

# register plugin
register_plugin(
	$thisfile, 
	'I18N Navigation', 	
	'2.6', 		
	'Martin Vlcek',
	'http://mvlcek.bplaced.net', 
	'Multilevel navigation & breadcrumbs (I18N enabled)',
	'pages',
	'i18n_navigation'  
);

if (function_exists('i18n_load_texts')) {
  i18n_load_texts('i18n_navigation');
} else {  
  require_once(GSPLUGINPATH.'i18n_common/common.php');
  i18n_merge('i18n_navigation') || i18n_merge('i18n_navigation', 'en_US');
}

# activate filter
add_action('edit-extras', 'i18n_navigation_edit'); 
add_action('changedata-save', 'i18n_navigation_save'); 
add_action('page-delete', 'i18n_clear_cache'); // GetSimple 3.0+
add_action('pages-sidebar', 'createSideMenu', array($thisfile, i18n_r('i18n_navigation/EDIT_NAVIGATION')));
add_action('index-pretemplate', 'i18n_check_redirect');
add_action('header', 'i18n_navigation_admin_header');

# workaround for page-delete in GetSimple 2.03:
if (!function_exists('get_site_version') && basename($_SERVER['PHP_SELF']) == 'deletefile.php') {
  i18n_clear_cache();
}

function i18n_navigation_admin_header() {
  if (basename($_SERVER['PHP_SELF']) == 'edit.php') {
    global $SITEURL;
    echo '<link rel="stylesheet" href="'.$SITEURL.'plugins/i18n_navigation/css/jquery.autocomplete.css" type="text/css" charset="utf-8" />'."\n";
    echo '<script type="text/javascript" src="'.$SITEURL.'plugins/i18n_navigation/js/jquery.autocomplete.min.js"></script>'."\n";
  }
  echo '<script type="text/javascript">$(function() { $("#sb_menumanager").hide(); });</script>';
}

function i18n_clear_cache() {
  $cachefile = GSDATAOTHERPATH . I18N_CACHE_FILE;
  if (file_exists($cachefile)) unlink($cachefile);
}

function i18n_check_redirect() {
  if (function_exists('return_custom_field')) {
    if (function_exists('i18n_init')) i18n_init();
    $link = return_custom_field('link');
    if ($link) {
      header('Location: '.$link);
      exit(0);
    }
  }
}

function return_i18n_pages() {
  global $i18n_pages;
  if ($i18n_pages) return $i18n_pages;
  $cachefile = GSDATAOTHERPATH . I18N_CACHE_FILE;
  if (!I18N_USE_CACHE || !file_exists($cachefile)) {
    // read pages into associative array
    $i18n_pages = array();
		$dir_handle = @opendir(GSDATAPAGESPATH) or die("Unable to open pages directory");
		while ($filename = readdir($dir_handle)) {
      if (strrpos($filename,'.xml') === strlen($filename)-4 && !is_dir(GSDATAPAGESPATH . $filename)) {
  			$data = getXML(GSDATAPAGESPATH . $filename);
        if (strpos($filename,'_') !== false) {
          $pos = strpos($data->url,'_');
          $url = substr($data->url,0,$pos);
          $lang = substr($data->url,$pos+1);
          if (!isset($i18n_pages[$url])) {
            $i18n_pages[$url] = array('url' => $url);
          }
          $menu = ((string) $data->menu ? (string) $data->menu : (string) $data->title);
          $title = ((string) $data->title ? (string) $data->title : (string) $data->menu);
				  if ($menu) $i18n_pages[$url]['menu_'.$lang] = stripslashes($menu);
				  if ($title) $i18n_pages[$url]['title_'.$lang] = stripslashes($title);
          if (isset($data->link) && (string) $data->link) $i18n_pages[$url]['link_'.$lang] = (string) $data->link;
        } else {
          $url = (string) $data->url;
          if (!isset($i18n_pages[$url])) {
            $i18n_pages[$url] = array('url' => $url);
          }
				  $i18n_pages[$url]['menuStatus'] = (string) $data->menuStatus;
				  $i18n_pages[$url]['menuOrder'] = (int) $data->menuOrder;
				  $i18n_pages[$url]['menu'] = stripslashes($data->menu);
				  $i18n_pages[$url]['title'] = stripslashes($data->title);
          $i18n_pages[$url]['parent'] = (string) $data->parent;
          $i18n_pages[$url]['private'] = (string) $data->private;
          $i18n_pages[$url]['tags'] = (string) stripslashes($data->meta);
          if (isset($data->link) && (string) $data->link) $i18n_pages[$url]['link'] = (string) $data->link;
        }
      }
		}
    // sort pages
    $urlsToDelete = array();
    $sortedpages = array();
    foreach ($i18n_pages as $url => $page) {
      if (isset($page['parent']) && $page['private'] != 'Y') {
        $sortedpages[] = array('url' => $url, 'parent' => $page['parent'],
           'sort' => sprintf("%s%03s%s", $page['parent'], $page['menuOrder'], $url));
      } else {
        $urlsToDelete[] = $url;
      }
    }
    $sortedpages = subval_sort($sortedpages,'sort');
    if (count($urlsToDelete) > 0) foreach ($urlsToDelete as $url) unset($i18n_pages[$url]);
    // save cache file
    if (I18N_USE_CACHE) {
  		$data = @new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><pages></pages>');
      foreach ($sortedpages as $sortedpage) {
        $url = $sortedpage['url'];
        $page = $i18n_pages[$url];
        $pagedata = $data->addChild('page');
        foreach ($page as $key => $value) {
          $propdata = $pagedata->addChild($key);
          $propdata->addCData($value);
        }
      }
  		XMLsave($data, $cachefile);
    }
	} else {
    $sortedpages = array();
    $data = getXML($cachefile);
    foreach ($data->page as $pagedata) {
      $url = '' . $pagedata->url;
      $i18n_pages[$url] = array();
      foreach ($pagedata as $propdata) {
        $i18n_pages[$url][$propdata->getName()] = '' . $propdata;
      }
      $sortedpages[] = array('url' => $url, 'parent' => $i18n_pages[$url]['parent']);
    }
  }
  // fill children
  $i18n_pages[null] = array();
  foreach ($sortedpages as $sortedpage) {
    $parent = $sortedpage['parent'];
    if (isset($i18n_pages[$parent])) {
      if (!isset($i18n_pages[$parent]['children'])) $i18n_pages[$parent]['children'] = array();
      $i18n_pages[$parent]['children'][] = $sortedpage['url'];
    }
  }
  return $i18n_pages;
}

function return_i18n_page_structure($slug=null, $menuOnly=true, $slugToIgnore=null) {
  $slug = '' . $slug;
  $structure = array();
  i18n_page_structure($structure, $slug, $menuOnly, $slugToIgnore);
  return $structure;
}

function i18n_page_structure(&$structure, $slug, $menuOnly=true, $slugToIgnore=null) {
  $pages = return_i18n_pages();
  if (!isset($pages[$slug])) return;
  $level = (count($structure) > 0 ? $structure[count($structure)-1]['level'] + 1 : 0);
  if (isset($pages[$slug]['children'])) {
    foreach ($pages[$slug]['children'] as $childslug) {
      if ($childslug != $slugToIgnore && (!$menuOnly || $pages[$childslug]['menuStatus'] == 'Y')) {
        $structure[] = array(
          'level' => $level,
          'url' => $childslug,
          'title' => $pages[$childslug]['title'],
          'menuStatus' => $pages[$childslug]['menuStatus'],
          'menu' => $pages[$childslug]['menu']
        );
        i18n_page_structure($structure, $childslug, $menuOnly, $slugToIgnore);
      }
    }
  }
}

function return_i18n_menu_data($slug, $minlevel=0, $maxlevel=0, $show=I18N_SHOW_NORMAL) {
  $slug = '' . $slug;
  $pages = return_i18n_pages();
  $breadcrumbs = array();
  for ($url = $slug; $url && isset($pages[$url]); $url = $pages[$url]['parent']) {
    array_unshift($breadcrumbs, $url);
  }
  array_unshift($breadcrumbs, null);
  // find last page in breadcrumbs that is displayed in the menu
  for ($icu = 0; $icu+1 < count($breadcrumbs) && $pages[$breadcrumbs[$icu+1]]['menuStatus'] == 'Y'; $icu++);
  $currenturl = $breadcrumbs[$icu];
  // menus to display
  if ($minlevel < 0 || $maxlevel < $minlevel || $minlevel >= count($breadcrumbs)) {
    return null;
  } else {
    return i18n_menu($breadcrumbs, $currenturl, $breadcrumbs[$minlevel], $maxlevel-$minlevel+1, $show);
  }
}

function i18n_menu($breadcrumbs, $currenturl, $url, $levels, $show=I18N_SHOW_NORMAL) {
  global $language; // only set if I18N base plugin is available
  $pages = return_i18n_pages();
  if (!@$pages[$url] || $levels <= 0) return null;
  $deflang = function_exists('return_i18n_default_language') ? return_i18n_default_language() : null;
  $menu = array();
  if (isset($pages[$url]['children'])) {
    foreach ($pages[$url]['children'] as $childurl) {
      $showIt = $show == I18N_SHOW_PAGES || $show == I18N_SHOW_TITLES || $pages[$childurl]['menuStatus'] == 'Y';
      if ($showIt && $show == I18N_SHOW_LANGUAGE) {
        $fulltitlekey = 'title' . (!@$language || $language == $deflang ? '' : '_' . $language);
        if (!isset($pages[$childurl][$fulltitlekey])) $showIt = false;
      }
      if ($showIt) {
        global $filters;
        $params = array($childurl, $pages[$childurl]['parent'], 
                        preg_split('/\s*,\s*/', html_entity_decode(stripslashes(trim(@$pages[$childurl]['tags'])), ENT_QUOTES, 'UTF-8')));
        foreach ($filters as $filter)  {
          if ($filter['filter'] == I18N_FILTER_VETO_NAV_ITEM) {
            if (call_user_func_array($filter['function'], $params)) {
              $showIt = false; 
              break;
            }
          }
        }
      }
      if ($showIt) {
        $showChildren = $show == I18N_SHOW_MENU || $show == I18N_SHOW_PAGES || $show == I18N_SHOW_TITLES || $show === true || in_array($childurl,$breadcrumbs);
        $children = $showChildren ? i18n_menu($breadcrumbs, $currenturl, $childurl, $levels-1, $show) : null;
        $menu[] = array(
          'url' => $childurl, 
          'parent' => $pages[$childurl]['parent'],
          'menu' => i18n_prop($childurl,'menu',$deflang), 
          'title' => i18n_prop($childurl,'title',$deflang),
          'link' => i18n_prop($childurl,'link',$deflang),
          'currentpath' => in_array($childurl, $breadcrumbs),
          'current' => ($childurl == $currenturl),
          'children' => $children,
          'haschildren' => $showChildren ? count($children) > 0 : i18n_menu_has_children($childurl, $show)
        );
      }
    }
  }
  return count($menu) > 0 ? $menu : null;
}

function i18n_menu_has_children($url, $show=I18N_SHOW_NORMAL) {
  global $language; // only set if I18N base plugin is available
  $pages = return_i18n_pages();
  if (!@$pages[$url] || !isset($pages[$url]['children'])) return false;
  $deflang = function_exists('return_i18n_default_language') ? return_i18n_default_language() : null;
  foreach ($pages[$url]['children'] as $childurl) {
    if ($show == I18N_SHOW_LANGUAGE) {
      $fulltitlekey = 'title' . (!@$language || $language == $deflang ? '' : '_' . $language);
      if (isset($pages[$childurl][$fulltitlekey])) return true;
    } else {
      return true;
    }
  }
  return false;
}

function i18n_prop($url, $key, $deflang) {
  $pages = return_i18n_pages();
  if ($deflang !== null) {
    $languages = return_i18n_languages();
    foreach ($languages as $language) {
      $fullkey = $key . ($language == $deflang ? '' : '_' . $language);
      if (isset($pages[$url][$fullkey])) return $pages[$url][$fullkey];
    }
  } else {
    if (isset($pages[$url][$key])) return $pages[$url][$key];
  }
  return null;
}

function i18n_menu_html(&$menu, $showTitles=false) {
  $html = '';
  foreach ($menu as &$item) {
    $href = @$item['link'] ? $item['link'] : (function_exists('find_i18n_url') ? find_i18n_url($item['url'],$item['parent']) : find_url($item['url'],$item['parent']));
    $urlclass = (preg_match('/^[a-z]/i',$item['url']) ? '' : 'x') . $item['url'];
    $parentclass = !$item['parent'] ? '' : (preg_match('/^[a-z]/i',$item['parent']) ? ' ' : ' x') . $item['parent'];
    $classes = $urlclass . $parentclass . 
                ($item['current'] ? ' current' : ($item['currentpath'] ? ' currentpath' : '')) . 
                (isset($item['children']) && count($item['children']) > 0 ? ' open' : ($item['haschildren'] ? ' closed' : ''));
    $text = $item['menu'] ? $item['menu'] : $item['title'];
    $title = $item['title'] ? $item['title'] : $item['menu'];
    if ($showTitles) {
      $html .= '<li class="' . $classes . '"><a href="' . $href . '" >' . $title . '</a>';
    } else {
      $html .= '<li class="' . $classes . '"><a href="' . $href . '" title="' . htmlspecialchars(html_entity_decode($title, ENT_QUOTES, 'UTF-8')) . '">' . $text . '</a>';
    }
    if (isset($item['children']) && count($item['children']) > 0) {
      $html .= '<ul>' . i18n_menu_html($item['children'], $showTitles) . '</ul>';
    }
    $html .= '</li>' . "\n";
  }
  return $html;
}

function get_i18n_navigation($slug, $minlevel=0, $maxlevel=0, $show=I18N_SHOW_NORMAL) {
  $slug = '' . $slug;
  $menu = return_i18n_menu_data($slug, $minlevel, $maxlevel, $show);
  if (isset($menu) && count($menu) > 0) {
    $html = i18n_menu_html($menu, $show == I18N_SHOW_TITLES);
		echo exec_filter('menuitems',$html);
  }
}

function return_i18n_breadcrumbs($slug) {
  $slug = '' . $slug;
  $pages = return_i18n_pages();
  $breadcrumbs = array();
  $deflang = function_exists('return_i18n_default_language') ? return_i18n_default_language() : null;
  for ($url = $slug; $url && isset($pages[$url]); $url = $pages[$url]['parent']) {
    array_unshift($breadcrumbs, array(
          'url' => $url, 
          'parent' => $pages[$url]['parent'],
          'menu' => i18n_prop($url,'menu',$deflang), 
          'title' => i18n_prop($url,'title',$deflang),
    ));
  }
  return $breadcrumbs;
}

function get_i18n_breadcrumbs($slug) {
  $slug = '' . $slug;
  $breadcrumbs = return_i18n_breadcrumbs($slug);
  foreach ($breadcrumbs as &$item) {
    $text = $item['menu'] ? $item['menu'] : $item['title'];
    $title = $item['title'] ? $item['title'] : $item['menu'];
    $url = function_exists('find_i18n_url') ? find_i18n_url($item['url'],$item['parent']) : find_url($item['url'],$item['parent']);
    echo ' &raquo; <span class="breadcrumb"><a href="' . $url . '" title="' . 
              strip_quotes($title) . '">' . $text . '</a></span>';
  }
}

function i18n_navigation() {
  include(GSPLUGINPATH.'i18n_navigation/structure.php');
}

function i18n_navigation_edit() {
  include(GSPLUGINPATH.'i18n_navigation/editextras.php');
}

function i18n_navigation_save() {
  include(GSPLUGINPATH.'i18n_navigation/save.php');
  i18n_clear_cache();
}


