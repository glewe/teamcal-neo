<?php
/**
 * users.php
 * 
 * Users page controller
 *
 * @category TeamCal Neo 
* @version 1.0.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

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
$U1 = new Users(); // used for sending out notifications
$viewData['searchUser'] = '';
$viewData['searchGroup'] = 'All';
$viewData['searchRole'] = 'All';

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
    
   if (!$inputError)
   {
      // ,----------,
      // | Activate |
      // '-----------'
      if ( isset($_POST['btn_userActivate']) AND isset($_POST['chk_userActive']) )
      {
         $selected_users = $_POST['chk_userActive'];
         foreach($selected_users as $su=>$value)
         {
            $U->unhide($value);
            $U->unlock($value);
            $U->unverify($value);
            $U1->findByName($value);
            sendAccountActivatedMail($U1->email, $U1->username, $U1->firstname, $U1->lastname);
         }
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['btn_activate_selected'];
         $alertData['text'] = $LANG['users_alert_activate_selected_users'];
         $alertData['help'] = '';
      }
      // ,---------,
      // | Archive |
      // '---------'
      else if ( isset($_POST['btn_userArchive']) AND isset($_POST['chk_userActive']) ) 
      {
         $selected_users = $_POST['chk_userActive'];
         
         //
         // Check if one or more users already exists in any archive table.
         // If so, we will not archive anything.
         //
         $exists = FALSE;
         foreach($selected_users as $su=>$value)
         {
            if (!archiveUser($value)) $exists=TRUE;
         }
         
         if (!$exists)
         {
            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['btn_archive_selected'];
            $alertData['text'] = $LANG['users_alert_archive_selected_users'];
            $alertData['help'] = '';
         }
         else 
         {
            //
            // Failed, at least partially
            //
            $showAlert = TRUE;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['btn_archive_selected'];
            $alertData['text'] = $LANG['users_alert_archive_selected_users_failed'];
            $alertData['help'] = '';
         }
      }
      // ,---------,
      // | Restore |
      // '---------'
      else if ( isset($_POST['btn_profileRestore']) AND isset($_POST['chk_userArchived']) )
      {
         $selected_users = $_POST['chk_userArchived'];
         
         //
         // Check if one or more users already exists in any active table.
         // If so, we will not restore anything.
         //
         $exists = FALSE;
         foreach($selected_users as $su=>$value)
         {
            if (!restoreUser($value)) $exists=TRUE;
         }
          
         if (!$exists)
         {
            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['btn_restore_selected'];
            $alertData['text'] = $LANG['users_alert_restore_selected_users'];
            $alertData['help'] = '';
         }
         else
         {
            //
            // Failed, at least partially
            //
            $showAlert = TRUE;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['btn_restore_selected'];
            $alertData['text'] = $LANG['users_alert_restore_selected_users_failed'];
            $alertData['help'] = '';
         }
      }
      // ,---------------,
      // | Delete Active |
      // '---------------'
      else if ( isset($_POST['btn_profileDelete']) AND isset($_POST['chk_userActive']) )
      {
         $selected_users = $_POST['chk_userActive'];
         foreach($selected_users as $su=>$value)
         {
            //
            // Send notification e-mails to the subscribers of user events. In this case,
            // send before delete while we can still access info from the user.
            //
            if ($C->read("emailNotifications"))
            {
               $U1->findByName($value);
               sendUserEventNotifications("deleted", $U1->username, $U1->firstname, $U1->lastname);
            }
            deleteUser($value, false);
         }
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['btn_delete_selected'];
         $alertData['text'] = $LANG['users_alert_delete_selected_users'];
         $alertData['help'] = '';
      }
      // ,-----------------,
      // | Delete Archived |
      // '-----------------'
      else if ( isset($_POST['btn_profileDeleteArchived']) AND isset($_POST['chk_userArchived']) )
      {
         $selected_users = $_POST['chk_userArchived'];
         foreach($selected_users as $su=>$value)
         {
            deleteUser($value, $fromArchive = true);
         }
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['btn_delete_selected'];
         $alertData['text'] = $LANG['users_alert_delete_selected_users'];
         $alertData['help'] = '';
      }
      // ,----------------,
      // | Reset Password |
      // '----------------'
      else if ( isset($_POST['btn_userResetPassword']) AND isset($_POST['chk_userActive']) )
      {
         $selected_users = $_POST['chk_userActive'];
         foreach($selected_users as $su=>$value)
         {
            //
            // Find user and reset password
            //
            $U->findByName($value);
            $newpwd = generatePassword();
            $U->password = crypt($newpwd,$CONF['salt']);
            $U->last_pw_change = date("Y-m-d H:I:s");
            $U->update($U->username);
            $U->clearStatus($CONF['USCHGPWD']);
      
            //
            // Send notification e-mail
            //
            $message = $LANG['notification_greeting'];
            $message .= $LANG['notification_usr_pwd_reset'];
            $message .= $LANG['notification_usr_pwd_reset_user'];
            $message .= $value;
            $message .= "\r\n\r\n";
            $message .= $LANG['notification_usr_pwd_reset_pwd'];
            $message .= $newpwd;
            $message .= "\r\n\r\n";
            $message .= $LANG['notification_sign'];
            $to = $U->email;
            $subject = stripslashes($LANG['notification_usr_pwd_subject']);
            sendEmail($to, $subject, $message);
      
            //
            // Log this event
            //
            $LOG->log("logUser",$L->checkLogin(),"log_user_pwd_reset", $U->username);
         }
         
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['btn_reset_password_selected'];
         $alertData['text'] = $LANG['users_alert_reset_password_selected'];
         $alertData['help'] = '';
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

//
// Default: Get all active users
//
$users = $U->getAll('lastname', 'firstname', 'ASC', $archive = false, $includeAdmin = true);

// ,--------,
// | Search |
// '--------'
// Adjust users by requested search
//
if (isset($_POST['btn_search']))
{
   $searchUsers = array();
    
   if (isset($_POST['txt_searchUser']) AND strlen($_POST['txt_searchUser']))
   {
      $searchUser = sanitize($_POST['txt_searchUser']);
      $viewData['searchUser'] = $searchUser;
      $users = $U->getAllLike($searchUser);
   }
    
   if ( isset($_POST['sel_searchGroup']) AND ($_POST['sel_searchGroup'] != "All") )
   {
      $searchGroup = sanitize($_POST['sel_searchGroup']);
      $viewData['searchGroup'] = $searchGroup;
      foreach ($users as $user)
      {
         if ($UG->isMemberOfGroup($user['username'],$searchGroup)) 
         {
            $searchUsers[] = $user;
         }
      }
      $users = $searchUsers;
   }
   
   if ( isset($_POST['sel_searchRole']) AND ($_POST['sel_searchRole'] != "All") )
   {
      $searchRole = sanitize($_POST['sel_searchRole']);
      $viewData['searchRole'] = $searchRole;
      foreach ($users as $user)
      {
         if ($user['role'] == $searchRole) 
         {
            $searchUsers[] = $user;
         }
      }
      $users = $searchUsers;
   }
}

//
// Load these users for the view
//
$viewData['users'] = array();
$i = 0;
foreach ($users as $user)
{
   $U->findByName($user['username']);

   $viewData['users'][$i]['username'] = $user['username'];
   if ( $U->firstname!="" ) $viewData['users'][$i]['dispname'] = $U->lastname.", ".$U->firstname;
   else $viewData['users'][$i]['dispname'] = $U->lastname;
   $viewData['users'][$i]['dispname'] .= ' ('.$U->username.')';
   $viewData['users'][$i]['role'] = $RO->getNameById($U->role);
   $viewData['users'][$i]['color'] = $RO->getColorById($U->role);
    
   //
   // Determine attributes
   //
   $viewData['users'][$i]['locked'] = false;
   $viewData['users'][$i]['hidden'] = false;
   $viewData['users'][$i]['onhold'] = false;
   $viewData['users'][$i]['verify'] = false;
   if ( $U->locked ) $viewData['users'][$i]['locked'] = true;
   if ( $U->hidden ) $viewData['users'][$i]['hidden'] = true;
   if ( $U->onhold ) $viewData['users'][$i]['onhold'] = true;
   if ( $U->verify ) $viewData['users'][$i]['verify'] = true;
       
   $viewData['users'][$i]['created'] = $U->created;
   $viewData['users'][$i]['last_login'] = $U->last_login;

   $i++;
}

//
// Always load all archived users
//
$viewData['users1'] = array();
$i = 0;
$users1 = $U->getAll('lastname', 'firstname', 'ASC', $archive = true);
foreach ($users1 as $user1)
{
   $U->findByName($user1['username'], $archive = true);
    
   $viewData['users1'][$i]['username'] = $user1['username'];
   if ( $U->firstname!="" ) $viewData['users1'][$i]['dispname'] = $U->lastname.", ".$U->firstname;
   else $viewData['users1'][$i]['dispname'] = $U->lastname;
   $viewData['users1'][$i]['dispname'] .= ' ('.$U->username.')';
   $viewData['users1'][$i]['role'] = $RO->getNameById($U->role);
   $viewData['users1'][$i]['color'] = $RO->getColorById($U->role);
     
   //
   // Determine attributes
   //
   $viewData['users1'][$i]['locked'] = false;
   $viewData['users1'][$i]['hidden'] = false;
   $viewData['users1'][$i]['onhold'] = false;
   $viewData['users1'][$i]['verify'] = false;
   if ( $U->locked ) $viewData['users1'][$i]['locked'] = true;
   if ( $U->hidden ) $viewData['users1'][$i]['hidden'] = true;
   if ( $U->onhold ) $viewData['users1'][$i]['onhold'] = true;
   if ( $U->verify ) $viewData['users1'][$i]['verify'] = true;
    
   $viewData['users1'][$i]['created'] = $U->created;
   $viewData['users1'][$i]['last_login'] = $U->last_login;
   
   $i++;
}

$viewData['groups'] = $G->getAll();
$viewData['roles'] = $RO->getAll();

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
