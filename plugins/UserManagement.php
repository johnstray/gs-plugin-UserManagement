<?php
/**
 * UserManagement Plugin for GetSimple CMS
 * Insert description here...
 *
 * @package: gs-UserManagement
 * @version: 1.0.0-alpha
 * @author: John Stray <getsimple@johnstray.com>
 */

# Prevent impropper loading of this file. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }

# Define the plugin identifier and base path
define( 'USRM', basename(__FILE__, ".php") );
define( 'USRMPATH', GSPLUGINPATH . DIRECTORY_SEPARATOR . USRM . DIRECTORY_SEPARATOR );

# Setup languages and language settings
i18n_merge( USRM ) || i18n_merge( USRM, "en_US" );

# Require the common file and initialize the plugin
require_once( USRMPATH . 'common.php' );
UserManagement_init();

# Register this plugin with the system
register_plugin(
    USRM,                                                       // Plugin Identifier
    i18n_r(USRM . '/PLUGIN_NAME'),                              // Plugin Name
    USRMVERS,                                                   // Plugin Version
    "John Stray",                                               // Author's Name
    i18n_r(USRM . '/AUTHOR_URL'),                               // Author URL
    i18n_r(USRM . '/PLUGIN_DESC'),                              // Plugin Description
    'settings',                                                 // Where the backend pages sit
    'UserManagement_main'                                       // Main backend controller function
);
