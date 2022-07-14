<?php
/**
 * UserManagement Plugin for GetSimple CMS
 *
 *
 * @package: gs-UserManagement
 * @version: 1.0.0-alpha
 * @author: John Stray <getsimple@johnstray.com>
 */

# Prevent impropper loading of this file. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }

/**
 * Initialize the plugin
 * Sets up default variables, registers actions, filters, styles and scripts with the system, loads in the class files
 * and brings in the frontend function mapping.
 *
 * @since 1.0.0
 * @return void
 */
function UserManagement_init(): void
{
    GLOBAL $SITEURL;

    # Define some required constants
    define( 'USRMVERS', '1.0.0-alpha' );
    define( 'USRMDATA', GSDATAPATH . 'users' . DIRECTORY_SEPARATOR );
    define( 'USRMSETTINGS', GSDATAPATH . 'usrm-config.xml' );

    # Tab / Sidebar Actions
    add_action( 'settings-sidebar', 'createSideMenu', [USRM, i18n(USRM . '/UI_SIDEBAR_BUTTON')] );

    # Hooks and Filters
    add_action( 'common', 'UserManagement_captureRedirect' ); // Capture then redirect hook
    add_action( 'index-login', 'UserManagement_injectLogin' ); // Inject message to login page

    # Register / Queue Stylesheets
    register_style( USRM . '_css', $SITEURL . '/plugins/' . USRM . '/includes/styles/admin_styles.css', USRMVERS, 'screen' );
    queue_style( USRM . '_css', GSBACK );

    # Register / Queue Scripts


    # Load in all the classes
    # - Ensuring the core UserManagement class is loaded before any others
    require_once( USRMPATH . 'class' . DIRECTORY_SEPARATOR . USRM . '.class.php' );
    $UserManagement_classFiles = glob( USRMPATH . 'class' . DIRECTORY_SEPARATOR . '*.class.php' );
    if ($UserManagement_classFiles !== false && is_array($UserManagement_classFiles) )
    {
        foreach ( $UserManagement_classFiles as $UserManagement_classFile )
        {
            if ( $UserManagement_classFile !== USRMPATH . 'class' . DIRECTORY_SEPARATOR . USRM . '.class.php' )
            {
                require_once( $UserManagement_classFile );
            }
        }
    }
}

/**
 * Main - Admin Backend Director
 * Manages and directs what we are doing on the admin backend pages
 *
 * @since 1.0.0
 * @return void
 */
function UserManagement_main(): void
{
    # Instatiate the core class so that we can make use of it on each of these pages.
    $UserManagement = new UserManagement();


    // Insert copyright footer to the bottom of the page
    echo "</div><div class=\"gs_usermanagement_ui_copyright-text\">UserManagement Plugin &copy; 2022 John Stray - Licensed under <a href=\"https://www.gnu.org/licenses/gpl-3.0.en.html\">GNU GPLv3</a>";
    echo "<div>If you like this plugin or have found it useful, please consider a <a href=\"https://paypal.me/JohnStray\">donation</a></div>";
}

/**
 * Display message
 * Function to display a message on the admin backend pages
 *
 * @since 1.0
 * @param string $message The message body to display
 * @param string $type The type of message to display, one of ['info', 'success', 'warn', 'error']
 * @return void
 */
function UserManagement_displayMessage( string $message, string $type = 'info', bool $close = true ): void
{
    if ( is_frontend() == false )
    {
        $removeit = (bool) $close ? ".removeit()" : "";
        $type = ucfirst( $type );
        if ( $close == false )
        {
            $message = $message . ' <a href="#" onclick="clearNotify();" style="float:right;">X</a>';
        }
        echo "<script>notify".$type."('".$message."')".$removeit.";</script>";
    }
}

/**
 * Debug Logging
 * Output debugging information to GetSimple's debug log when debugging enabled
 *
 * @since 1.0
 * @param string $message The text of the message to add to the log
 * @param string $type The type of message this is, could be 'ERROR', 'WARN', etc.
 * @return string The formatted message added to the debug log
 */
function UserManagement_debugLog( string $method, string $message, string $type = 'INFO' ): string
{
    if ( defined('GSDEBUG') && getDef('GSDEBUG', true) === true )
    {
        $debugMessage = "UserManagement Plugin (" . $method . ") [" . $type . "]: " . $message;
        debugLog( $debugMessage );
    }
    return $debugMessage || '';
}