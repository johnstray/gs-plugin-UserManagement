<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple User Management
 * Description: Enables advanced user management functions for GetSimple CMS
 * File Action: Admin functions - Makes everything happen on the backend
 */

function usrm_main() {
  
  $GSUsers = new GSUsers;
  exec_action('permissions');
  
  if(isset($_GET['action'])) {
    
    switch ($_GET['action']) {
      
      /* # USER MANAGEMENT # */
      
      /* Create or Edit a User */
      case 'user-editor':
        GLOBAL $SESSIONHASH;
        if(isset($_GET['username'])){$existing = $GSUsers->getUserProfile($_GET['username']);}
        ob_start();
        include(USRMINCFOLDER.'user_editor.php');
        exec_filter('checkPermission',array('users-edit',ob_get_clean()));
        break;
      
      /* Save the New or Updated User */
      case 'save-user':
        if($GSUsers->getPermission('users-edit')) {
          if($GSUsers->saveUser()) {
            outputMessage('Successfully saved the User! <a href="load.php?id='.USRMGMT.'&action=undo" style="float:right;">'.i18n_r('UNDO').'</a>','ok',null);
          } else {outputMessage('<b>ERROR:</b> Failed to save the user!','error');}
        } else {outputMessage('<b>ACCESS DENIED:</b> You are not permitted to create or modify users!','warning');}
        include(USRMINCFOLDER.'user_management.php');
        break;
      
      /* Delete the User */
      case 'delete-user':
        if($GSUsers->getPermission('users-delete')) {
          if($GSUsers->deleteUser()) {
            outputMessage('Successfully deleted the User! <a href="load.php?id='.USRMGMT.'&action=undo" style="float:right;">'.i18n_r('UNDO').'</a>','ok',null);
          } else {outputMessage('<b>ERROR:</b> Unable to delete the user!','error');}
        } else {outputMessage('<b>ACCESS DENIED:</b> You are not permitted to delete users!','warning');}
        include(USRMINCFOLDER.'user_management.php');
        break;
      
      /* # GROUP MANAGEMENT */
      
      /* View the Group list */
      case 'view-groups':
        include(USRMINCFOLDER.'group_management.php');
        break;
      
      /* Create or Edit a Group */
      case 'group-editor':
        GLOBAL $plugin_info, $permissions_array;
        ob_start();
        include(USRMINCFOLDER.'group_editor.php');
        exec_filter('checkPermission',array('users-editgroup',ob_get_clean()));
        break;
      
      /* Save the New or Updated Group */
      case 'save-group':
        if($GSUsers->getPermission('users-editgroup')) {
          if($GSUsers->saveGroup($_POST)) {
            outputMessage('Successfully saved the Group! <a href="load.php?id='.USRMGMT.'&action=undo" style="float:right;">'.i18n_r('UNDO').'</a>','ok',null);
          } else {outputMessage('<b>ERROR:</b> Failed to save the group!','error');}
        } else {outputMessage('<b>ACCESS DENIED:</b> You are not permitted to create or modify groups!','warning');}
        include(USRMINCFOLDER.'group_management.php');
        break;
      
      /* Delete the Group */
      case 'delete-group':
        if($GSUsers->getPermission('users-deletegroup') && isset($_GET['group'])) {
          if($GSUsers->deleteUser($_GET['group'])) {
            outputMessage('Successfully deleted the Group! <a href="load.php?id='.USRMGMT.'&action=undo" style="float:right;">'.i18n_r('UNDO').'</a>','ok',null);
          } else {outputMessage('<b>ERROR:</b> Unable to delete the Group!','error');}
        } else {outputMessage('<b>ACCESS DENIED:</b> You are not permitted to delete Groups!','warning');}
        include(USRMINCFOLDER.'group_management.php');
        break;
      
      /* User Management Settings */
      case 'settings':
        include(USRMINCFOLDER.'settings.php');
        break;
      
      /* Save Settings */
      case 'save-settings':
        if($GSUsers->getPermission('users-settings')) {
          if($GSUsers->saveSettings()) {
            outputMessage('Successfully saved the Settings! <a href="load.php?id='.USRMGMT.'&action=undo" style="float:right;">'.i18n_r('UNDO').'</a>','ok',null);
          } else {outputMessage('<b>ERROR:</b> Unable to save the Settings!','error');}
        } else {outputMessage('<b>ACCESS DENIED:</b> You are not permitted to save Settings!','warning');}
        include(USRMINCFOLDER.'settings.php');
        break;
        
      /* Denied permission redirects to here */
      case 'denied':
        include(USRMINCFOLDER.'denied.php');
        break;
      
      /* # Default Action (when incorrect supplied) # */
      default:
        include(USRMINCFOLDER.'user_management.php');
        break;
    }
    
  } else {
    /* # Default Action (when none supplied) # */
    include(USRMINCFOLDER.'user_management.php');
  }
  
  echo "</div><div class=\"copyright-text\">User Management Plugin &copy; 2016 John Stray - Licenced under GNU GPLv3";
  echo "<div>If you like this plugin or have found it useful, please consider a <a href=\"#\">donation</a></div>";
  
}

function reorderUserManagement() {
  GLOBAL $live_plugins;
  
  if (file_exists(GSPLUGINPATH)){
    $pluginfiles = getFiles(GSPLUGINPATH);
  }
  $phpfiles = array();
  foreach ($pluginfiles as $fi) {
    if (lowercase(pathinfo($fi, PATHINFO_EXTENSION))=='php') {
      $phpfiles[] = $fi;
    }
  }
  
  if($phpfiles[0] !== 'gs-users.php') { # Only reorder if user-managment is not first
    if(($key = array_search('gs-users.php', $phpfiles)) !== false) {
      unset($phpfiles[$key]);
    }
    $temp = array('gs-users.php');
    $phpfiles = $temp + $phpfiles;
    
    # Re-write the plugins.xml file with the new ordering
    $xml = @new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><channel></channel>'); 
    foreach ($phpfiles as $fi) {
      $plugins = $xml->addChild('item');  
      $p_note = $plugins->addChild('plugin');
      $p_note->addCData($fi);
      $p_note = $plugins->addChild('enabled');
      if (isset($live_plugins[(string)$fi])){
        $p_note->addCData($live_plugins[(string)$fi]);     
      } else {
       $p_note->addCData('false'); 
      } 
    }
    XMLsave($xml, GSDATAOTHERPATH."plugins.xml");  
    read_pluginsxml();
  }
  
}

function usrmCaptureRedirect() {
  $page_request = basename($_SERVER['PHP_SELF'],'.php');
  if($page_request != 'load') { // Let plugins handle their own permissions
    include('redirector.php');
  } elseif($page_request == 'load' && isset($_GET['id']) && $_GET['id'] == 'api.plugin') {
    // Handle the API Configuration page here...
  }
}

function usrmLoginRedirect() {
    GLOBAL $userid;
    $GSUsers = new GSUsers();
    $cUser = $GSUsers->getUserProfile($userid);
    $userLandingPage = $cUser['LANDINGPAGE'];
    if ( !empty($userLandingPage) ) {
        switch ($userLandingPage) {
            case "group-default":
                $groupDetails = $GSUsers->getGroupDetails($cUser['USERGROUP']);
                $groupLandingPage = (string) $groupDetails['LANDINGPAGE'];
                $landingPage = $groupLandingPage;
                break;
            case "global-default":
                # Get Global Landing Page
                break;
            default:
                $landingPage = $userLandingPage;
                break;
        }
    } else {
        $landingPage = 'pages';
    }
    var_dump(stripos($landingPage, 'plugin-'));
    if ( stripos($landingPage, 'plugin-') === 0 ) {
        # Landing page is a plugin
        $landingPage = 'load.php?id=' . substr($landingPage,7);
    } else {
        $landingPage .= '.php';
    }
    redirect($landingPage);
}

function injectLoginMessage() {
    $GSUsers = new GSUsers();
    $loginMsg = $GSUsers->getSettings('loginmsg');
    if ( !empty($loginMsg) ) {
        echo '<p style="text-align:justify;">'.$loginMsg.'</p>';
    }
    
    if( $GSUsers->getSettings('adminonly') ) {
        echo '<div class="notify notify_info">'.i18n_r(USRMGMT.'/ADMIN_ONLY_LOGIN').'</div>';
    }
  
}

function checkAdminsOnly() {
    GLOBAL $cookie_redirect, $userid;
    $GSUsers = new GSUsers();

    if ( $GSUsers->getSettings('adminonly') ) {
        $cUser = $GSUsers->getUserProfile($userid);
        if ( $cUser['USERGROUP'] == "administrators" ) {
            usrmLoginRedirect();
        } else {
            redirect('logout.php');
        }
    } else {
        usrmLoginRedirect();
    }
  
}

function outputMessage($message='', $type='ok', $remove = '.removeit()') {
  echo "<script>notify('$message','$type').popit()$remove;</script>";
}
