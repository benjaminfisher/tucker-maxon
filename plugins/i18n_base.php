<?php
/*
Plugin Name: I18N Base
Description: Internationalization based on slug/URL names (e.g. index, index_de, index_fr)
Version: 2.6
Author: Martin Vlcek
Author URI: http://mvlcek.bplaced.net

Public functions:
  return_i18n_default_language()
      returns the default language - the language of pages without language suffix
  return_i18n_languages()
      returns an array of user requested languages with the best first, e.g. ( 'de', 'fr', 'en' )
  return_i18n_available_languages($slug=null)
      returns the available languages for the site or page (if slug is not empty)
  return_i18n_page_data($slug)
      returns the xml data for the best fitting language version of the $slug
  return_i18n_lang_url($language=null)
      returns the URL to the current page in the given $language (if null, the default language is used)
      (you should use htmlspecialchars when outputting in a href to convert & to &amp;)
  return_i18n_setlang_url($language)
      returns the URL to the current page which also sets the preferred $language. If the current URL does not have a 
      parameter lang then this causes the page to be displayed in the given $language (if it exists).
      (you should use htmlspecialchars when outputting in a href to convert & to &amp;)
  get_i18n_page_url($echo=false)
      like get_page_url, but I18N enabled - ATTENTION: $echo=false WILL echo!!!
  find_i18n_url($slug,$parent,$language,$type='full')
      returns the URL to the page identified by $slug/$parent in the given $language (see also core function find_url)
      (you should use htmlspecialchars when outputting in a href to convert & to &amp;)
  return_i18n_component($slug)
      returns the component content (unprocessed)

Display functions:
  get_i18n_header()
      like get_header, but tags beginning with _ are ignored and the language is appended to the canonical URL
  get_i18n_content($slug)
      outputs the best fitting language content of the $slug. Returns true, if content found.
  get_i18n_component($id, $param1, ...)
      outputs the (localized) component. Returns true, if component found.
      Optionally parameters can be passed. They are available in the component as global array $args.
  get_i18n_link($slug)
      outputs a link to the given page in the best language

Functions to call for other plugins:
  i18n_init()
      loads the correct language version for the current page

Ignore user language:
      if you want to ignore the language(s) the user has set in his browser, add the following to gsconfig.php:
        define('I18N_IGNORE_USER_LANGUAGE',true);
                  
Fancy URLs:
      You can include a placeholder %language% in the fancy URL - then the language will be always included
      in the URL, e.g. %language%/%parent%/%slug%/ --> en/products/notebook1/
      You can also define a constant I18N_SEPARATOR in gsconfig.php, e.g. ':', which will result in URLs like
      products/notebook1:en/. The language will only be shown, if specifically requested.
*/

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");
$i18n_initialized = false;
$i18n_languages = null;
$i18n_settings = null;

define('I18N_DEFAULT_LANGUAGE', 'en');
define('I18N_SETTINGS_FILE', 'i18n_settings.xml');
define('I18N_LANGUAGE_PARAM', 'lang');            # language parameter in URL, e.g. "...?lang=de"
define('I18N_SET_LANGUAGE_PARAM', 'setlang');     # parameter to set current language via GET/POST, e.g. "...?setlang=de"
define('I18N_LANGUAGE_KEY', 'language');          # session key for language, if language was set 

// properties
define('I18N_PROP_DEFAULT_LANGUAGE', 'default_language');
define('I18N_PROP_URLS_TO_IGNORE', 'urls-to-ignore');
define('I18N_PROP_PAGES_VIEW', 'pages-view');

# register plugin
register_plugin(
	$thisfile, 
	'I18N Base', 	
	'2.6', 		
	'Martin Vlcek',
	'http://mvlcek.bplaced.net', 
	'Internationalize content based on slug/URL names',
	'pages',
	'i18n_pages'  
);

require_once(GSPLUGINPATH.'i18n_common/common.php');
i18n_load_texts('i18n_base');

# activate filter
add_action('index-pretemplate', 'i18n_init');
add_action('edit-extras', 'i18n_base_edit'); 
add_action('pages-sidebar', 'createSideMenu', array($thisfile, i18n_r('i18n_base/PAGES')));
add_action('admin-pre-header', 'i18n_base_admin_pre_header'); // 3.1+ only
add_action('header', 'i18n_base_admin_header'); // 3.0+

# ===== BACKEND HOOKS =====

function i18n_base_admin_pre_header() {
  require_once(GSPLUGINPATH.'i18n_base/backend.class.php');
  I18nBackend::processPreHeader();
}

function i18n_base_admin_header() {
  require_once(GSPLUGINPATH.'i18n_base/backend.class.php');
  I18nBackend::processHeader();
}

function i18n_base_edit() {
  include(GSPLUGINPATH.'i18n_base/editextras.php');
}


# ===== FRONTEND HOOKS =====

function i18n_init() {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  I18nFrontend::init();
}


# ===== FRONTEND FUNCTIONS =====

// load texts based on frontend/admin languages
function i18n_load_texts($plugin) {
  global $LANG, $language;
  if (basename($_SERVER['PHP_SELF']) == 'index.php') {
    // frontend language with I18N plugin is always two characters long
    i18n_merge($plugin, $language) ||
    i18n_merge($plugin, return_i18n_default_language()) || 
    i18n_merge($plugin, 'en');
  } else {
    i18n_merge($plugin, $LANG) ||
    (strlen($LANG) > 2 && i18n_merge($plugin, substr($LANG,0,2))) ||
    i18n_merge($plugin, 'en_US') ||
    i18n_merge($plugin, 'en');
  }
}

function return_i18n_default_language() {
  require_once(GSPLUGINPATH.'i18n_base/basic.class.php');
  return I18nBasic::getProperty(I18N_PROP_DEFAULT_LANGUAGE, I18N_DEFAULT_LANGUAGE);
}

function return_i18n_languages() {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::getLanguages();
}

function return_i18n_available_languages($slug=null) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::getAvailableLanguages($slug);
}

function return_i18n_page_data($slug) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::getPageData($slug);
}

function get_i18n_content($slug, $force=false) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::outputContent($slug, $force);
}

function return_i18n_component($id) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::getComponent($id);  
}

function get_i18n_component($id, $param1=null, $param2=null) {
  global $args;
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  if (func_num_args() > 1) { 
    $a = func_get_args(); array_shift($a); 
  } else if (isset($args)) {
    $a = $args;
  } else {
    $a = array();
  }
  return I18nFrontend::outputComponent($id, $a);
}

function get_i18n_page_url($echo=false) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  if (!$echo) { # to be compatible with get_page_url!!!
    echo I18nFrontend::getPageURL();
  } else {
    return I18nFrontend::getPageURL();
  }
}

function find_i18n_url($slug, $slugparent, $language=null, $type='full') {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::getURL($slug, $slugparent, $language, $type);
}

function return_i18n_lang_url($language=null) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::getLangURL($language);
}

function return_i18n_setlang_url($language) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::getSetLangURL($language);
}

function get_i18n_link($slug) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  return I18nFrontend::outputLinkTo($slug);  
}

function get_i18n_header($full=true) {
  require_once(GSPLUGINPATH.'i18n_base/frontend.class.php');
  I18nFrontend::outputHeader($full);
}


# ===== BACKEND PAGES =====

function i18n_pages() {
  include(GSPLUGINPATH.'i18n_base/pages.php');
}




