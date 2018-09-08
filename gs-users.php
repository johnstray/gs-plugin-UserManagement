<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple User Management
 * Description: Enables advanced user management functions for GetSimple CMS with support for custom permissions from plugins
 * Version: 0.0.1
 * Author: John Stray
 * Author URI: https://www.johnstray.id.au/
 */

# Define some important stuff
define('USRMGMT', basename(__FILE__, ".php"));
define('USRMEXTENDID','810');
define('USRMVERSION','0.0.1');
define('USRMPLUGINFOLDER', GSPLUGINPATH.USRMGMT.DIRECTORY_SEPARATOR);
define('USRMINCFOLDER', USRMPLUGINFOLDER.'include'.DIRECTORY_SEPARATOR);
define('USRMGROUPPATH', GSUSERSPATH.'groups'.DIRECTORY_SEPARATOR);
define('USRMAVATARPATH', GSDATAUPLOADPATH.'avatars'.DIRECTORY_SEPARATOR);
define('GSGROUPSPATH', GSUSERSPATH.'groups'.DIRECTORY_SEPARATOR);
define('GSBACKGROUPSPATH', GSBACKUSERSPATH.'groups'.DIRECTORY_SEPARATOR);
define('USRMDEBUGPREFIX', 'USRM >: ');

# Require the Class and Functions files
require(USRMPLUGINFOLDER.'users.class.php');
require(USRMPLUGINFOLDER.'frontend_functions.php');
require(USRMPLUGINFOLDER.'admin_functions.php');

# Setup languages and language settings.
i18n_merge(USRMGMT) || i18n_merge(USRMGMT, "en_US");
define('USRMLANGUAGE',i18n_r(USRMGMT.'/LANGUAGE_CODE'));

# Register plugin
register_plugin(
	USRMGMT, // ID of plugin, should be filename minus php
	i18n_r(USRMGMT.'/PLUGIN_TITLE'), 	
	USRMVERSION,
	'John Stray',
	'https://www.johnstray.id.au/get-simple/plug-ins/gs-blog-3/', 
	i18n_r(USRMGMT.'/PLUGIN_DESCRIPTION'),
	'users',
	'usrm_main'  
);

# Execute Actions
add_action('common', 'reorderUserManagement');           // Reorder the User Management Plugin
add_action('common', 'usrmCaptureRedirect');             // 
add_action('settings-sidebar','createSideMenu',array(USRMGMT, i18n_r(USRMGMT.'/USER_MANAGEMENT')));
add_filter('checkPermission', 'exec_permission');        // Function for other plugins to check permissions
add_action('index-login', 'injectLoginMessage');         // Show the defined message on the login page
add_action('successful-login-end', 'checkAdminsOnly'); // Check if only allowing Administrators
add_action('index-pretemplate','usrm_show_user_profiles');
    
add_action('nav-tab', 'createSupportTab');
add_action('users-sidebar', 'createSideMenu', array( USRMGMT, '<i class="fa fa-fw fa-user-o"></i> ' . i18n_r(USRMGMT.'/USER_MANAGEMENT'), 'view-users' ));
add_action('users-sidebar', 'createSideMenu', array( USRMGMT, '<i class="fa fa-fw fa-object-group"></i> ' . i18n_r(USRMGMT.'/GROUPS_MANAGEMENT'), 'view-groups' ));
add_action('users-sidebar', 'createSideMenu', array( USRMGMT, '<i class="fa fa-fw fa-cogs"></i> ' . i18n_r(USRMGMT.'/CONFIGURATION_SETTINGS'), 'settings' ));
add_action('users-sidebar', 'createSideMenu', array( USRMGMT, '<i class="fa fa-fw fa-question-circle"></i> ' . i18n_r(USRMGMT.'/HELP_INFORMATION'), 'help' ));

# Register Styles
register_style(USRMGMT.'_css', $SITEURL.'plugins/'.USRMGMT.'/resources/admin_styles.css', USRMVERSION, 'screen');

# Register Scripts
register_script('masonry', $SITEURL.'/plugins/'.USRMGMT.'/resources/masonry.js', '4.1.1', TRUE);
register_script('checkboxes', $SITEURL.'/plugins/'.USRMGMT.'/resources/checkboxes.js', '1.0.0', TRUE);
register_script('image_upload', $SITEURL.'/plugins/'.USRMGMT.'/resources/image_upload.js', '1.0.0', TRUE);

# Queue Scripts/Styles
if(!is_frontend() && isset($_GET['id']) && $_GET['id'] == USRMGMT) { // Only queue the scripts when on user management pages
  queue_style(USRMGMT.'_css', GSBACK);
  queue_script('masonry', GSBACK);
  queue_script('checkboxes', GSBACK);
  queue_script('image_upload', GSBACK);
}

# Public Functions
function add_permissions($plugin=null, $permissions=array()) {
  $GSUsers = new GSUsers();
  $GSUsers->addPermissionGroup($plugin, $permissions);
}
/* array('permission_id', ob_get_clean()); */
function exec_permission($args) {
  $GSUsers = new GSUsers();
  $permitted = $GSUsers->getPermission($args[0]);
  if($permitted) {echo $args[1];}
  else {include(USRMINCFOLDER.'denied.php');}
}

function get_user_permission($permission_id, $user=null) {
  $GSUsers = new GSUsers();
  $user = (is_null($user) ? get_cookie('GS_ADMIN_USERNAME') : $user);
  $result = $GSUsers->getPermission($user, $permission_id);
  return $result;
}

function vdump($mixed){
  
  ob_start();
  var_dump($mixed);
  $dump = ob_get_clean();
  debugLog($dump);
  
}
