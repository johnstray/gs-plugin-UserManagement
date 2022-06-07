<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
error_reporting(E_ALL & ~E_WARNING);
/**
 * Plugin Name: GetSimple User Management
 * Description: Enables advanced user management functions for GetSimple CMS
 * File Action: Checks request permission and redirects if not permitted
 */

/* Pages Management */
if($page_request == 'pages' && get_user_permission('pages')) {
  if(!get_user_permission('pages-view')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'edit' && get_user_permission('pages')) {
  if(!get_user_permission('pages-edit')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'deletefile' && get_user_permission('pages')) {
  if(isset($_GET['id']) && !get_user_permission('pages-delete')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'menu-manager' && get_user_permission('pages')) {
  if(!get_user_permission('pages-menumanager')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
}

/* File Management */
if($page_request == 'upload' && get_user_permission('upload')) {
  if(!get_user_permission('upload-list')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'upload-uploadify' && get_user_permission('upload')) {
  if(!get_user_permission('upload-upload')) {
    GLOBAL $SESSIONHASH; $SESSIONHASH = ''; // Causes uploadify to fail session authentication.
  }
} elseif($page_request == 'deletefile' && get_user_permission('upload')) {
  if(isset($_GET['file']) && !get_user_permission('upload-delete')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'image' && get_user_permission('upload')) {
  if(!get_user_permission('upload-thumbnail')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
}

/* Theme Management */
if($page_request == 'theme' && get_user_permission('theme')) {
  if(!get_user_permission('theme-change')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'theme-edit' && get_user_permission('theme')) {
  if(!get_user_permission('theme-modify')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'components' && get_user_permission('theme')) {
  if(!get_user_permission('theme-components')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'sitemap' && get_user_permission('theme')) {
  if(!get_user_permission('theme-sitemap')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
}

/* Page Backups */
if($page_request == 'backups' && get_user_permission('backups')) {
  if(isset($_GET['deleteall']) && !get_user_permission('backups-delete')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  } elseif(!get_user_permission('backups-list')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'backup-edit' && get_user_permission('backups')) {
  if(isset($_GET['p'])) {
    if($_GET['p'] == 'view' && !get_user_permission('backups-view')) {
      redirect('load.php?id='.USRMGMT.'&action=denied');
    } elseif($_GET['p'] == 'restore' && !get_user_permission('backups-restore')) {
      redirect('load.php?id='.USRMGMT.'&action=denied');
    }
  }
}

/* Website Archives */
/* archive.php - List
   archive.php?do - Create
   download.php?file=*\backups\zip\*.zip - Download
   deletefile.php?zip=*_archive.zip - Delete*/
if($page_request == 'archive' && get_user_permission('backups')) {
  if(isset($_GET['do']) && !get_user_permission('backups-archivecreate')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  } elseif(!get_user_permission('backups-archive')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
} elseif($page_request == 'download' && get_user_permission('backups')) {
  if(isset($_GET['file']) && (stripos($_GET['file'], "/backups/zip/") !== false))
  if(!get_user_permission('theme-sitemap')) {
    redirect('load.php?id='.USRMGMT.'&action=denied');
  }
}

/* Plugin Management */


/* Support */


/* Settings */


/* User Management */
