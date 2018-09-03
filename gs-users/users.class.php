<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple User Management
 * Description: Enables advanced user management functions for GetSimple CMS
 * File Action: The core class
 */

class GSUsers {
  
  public function __construct($init=false) {
    
    # Create the global permissions_array with core permissions.
    GLOBAL $permissions_array;
    $permissions_array = array();
    
    # Permissions array for the GetSimple CMS Core
    $core_permissions = array(
      'pages' => array(
        'name' => i18n_r(USRMGMT.'/PAGES_NAME'),
        'desc' => i18n_r(USRMGMT.'/PAGES_DESC'),
        'child_perms' => array(
          'pages-view' => i18n_r(USRMGMT.'/PAGES_VIEW'),
          'pages-editor' => i18n_r(USRMGMT.'/PAGES_EDITOR'),
          'pages-delete' => i18n_r(USRMGMT.'/PAGES_DELETE'),
          'pages-menumanager' => i18n_r(USRMGMT.'/PAGES_MENUMANAGER')
        )
      ),
      'upload' => array(
        'name' => i18n_r(USRMGMT.'/UPLOAD_NAME'),
        'desc' => i18n_r(USRMGMT.'/UPLOAD_DESC'),
        'child_perms' => array(
          'upload-list' => i18n_r(USRMGMT.'/UPLOAD_LIST'),
          'upload-upload' => i18n_r(USRMGMT.'/UPLOAD_UPLOAD'),
          'upload-delete' => i18n_r(USRMGMT.'/UPLOAD_DELETE'),
          'upload-thumbnail' => i18n_r(USRMGMT.'/UPLOAD_THUMBNAIL')
        )
      ),
      'theme' => array(
        'name' => i18n_r(USRMGMT.'/THEME_NAME'),
        'desc' => i18n_r(USRMGMT.'/THEME_DESC'),
        'child_perms' => array(
          'theme-change' => i18n_r(USRMGMT.'/THEME_CHANGE'),
          'theme-modify' => i18n_r(USRMGMT.'/THEME_MODIFY'),
          'theme-components' => i18n_r(USRMGMT.'/THEME_COMPONENTS'),
          'theme-sitemap' => 'View Sitemap'
        )
      ),
      'backups' => array(
        'name' => i18n_r(USRMGMT.'/BACKUPS_NAME'),
        'desc' => i18n_r(USRMGMT.'/BACKUPS_DESC'),
        'child_perms' => array(
          'backups-list' => 'List Backups',
          'backups-view' => 'View Backup',
          'backups-delete' => 'Delete Backup',
          'backups-restore' => 'Restore Backup',
          'backups-archive' => 'List Archives',
          'backups-archivecreate' => 'Create Archive',
          'backups-archivedownload' => 'Download Archive',
          'backups-archivedelete' => 'Delete Archive'
        )
      ),
      'plugins' => array(
        'name' => i18n_r(USRMGMT.'/PLUGINS_NAME'),
        'desc' => i18n_r(USRMGMT.'/PLUGINS_DESC'),
        'child_perms' => array(
          'plugins-view' => 'View Plugins',
          'plugins-toggle' => 'Toggle State'
        )
      ),
      'support' => array(
        'name' => 'Support',
        'desc' => '',
        'child_perms' => array(
          'support-gettingstarted' => 'Getting Started',
          'support-healthcheck' => 'Health Check',
          'support-viewlog' => 'View Logs',
          'support-clearlog' => 'Clear Logs'
        )
      ),
      'settings' => array(
        'name' => i18n_r(USRMGMT.'/SETTINGS_NAME'),
        'desc' => i18n_r(USRMGMT.'/SETTINGS_DESC'),
      ),
      'users' => array(
        'name' => i18n_r(USRMGMT.'/USERS_NAME'),
        'desc' => i18n_r(USRMGMT.'/USERS_DESC'),
        'child_perms' => array(
          'users-view' => i18n_r(USRMGMT.'/USERS_VIEW'),
          'users-edit' => i18n_r(USRMGMT.'/USERS_EDIT'),
          'users-delete' => i18n_r(USRMGMT.'/USERS_DELETE'),
          'users-viewgroup' => i18n_r(USRMGMT.'/USERS_VIEWGROUP'),
          'users-editgroup' => i18n_r(USRMGMT.'/USERS_EDITGROUP'),
          'users-deletegroup' => i18n_r(USRMGMT.'/USERS_DELETEGROUP'),
          'users-settings' => i18n_r(USRMGMT.'/USERS_SETTINGS')
        )
      )
    );
    
    # If GS API enabled, split settings into separate permissions
    if(getDef('GSEXTAPI',true)) {
      $core_permissions['settings']['child_perms'] = array(
        'settings-general' => 'General Settings',
        'settings-apiconfig' => 'API Configuration'
      );
    }
    
    
    $permissions_array['getsimple-core'] = $core_permissions;
    
    # Create 'avatars' folder in data/uploads
    if(!file_exists(USRMAVATARPATH)) {
      if(mkdir(USRMAVATARPATH,0777)) {
        if(!file_put_contents(USRMAVATARPATH.'.htaccess',"Allow from All\n")) {
          debugLog(USRMDEBUGPREFIX.'Could not create HTAccess Allow file in Avatars directory!');
        }
      } else {debugLog(USRMDEBUGPREFIX.'Could not create Avatars folder!');}
    }
    
    # Create 'groups' folder in data/users
    if(!file_exists(USRMGROUPPATH)) {
      if(mkdir(USRMGROUPPATH,0777)){
        if(!file_put_contents(USRMGROUPPATH.'.htaccess',"Deny from All\n")){
          debugLog(USRMDEBUGPREFIX.'Could not create HTAccess Deny file in Groups folder!');
        }
      } else {debugLog(USRMDEBUGPREFIX.'Could not create group path.');}
    }
    
    # Create 'groups' folder in backups/users
    if(!file_exists(GSBACKGROUPSPATH)) {
      if(mkdir(GSBACKGROUPSPATH,0777)){
        if(!file_put_contents(GSBACKGROUPSPATH.'.htaccess',"Deny from All\n")){
          debugLog(USRMDEBUGPREFIX.'Could not create HTAccess Deny file in Groups Backup folder!');
        }
      } else {debugLog(USRMDEBUGPREFIX.'Could not create Groups Backup path.');}
    }
    
    # Create 'Administrators' Group
    # Create 'Standard Users' Group
    
    # Modify all existing users:
      # If Current User, add to administrators group
      # else add to standard users group
    
  }
  
  public function addPermissionGroup($plugin_id=null, $permissions=array()) {
    GLOBAL $permissions_array;
    
    if(!is_null($plugin_id)) { # Append the new permissions if specified.
      $permissions_array[$plugin_id] = $permissions;
    }
    
  }
  
  public function getPermission($permission_id) {
    $current_user = $this->getCurrentUser();
    $user_file = getXML(GSUSERSPATH.$current_user.'.xml');
    $user_group = $user_file->USERGROUP;
    $granted = false; // Default catchall permission
    $group_perms = $this->getGroupPermissions($user_group);
    
    if(array_key_exists($permission_id, $group_perms)) {
      $granted = (!empty($group_perms[$permission_id]) ? $group_perms[$permission_id] : $granted);
    } return $granted;
  }
  
  public function saveUser() {
    
    # First, check for CSRF...
    if (!defined('GSNOCSRF') || (GSNOCSRF == FALSE) ) {
      $nonce = $_POST['nonce'];
      if(!check_nonce($nonce, "users_saveuser")) {
        die("CSRF detected!");	
      }
    }
    
    $user_array = $_POST;
    $all_users = $this->getAllUsers(); $error = false;
    if(in_array($user_array['username'], $all_users)) {$existing_user = $this->getUserProfile($user_array['username']) ?: array();}
    
    $USR = (isset($user_array['username']) ? strtolower($user_array['username']) : (isset($existing_user['USR']) ? $existing_user['USR'] : ''));
    $NAME = (isset($user_array['name']) ? $user_array['name'] : (isset($existing_user['NAME']) ? $existing_user['NAME'] : ''));
    $PASSWD = (isset($user_array['password']) ? passhash($user_array['password']) : (isset($existing_user['PWD']) ? $existing_user['PWD'] : ''));
    $EMAIL = (isset($user_array['email']) ? var_out($user_array['email'],'email') : (isset($existing_user['EMAIL']) ? $existing_user['EMAIL'] : ''));
    $HTMLEDITOR = (isset($existing_user['htmleditor']) ? $existing_user['htmleditor'] : 'TRUE');
    $TIMEZONE = (isset($user_array['timezone']) ? var_out($user_array['timezone']) : (isset($existing_user['TIMEZONE']) ? $existing_user['TIMEZONE'] : ''));
    $LANG = (isset($user_array['lang']) ? var_out($user_array['lang']) : (isset($existing_user['LANG']) ? $existing_user['LANG'] : ''));
    $USERGROUP = (isset($user_array['usergroup']) ? $user_array['usergroup'] : (isset($existing_user['USERGROUP']) ? $existing_user['USERGROUP'] : ''));
    $LANDINGPAGE = (isset($user_array['landingpage']) ? $user_array['landingpage'] : (isset($existing_user['LANDINGPAGE']) ? $existing_user['LANDINGPAGE'] : ''));
    $AVATAR = (isset($user_array['avatar-file']) ? $user_array['avatar-file'] : (isset($existing_user['AVATAR']) ? $existing_user['AVATAR'] : ''));
    $PROFILE = (isset($user_array['profile-content']) ? safe_slash_html($user_array['profile-content']) : (isset($existing_user['PROFILE']) ? $existing_user['PROFILE'] : ''));
      
    # Create backup for undo...
    if(file_exists(GSUSERSPATH.$USR.'.xml')) {
      if(!createBak($USR.'.xml', GSUSERSPATH, GSBACKUSERSPATH)) {
        deubgLog(USRMDEBUGPREFIX.'Failed to create a backup of the userfile before overwriting [ '.$USR.'.xml ]');
      }
    }
    
    $xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><item></item>');		
		$xml->addChild('USR', $USR);
		$xml->addChild('NAME', $NAME);
		$xml->addChild('PWD', $PASSWD);
		$xml->addChild('EMAIL', $EMAIL);
		$xml->addChild('HTMLEDITOR', $HTMLEDITOR);
		$xml->addChild('TIMEZONE', $TIMEZONE);
		$xml->addChild('LANG', $LANG);
    $xml->addChild('USERGROUP', $USERGROUP);
    $xml->addChild('LANDINGPAGE', $LANDINGPAGE);
    $xml->addChild('AVATAR', $AVATAR);
    $xml->addChild('PROFILE');
    $xml->PROFILE->addCData($PROFILE);
		
		exec_action('settings-user');
		
		if(!XMLsave($xml, GSUSERSPATH.$USR.'.xml')) {
			debugLog(); return false;
		} else {return true;}
    
  }
  
  public function deleteUser($user_id) {
    if(isset($_GET['user'])) {$_GET['user'];} else {return false;}
    if(file_exists(GSUSERSPATH.$user_id.'.xml')) {
      if(!createBak($user_id.'.xml', GSUSERSPATH, GSBACKUSERSPATH)) {
        debugLog();
      }
      if (unlink(GSUSERSPATH.$user_id.'.xml')) {
        return true;
      } else {debugLog();return false;}
    } else {debugLog();return false;}
  }
  
  public function getUserProfile($username) {
    if(file_exists(GSUSERSPATH.$username.'.xml')) {
      if($xml = getXML(GSUSERSPATH.$username.'.xml')) {
        $user_profile = array();
        foreach($xml as $key => $value){
          $key = (string) $key;
          $user_profile[$key] = (string) $value;
        } return $user_profile;
      } else {debugLog(USRMDEBUGPREFIX.'Could not get the XML data from file [ '.$username.'.xml ]');return false;}
    } else {debugLog(USRMDEBUGPREFIX.'Could not return profile of user. User file not found [ '.$username.'.xml ]');return false;}
  }
  
  public function getCurrentUser() {
    if($all_users = $this->getAllUsers()) {
      $cookie_user = get_cookie('GS_ADMIN_USERNAME');
      if(in_array($cookie_user, $all_users)) {
        return $cookie_user;
      } else {return false;}
    } else {return false;}
  }
  
  public function getAllUsers() {
    $users_dir = GSUSERSPATH.'*.xml';
    $all_users = array();
    $user_files = glob($users_dir);
    if($user_files !== false) {
      foreach($user_files as $user_file) {
        if($xml = getXML($user_file)) {
          $all_users[] = (string) $xml->USR;
        } else {debugLog(USRMDEBUGPREFIX.'Could not get the XML data from file [ '.$user_file.' ]');}
      } return $all_users;
    } else {debugLog(USRMDEBUGPREFIX.'Failed to get a list of files from the Users path');return false;}
  }
  
  public function saveGroup($group_array) {
    
    GLOBAL $permissions_array;
    
    $existing_group = (isset($group_array['groupname']) ? ($this->getGroupDetails($this->generateID($group_array['groupname'])) ?: array()) : array());
    $GROUPSLUG = (isset($group_array['groupname']) && !empty($group_array['groupname']) ? $this->generateID($group_array['groupname']) : (isset($existing_group['GROUPSLUG']) ? $existing_group['GROUPSLUG'] : ''));
    $GROUPNAME = (isset($group_array['groupname']) ? $group_array['groupname'] : (isset($existing_group['GROUPNAME']) ? $existing_group['GROUPNAME'] : ''));
    $LANDINGPAGE = (isset($group_array['landingpage']) ? $this->generateID($group_array['landingpage']) : (isset($existing_group['LANDINGPAGE']) ? $existing_group['LANDINGPAGE'] : ''));
    
    # Filter out the permission values from the $_POST array.
    $posted_permissions = array();
    foreach($group_array as $key => $value) {
      if(strpos($key,'perm') === 0){
        $key = str_replace('perm-','',$key);
        $posted_permissions[$key] = $value;
      }
    }
    
    # Filter out permission keys from the $permissions_array
    $permission_keys = array();
    foreach($permissions_array as $plugin => $plugin_permissions) {
      foreach($plugin_permissions as $perm_key => $perm_detail) {
        $plugin = ($plugin != 'getsimple-core') ? $plugin : '';
        $permission_keys[] = (!empty($plugin)) ? $plugin.'-'.$perm_key : $perm_key;
        if(isset($perm_detail['child_perms'])) {
          foreach($perm_detail['child_perms'] as $ckey => $cval) {
            $permission_keys[] = (!empty($plugin)) ? $plugin.'-'.$ckey : $ckey;
          }
        }
      }
    }
    
    if (count($permission_keys) == 0) {
      debugLog(USRMDEBUGPREFIX.'Permission keys array is empty! Something has gone wrong...');
      return false;
    }
    
    # Combine the keys from the $permissions_array to the values from $posted_permissions
    $sorted_permissions = array();
    foreach($permission_keys as $key){
      if(array_key_exists($key,$posted_permissions)) {
        $sorted_permissions[$key] = $posted_permissions[$key];
      } else {
        $sorted_permissions[$key] = false;
      }
    }
    
    # Create a backup if this is an existing user
    if(file_exists(GSGROUPSPATH.$GROUPNAME.'.xml')) {
      if(!createBak($GROUPNAME.'.xml', GSGROUPSPATH, GSBACKGROUPSPATH)) {
        debugLog(USRMDEBUGPREFIX.'Failed to create a backup of the groupfile before overwriting [ '.$GROUPNAME.'.xml ]');
      }
    }
    
    # Compile the XML data
    $xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8" ?><group></group>');		
		$xml->addChild('GROUPNAME', $GROUPNAME);
      $xml->addChild('GROUPSLUG', $GROUPSLUG);
    $xml->addChild('LANDINGPAGE', $LANDINGPAGE);
    $xml->addChild('PERMISSIONS');
    foreach ($sorted_permissions as $key => $value) {
      $value = ($value == true ? 'TRUE' : (strtolower($GROUPNAME) == 'administrators' ? 'TRUE' : 'FALSE'));
      $xml->PERMISSIONS->addChild($key, $value);
    }
		
    # Save the file
		if(!XMLsave($xml, GSGROUPSPATH.$GROUPSLUG.'.xml')) {
      debugLog(USRMDEBUGPREFIX.'Unable to save the group data XML file [ '.$GROUPNAME.'.xml ]');
    } else {return true;}
    
  }
  
  public function deleteGroup($group_id) {
      if ( $group_id == "administrators" ) {
          if(file_exists(GSGROUPSPATH.$group_id.'.xml')) {
              if(!createBak($group_id.'.xml', GSGROUPSPATH, GSBACKGROUPSPATH)) {
                  deubgLog(USRMDEBUGPREFIX.'Failed to create a backup of the groupfile before deleting [ '.$group_id.'.xml ]');
              }
              if (unlink(GSGROUPSPATH.$group_id.'.xml')) {
                  return true;
              } else {deubugLog(USRMDEBUGPREFIX.'Could not delete group. Unlink() failed [ '.$group_id.'.xml ]');return false;}
          } else {deubugLog(USRMDEBUGPREFIX.'Could not delete group. Group file not found [ '.$group_id.'.xml ]');return false;}
      } else {debugLog(USRMDEBUGPREFIX.'You cannot delete the default Administrators group. This group is required by the system.');return false;}
  }
  
  public function getAllGroups() {
    $groups_dir = GSGROUPSPATH.'*.xml';
    $all_groups = array();
    $group_files = glob($groups_dir);
    if($group_files !== false) {
      foreach($group_files as $group_file) {
        if($xml = getXML($group_file)) {
            $groupslug = (string) $xml->GROUPSLUG;
          $all_groups[$groupslug] = (string) $xml->GROUPNAME;
        } else {debugLog(USRMDEBUGPREFIX.'Could not get the XML data from file [ '.$group_file.' ]');}
      } return $all_groups;
    } else {debugLog(USRMDEBUGPREFIX.'Failed to get a list of files from the Groups path');return false;}
  }
  
  public function getGroupDetails($group_id) {
    if(file_exists(GSGROUPSPATH.$group_id.'.xml')) {
      if($xml = getXML(GSGROUPSPATH.$group_id.'.xml')) {
        $group_details = array();
        foreach($xml as $key => $value){
          $key = (string) $key;
          $group_details[$key] = $value;
        } return $group_details;
      } else {debugLog(USRMDEBUGPREFIX.'Could not get the XML data from file [ '.$group_id.'.xml ]');return false;}
    } else {debugLog(USRMDEBUGPREFIX.'Could not return details of group. Group file not found [ '.$group_id.'.xml ]');return false;}
  }
  
  public function getGroupPermissions($group_id) {
    $group_details = $this->getGroupDetails($group_id);
    $group_permissions = array();
    if($group_details != false) {
      foreach($group_details['PERMISSIONS'] as $pkey => $pval) {
        $group_permissions[$pkey] = ($pval == 'TRUE' ? true : (strtolower($group_id) == 'administrators' ? true : false));
      }
    } return $group_permissions;
  }
  
  public function saveSettings() {
    
    # First, check for CSRF...
    if (!defined('GSNOCSRF') || (GSNOCSRF == FALSE) ) {
      if(!check_nonce($_POST['nonce'], "users_savesettings")) {
        die("CSRF detected!");	
      }
    }
    
    # Compile the XML data
    $xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8" ?><settings></settings>');		
	foreach ($_POST as $key => $value) {
      if ($key == 'nonce') {continue;}
      $value = htmlspecialchars(strip_tags($value));
      $xml->addChild($key, $value);
    }
		
    # Save the file
		if(!XMLsave($xml, GSDATAOTHERPATH.'/usrm_settings.xml')) {
      debugLog(USRMDEBUGPREFIX.'Unable to save the settings data XML file [ usrm_settings.xml ]');
      return false;
    } else {return true;}
  }
  
  public function getSettings($setting=null) {
    
    if(file_exists(GSDATAOTHERPATH.'usrm_settings.xml')) {
      if($xml = getXML(GSDATAOTHERPATH.'usrm_settings.xml')) {
        $settings = array();
        foreach($xml as $key => $value){
          $key = (string) $key;
          $value = (string) $value;
          $asBool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
          if ( $asBool !== null ) { $value = $asBool; }
          $settings[$key] = $value;
        }
        if(!is_null($setting) && isset($settings[$setting])) {
          return $settings[$setting];
        } else {return $settings;}
      } else {debugLog(USRMDEBUGPREFIX.'Could not get the XML data from file [ usrm_settings.xml ]');return false;}
    } else {debugLog(USRMDEBUGPREFIX.'Could not return the settings data. Settings file not found [ '.$username.'.xml ]');return false;}
    
  }
  
  public function generatePagesList($type) {
    GLOBAL $plugin_info;
    $returned_pages = array();
    
    $core_pages = array(
      'pages' => 'Pages',
      'upload' => 'Files',
      'theme' => 'Themes',
      'backups' => 'Backups',
      'plugins' => 'Plugins',
      'support' => 'Support',
      'settings' => 'Settings',
    );
    foreach($plugin_info as $plugin_id => $plugin_data) {
        $key = 'plugin-'.$plugin_id;
      if(!empty($key) && $plugin_data['version'] != "disabled") {
        $returned_pages[$key] = $plugin_data['name'];
      }
    }
    if($type=='core') {return $core_pages;}
    elseif($type=='plugins') {return $returned_pages;}
  }
  
  private function generateID($string) {
    $string = trim($string);
    if (isset($i18n['TRANSLITERATION']) && is_array($translit=$i18n['TRANSLITERATION']) && count($translit>0)) {
      $string = str_replace(array_keys($translit),array_values($translit),$string);
    }
    $string = to7bit($string, "UTF-8");
    $string = clean_url($string);
    return $string;
  }
  
}
