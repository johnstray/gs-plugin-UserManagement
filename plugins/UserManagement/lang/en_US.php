<?php
/**
 * UserManagement Plugin for GetSimple CMS
 * Insert a description here...
 *
 * @package: gs-UserManagement
 * @version: 1.0.0-alpha
 * @author: John Stray <getsimple@johnstray.com>
 */

# Prevent impropper loading of this file. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }

$i18n = array(

    # -----
    # General Info
    # -----

    'PLUGIN_NAME' => "UserManagement",
    'PLUGIN_DESC' => "",
    'AUTHOR_URL' => "https://johnstray.com/gs-plugin/gsimpleblog/?lang=en_US",


    # -----
    # Tab / Sidebar Buttons
    # -----

    'UI_SIDEBAR_BUTTON' => "User Management",

);
