<?php
/**
 * permissions.php
 * 
 * Permissions page controller
 *
 * @category TeamCal Neo 
 * @version 2.2.1
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
// CHECK PERMISSION
//
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

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
// VARIABLE DEFAULTS
//
$showAlert = false;
$roles = $RO->getAll();
$scheme = "Default";

//
// Controller based permission entries
//
$perms = array();
$permgroups = array();
asort($CONF['controllers']);
foreach ($CONF['controllers'] as $contr)
{
   if (strlen($contr->permission))
   {
      //
      // Add the permission name to the permissions array
      //
      $perms[] = $contr->permission;
      
      //
      // Also add it to the appropriate permission group array
      //
      $permgroups[$contr->permission][] = $contr->permission;
   }
}

//
// Feature based permission entries
//
$fperms = array (
   'calendareditown',
   'calendareditgroup',
   'calendareditgroupmanaged',
   'calendareditall',
   'calendarviewgroup',
   'calendarviewall',
   'daynoteglobal',
   'manageronlyabsences',
   'useraccount',
   'userabsences',
   'userallowance',
   'useravatar',
   'usercustom',
   'usergroups',
   'groupmemberships',
   'usernotifications',
   'useroptions',
);

//
// Get the active scheme and view mode
//
if (!$scheme=$C->read('permissionScheme')) $scheme = "Default";
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

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST))
{
   //
   // Sanitize input
   //
   $_POST = sanitize($_POST);

   //
   // Form validation
   //
   $inputError = false;
    
   //
   // Validate input data. If something is wrong or missing, set $inputError = true
   //
    
   if (!$inputError)
   {
      // ,----------,
      // | Activate |
      // '----------'
      if ( isset($_POST['btn_permActivate']) ) 
      {
         $C->save("permissionScheme",$_POST['sel_scheme']);
         
         //
         // Log this event
         //
         $LOG->log("logPermission",L_USER,"log_perm_activated", $_POST['sel_scheme']);
         header("Location: index.php?action=permissions&scheme=".$_POST['sel_scheme']);
      }
      // ,--------,
      // | Delete |
      // '--------'
      else if ( isset($_POST['btn_permDelete']) ) 
      {
         //
         // The Default scheme cannot be deleted
         //
         if ($_POST['sel_scheme']!="Default") 
         {
            $P->deleteScheme($_POST['sel_scheme']);
            $C->save("permissionScheme","Default");
            
            //
            // Log this event
            //
            $LOG->log("logPermission",L_USER,"log_perm_deleted", $_POST['sel_scheme']);
            header("Location: index.php?action=permissions&scheme=Default");
         }
      }
      // ,--------,
      // | Create |
      // '--------'
      // Create a new scheme with admin-only settings
      //
      else if ( isset($_POST['btn_permCreate']) )
      {
         if (!preg_match('/^[a-zA-Z0-9-]*$/', $_POST['txt_newScheme'])) 
         {
            //
            // Permission name invalid
            //
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
               //
               // Permission name exists
               //
               $showAlert = true;
               $alertData['type'] = 'danger';
               $alertData['title'] = $LANG['alert_danger_title'];
               $alertData['subject'] = $LANG['alert_input_validation_subject'];
               $alertData['text'] = str_replace('%1%', $_POST['txt_newScheme'], $LANG['alert_perm_exists']);
            }
         }
      
         if (!$showAlert) 
         {
            //
            // First, delete the existing scheme entries
            //
            $P->deleteScheme($scheme);
      
            //
            // Then create new entries for controller permissions
            //
            foreach($perms as $perm) 
            {
               foreach($roles as $role) 
               {
                  if ($role['id'] == 1) $allowed = 1; else $allowed = 0;
                  $P->setPermission($scheme, $perm, $role['id'], $allowed);
               }
            }
      
            //
            // Then create new entries for feature permissions
            //
            foreach($fperms as $fperm)
            {
               foreach($roles as $role)
               {
                  if ($role['id'] == 1) $allowed = 1; else $allowed = 0;
                  $P->setPermission($scheme, $fperm, $role['id'], $allowed);
               }
            }
            
            //
            // Log this event
            //
            $LOG->log("logPermission",L_USER, "log_perm_created", $scheme);
            header("Location: index.php?action=permissions&scheme=".$scheme);
         }
      }
      // ,-------,
      // | Reset |
      // '-------'
      // Reset a permission scheme with Default settings
      //
      else if ( isset($_POST['btn_permReset']) )
      {
         if ($scheme != "Default") 
         {
            //
            // Set entries for controller permissions based on Default
            //
            foreach($perms as $perm) 
            {
               foreach($roles as $role) 
               {
                  if ($P->isAllowed("Default",$perm,$role['id'])) $allowed = 1;
                  else $allowed = 0;
                  if ($role['id'] == 1) $allowed = 1;
                  $P->setPermission($scheme, $perm, $role['id'], $allowed);
               }
            }
      
            //
            // Set entries for feature permissions based on Default
            //
            foreach($fperms as $fperm)
            {
               foreach($roles as $role)
               {
                  if ($P->isAllowed("Default",$fperm,$role['id'])) $allowed = 1;
                  else $allowed = 0;
                  if ($role['id'] == 1) $allowed = 1;
                  $P->setPermission($scheme, $fperm, $role['id'], $allowed);
               }
            }
            
            //
            // Log this event
            //
            $LOG->log("logPermission",L_USER, "log_perm_reset", $scheme);
            header("Location: index.php?action=permissions&scheme=".$scheme);
         }
         else 
         {
            //
            // Default permission scheme cannot be reset
            //
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $LANG['alert_warning_title'];
            $alertData['subject'] = $LANG['alert_input_validation_subject'];
            $alertData['text'] = $LANG['alert_perm_default'];
         }
      }
      // ,------,
      // | Save |
      // '------'
      else if ( isset($_POST['btn_permSave']) ) 
      {
         //
         // Controller permission groups
         //
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
               
                  //
                  // Make sure Administrator role is permitted
                  //
                  $P->setPermission($scheme,$permname,1,1);
               }
            }
         }
         
         //
         // Feature permissions
         //
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
                
               //
               // Make sure Administrator role is permitted
               //
               $P->setPermission($scheme,$fperm,1,1);
            }
         }
          
         //
         // Log this event
         //
         $LOG->log("logPermission",L_USER,"log_perm_changed", $scheme);
         header("Location: index.php?action=permissions&scheme=".$scheme);
      }
      // ,--------,
      // | Select |
      // '--------'
      else if (isset($_POST['btn_permSelect'])) 
      {
         header("Location: index.php?action=permissions&scheme=".$_POST['sel_scheme']);
      }
   }
   else
   {
      //
      // Input validation failed
      //
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['register_alert_failed'];
      $alertData['help'] = '';
   }
}
      
//=============================================================================
//
// PREPARE VIEW
//
$viewData['currentScheme'] = $C->read("permissionScheme");
$viewData['mode'] = $mode;
$viewData['perms'] = $perms;
$viewData['permgroups'] = $permgroups;
$viewData['fperms'] = $fperms;
$viewData['roles'] = $roles;
$viewData['schemes'] = $P->getSchemes();
$viewData['scheme'] = $scheme;

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
