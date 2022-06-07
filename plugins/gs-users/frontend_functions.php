<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple User Management
 * Description: Enables advanced user management functions for GetSimple CMS
 * File Action: Frontend Functions - Makes everything happen on the frontend
 */

function usrm_show_user_profiles() {
  
  GLOBAL $content, $title;
  $GSUsers = new GSUsers;
  
  if(return_page_slug() == $GSUsers->getSettings('profileurl')) {
    if(isset($_GET['u'])) {
      $user = $GSUsers->getUserProfile($_GET['u']);
      if($user !== false) {
        foreach ($user as $key => $value) {
          $variables[] = '{% '.$key.' %}';
          $replace[] = $value;
        }
        $content = str_replace($variables, $replace, $content);
        $title = str_replace($variables, $replace, $title);
      } else {
        $content = '<p class="text-danger">User doesn&apos;t exist!</p>';
      }
    } else {
      $content = '<p class="text-danger">User not specified!</p>';
    }
  } elseif(return_page_slug() == $GSUsers->getSettings('pageurl')) {
    preg_match('/&lt;(p|div|li) class=\&quot;user\&quot;(.*?)&gt;(.*?)&lt;\/(p|div|li)&gt;/s', $content, $matches);
    $placeholder = $matches[0];
    $all_users = $GSUsers->getAllUsers();
    $combined = '';
    
    foreach ($all_users as $user) {
      $profile = $GSUsers->getUserProfile($user);
      if($profile !== false) {
        foreach ($profile as $key => $value) {
          $variables[] = '{% '.$key.' %}';
          $replace[] = $value;
        }
        $combined .= str_replace($variables, $replace, $placeholder);
        unset($variables);unset($replace);
      }
    }
    
    $content = str_replace($matches[0],$combined,$content);
    
  }
  
  return $content;
  
}