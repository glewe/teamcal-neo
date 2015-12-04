<?php
/**
 * permissions.php
 * 
 * Permissions page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

/**
 * ========================================================================
 * Check if allowed
 */
if (!isAllowed($CONF['controllers'][$controller]->permission))
{
   $alertData['type'] = 'warning';
   $alertData['title'] = $LANG['alert_alert_title'];
   $alertData['subject'] = $LANG['alert_not_allowed_subject'];
   $alertData['text'] = $LANG['alert_not_allowed_text'];
   $alertData['help'] = $LANG['alert_not_allowed_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */
$showAlert = false;
$roles = $RO->getAll();

/**
 * Controller based permission entries
 */
$perms = array();
$permgroups = array();
foreach ($CONF['controllers'] as $contr)
{
   if (strlen($contr->permission))
   {
      /**
       * Add the permission name to the permissions array
       */
      $perms[] = $contr->permission;
      /**
       * Also add it to the appropriate permission group array
       */
      $permgroups[$contr->permission][] = $contr->permission;
   }
}

/**
 * Feature based permission entries
 */
$fperms = array (
   'calendareditown',
   'calendareditgroup',
   'calendareditall',
   'calendarviewgroup',
   'calendarviewall',
   'useraccount',
   'groupmemberships',
);

/**
 * ========================================================================
 * Get requested scheme and view mode
 */
$scheme = "Default";
if ( isset($_GET['scheme']) ) 
{
   if ( $P->schemeExists($_GET['scheme']) ) $scheme = $_GET['scheme'];
}

$modes = array('byperm', 'byrole');
$mode = "byperm";
if ( isset($_GET['mode']) ) 
{
   $mode = $_GET['mode'];
   if ( !in_array($mode, $modes) ) $mode = 'byperm';
}

/**
 * ========================================================================
 * Process form
 */
/**
 * ,----------,
 * | Activate |
 * '----------'
 */
if ( isset($_POST['btn_permActivate']) ) 
{
   $C->save("permissionScheme",$_POST['sel_scheme']);
   
   /**
    * Log this event
    */
   $LOG->log("logPermission",$L->checkLogin(),"log_perm_activated", $_POST['sel_scheme']);
   header("Location: index.php?action=permissions&scheme=".$_POST['sel_scheme']);
}
/**
 * ,--------,
 * | Delete |
 * '--------'
 */
else if ( isset($_POST['btn_permDelete']) ) 
{
   /**
    * The Default scheme cannot be deleted
    */
   if ($_POST['sel_scheme']!="Default") 
   {
      $P->deleteScheme($_POST['sel_scheme']);
      $C->save("permissionScheme","Default");
      
      /**
       * Log this event
       */
      $LOG->log("logPermission",$L->checkLogin(),"log_perm_deleted", $_POST['sel_scheme']);
      header("Location: index.php?action=permissions&scheme=Default");
   }
}
/**
 * ,-------, ,--------,
 * | Reset | | Create |
 * '-------' '--------'
 * Reset Default permission scheme or create a new with standard settings
 */
else if ( isset($_POST['btn_permReset']) OR isset($_POST['btn_permCreate']) )
{
   $event = "log_perm_reset";
   if ( isset($_POST['btn_permCreate']) ) 
   {
      if (!preg_match('/^[a-zA-Z0-9-]*$/', $_POST['txt_newScheme'])) 
      {
         /**
          * Permission name invalid
          */
         $showAlert = true;
         $alertData['type'] = 'danger';
         $alertData['title'] = $LANG['alert_danger_title'];
         $alertData['subject'] = $LANG['alert_input_validation_subject'];
         $alertData['text'] = str_replace('%1%', $_POST['txt_newScheme'], $LANG['alert_perm_invalid']);
      }
      else 
      {
         $scheme = $_POST['txt_newScheme'];
         if ($P->schemeExists($scheme)) 
         {
            /**
             * Permission name exists
             */
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['alert_input_validation_subject'];
            $alertData['text'] = str_replace('%1%', $_POST['txt_newScheme'], $LANG['alert_perm_exists']);
         }
      }
      $event = "log_perm_created";
   }

   if (!$showAlert) 
   {
      /**
       * First, delete the existing scheme entries
       */
      $P->deleteScheme($scheme);

      /**
       * Then create new entries for controller permissions
       */
      foreach($perms as $perm) 
      {
         foreach($roles as $role) 
         {
            if ($role['id'] == 1) $allowed = 1; else $allowed = 0;
            $P->setPermission($scheme, $perm, $role['id'], $allowed);
         }
      }

      /**
       * Then create new entries for feature permissions
       */
      foreach($fperms as $fperm)
      {
         foreach($roles as $role)
         {
            if ($role['id'] == 1) $allowed = 1; else $allowed = 0;
            $P->setPermission($scheme, $fperm, $role['id'], $allowed);
         }
      }
      
      /**
       * Log this event
       */
      $LOG->log("logPermission",$L->checkLogin(), $event, $scheme);
      header("Location: index.php?action=permissions&scheme=".$scheme);
   }
}
/**
 * ,------,
 * | Save |
 * '------'
 */
else if ( isset($_POST['btn_permApply']) ) 
{
   /**
    * Controller permission groups
    */
   foreach($permgroups as $permgroup => $permnames)
   {
      foreach($permnames as $permname)
      {
         foreach($roles as $role)
         {
            if ( isset($_POST['chk_'.$permgroup.'_'.$role['id']]) && $_POST['chk_'.$permgroup.'_'.$role['id']] )
            {
               $P->setPermission($scheme,$permname,$role['id'],1);
            }
            else
            {
               $P->setPermission($scheme,$permname,$role['id'],0);
            }
         
            /**
             * Make sure Administrator role is permitted
             */
            $P->setPermission($scheme,$permname,1,1);
         }
      }
   }
   
   /**
    * Feature permissions
    */
   foreach($fperms as $fperm)
   {
      foreach($roles as $role)
      {
         if ( isset($_POST['chk_'.$fperm.'_'.$role['id']]) && $_POST['chk_'.$fperm.'_'.$role['id']] )
         {
            $P->setPermission($scheme,$fperm,$role['id'],1);
         }
         else
         {
            $P->setPermission($scheme,$fperm,$role['id'],0);
         }
          
         /**
          * Make sure Administrator role is permitted
          */
         $P->setPermission($scheme,$fperm,1,1);
      }
   }
    
   /**
    * Log this event
    */
   $LOG->log("logPermission",$L->checkLogin(),"log_perm_changed", $scheme);
   header("Location: index.php?action=permissions&scheme=".$scheme);
}
/**
 * ,--------,
 * | Select |
 * '--------'
 */
else if (isset($_POST['btn_permSelect'])) 
{
   header("Location: index.php?action=permissions&scheme=".$_POST['sel_scheme']);
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$permData['currentScheme'] = $C->read("permissionScheme");
$permData['mode'] = $mode;
$permData['perms'] = $perms;
$permData['permgroups'] = $permgroups;
$permData['fperms'] = $fperms;
$permData['roles'] = $roles;
$permData['schemes'] = $P->getSchemes();
$permData['scheme'] = $scheme;

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
