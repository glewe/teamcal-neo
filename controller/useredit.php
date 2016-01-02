<?php
/**
 * useredit.php
 * 
 * User edit page controller
 *
 * @category TeamCal Neo 
 * @version 0.4.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

//=============================================================================
//
// CHECK URL PARAMETERS
//
$UP = new Users(); // for the profile to be created or updated
if (isset($_GET['profile']))
{
   $missingData = FALSE;
   $profile = sanitize($_GET['profile']);
   if (!$UP->findByName($profile)) $missingData = TRUE;
}
else
{
   $missingData = TRUE;
}

if ($missingData)
{
   //
   // URL param fail
   //
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

//=============================================================================
//
// CHECK PERMISSION
//
$allowed = FALSE;
if ($UL->username == $profile OR isAllowed($CONF['controllers'][$controller]->permission))
{
   $allowed = TRUE;
}

if (!$allowed)
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
$inputAlert = array();

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
   if (isset($_POST['btn_profileUpdate']))
   {
      if (!formInputValid('txt_lastname', 'alpha_numeric_dash')) $inputError = true;
      if (!formInputValid('txt_firstname', 'alpha_numeric_dash')) $inputError = true;
      if (!formInputValid('txt_title', 'alpha_numeric_dash_blank_dot')) $inputError = true;
      if (!formInputValid('txt_position', 'alpha_numeric_dash_blank')) $inputError = true;
      if (!formInputValid('txt_email', 'required|email')) $inputError = true;
      if ( (isset($_POST['txt_password']) and strlen($_POST['txt_password'])) or (isset($_POST['txt_password2']) and strlen($_POST['txt_password2'])))
      {
         if (!formInputValid('txt_password', 'pwd'.$C->read('pwdStrength'))) $inputError = true;
         if (!formInputValid('txt_password2', 'required|pwd'.$C->read('pwdStrength'))) $inputError = true;
         if (!formInputValid('txt_password2', 'match', 'txt_password'))
         {
            $inputAlert['password2'] = sprintf($LANG['alert_input_match'], $LANG['profile_password2'], $LANG['profile_password']);
            $inputError = true;
         }
      }
      if (!formInputValid('txt_phone', 'phone_number')) $inputError = true;
      if (!formInputValid('txt_mobilephone', 'phone_number')) $inputError = true;
      if (!formInputValid('txt_google', 'alpha_numeric_dash')) $inputError = true;
      if (!formInputValid('txt_linekdin', 'alpha_numeric_dash')) $inputError = true;
      if (!formInputValid('txt_skype', 'alpha_numeric_dash')) $inputError = true;
      if (!formInputValid('txt_twitter', 'alpha_numeric_dash')) $inputError = true;
   }
    
   if (!$inputError)
   {
      // ,--------,
      // | Update |
      // '--------'
      if (isset($_POST['btn_profileUpdate']))
      {
         $reloadPage = false;
         
         //
         // Personal
         //
         $UP->lastname = $_POST['txt_lastname'];
         $UP->firstname = $_POST['txt_firstname'];
         $UO->save($profile, 'title', $_POST['txt_title']);
         $UO->save($profile, 'position', $_POST['txt_position']);
         $UO->save($profile, 'id', $_POST['txt_id']);
         if (isset($_POST['opt_gender'])) $UO->save($profile, 'gender', $_POST['opt_gender']); else $UO->save($profile, 'gender', 'male');
          
         //
         // Contact
         //
         $UP->email = $_POST['txt_email'];
         $UO->save($profile, 'phone', $_POST['txt_phone']);
         $UO->save($profile, 'mobile', $_POST['txt_mobilephone']);
         $UO->save($profile, 'facebook', $_POST['txt_facebook']);
         $UO->save($profile, 'google', $_POST['txt_google']);
         $UO->save($profile, 'linkedin', $_POST['txt_linkedin']);
         $UO->save($profile, 'skype', $_POST['txt_skype']);
         $UO->save($profile, 'twitter', $_POST['txt_twitter']);
          
         //
         // Options
         //
         if (isset($_POST['sel_theme'])) 
         {
            if ($_POST['sel_theme'] != $UO->read($profile,'theme')) $reloadPage = true; // New theme needs a page reload later
            $UO->save($profile, "theme", $_POST['sel_theme']); 
         }
         else 
         {
            $UO->save($profile, 'language', 'default');
         }
         
         if (isset($_POST['chk_menuBarInverse']) && $_POST['chk_menuBarInverse']) 
         {
            if (!$UO->read($profile,'menuBarInverse')) $reloadPage = true; // New navbar setting needs a page reload later
            $UO->save($profile, 'menuBarInverse', '1'); 
         } 
         else 
         {
            if ($UO->read($profile,'menuBarInverse')) $reloadPage = true; // New navbar setting needs a page reload later
            $UO->save($profile, 'menuBarInverse', '0');
         }
          
         if (isset($_POST['sel_language']))
         {
            if ($_POST['sel_language'] != $UO->read($profile,'language')) $reloadPage = true; // New language needs a page reload later
            $UO->save($profile, "language", $_POST['sel_language']);
         }
         else
         {
            $UO->save($profile, 'language', 'default');
         }
         
         //
         // Account
         //
         if (isAllowed("useraccount")) 
         {
            if (isset($_POST['sel_role'])) $UP->role = $_POST['sel_role']; else $UP->role = '2';
            if (isset($_POST['chk_locked']) AND $_POST['chk_locked']) $UP->locked = '1'; else $UP->locked = '0';
            if (isset($_POST['chk_hidden']) AND $_POST['chk_hidden']) $UP->hidden = '1'; else $UP->hidden = '0';
            if (isset($_POST['chk_onhold']) AND $_POST['chk_onhold']) $UP->onhold = '1'; else $UP->onhold = '0';
            if (isset($_POST['chk_verify']) AND $_POST['chk_verify']) $UP->verify = '1'; else $UP->verify = '0';
         }
          
         //
         // Password
         //
         if ( isset($_POST['txt_password']) AND strlen($_POST['txt_password']) AND isset($_POST['txt_password2']) AND strlen($_POST['txt_password2']) AND $_POST['txt_password'] == $_POST['txt_password2'] )
         {
            $UP->password = crypt($_POST['txt_password'], $CONF['salt']);
            $UP->last_pw_change = date("Y-m-d H:I:s");
         }
          
         //
         // Groups
         //
         if (isAllowed("groupmemberships"))
         {
            $UG->deleteByUser($profile);
            if (isset($_POST['sel_memberships']) )
            {
               foreach ($_POST['sel_memberships'] as $grp)
               {
                  if ($G->getById($grp)) $UG->save($profile, $grp, 'member');
               }
            }
            if (isset($_POST['sel_managerships']) )
            {
               foreach ($_POST['sel_managerships'] as $grp)
               {
                  if ($G->getById($grp)) $UG->save($profile, $grp, 'manager');
               }
            }
         }
          
         //
         // Avatar
         //
         if (isset($_POST['opt_avatar'])) 
         {
            $UO->save($profile, 'avatar', $_POST['opt_avatar']);
         }
         elseif ( (!$UO->read($profile, 'avatar') AND ($UO->read($profile, 'gender') == 'male' OR $UO->read($profile, 'gender') == 'female')) OR 
                  ($UO->read($profile, 'avatar') == 'noavatar_male.png' AND $UO->read($profile, 'gender') == 'female') OR
                  ($UO->read($profile, 'avatar') == 'noavatar_female.png' AND $UO->read($profile, 'gender') == 'male')
            )
         {
            $UO->save($profile, 'avatar', 'noavatar_'.$UO->read($profile, 'gender').'.png');
         }
         else
         {
            if (!$UO->read($profile, 'avatar')) $UO->save($profile, 'avatar', 'noavatar_male.png');
         }
          
         $UP->update($profile);
          
         //
         // Send notification e-mails to the subscribers of user events
         //
         if ($C->read("emailNotifications"))
         {
            sendUserEventNotifications("changed", $UP->username, $UP->firstname, $UP->lastname);
         }
          
         //
         // Log this event
         //
         $LOG->log("logUser",$L->checkLogin(),"log_user_updated", $UP->username);
          
         //
         // Reload page in case of language change, so it takes effect.
         //
         if ($reloadPage)
         {
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&profile=" . $profile);
            die();
         }
         
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['profile_alert_update'];
         $alertData['text'] = $LANG['profile_alert_update_success'];
         $alertData['help'] = '';
      }
      // ,--------,
      // | Upload |
      // '--------'
      else if (isset ($_POST['btn_uploadAvatar']))
      {
         $UPL = new Upload();
         $UPL->upload_dir = $CONF['app_avatar_dir'];
         $UPL->extensions = $CONF['avatarExtensions'];
         $UPL->do_filename_check = "y";
         $UPL->replace = "y";
         $UPL->the_temp_file = $_FILES['file_avatar']['tmp_name'];
         $UPL->http_error = $_FILES['file_avatar']['error'];
         
         //
         // One avatar per user at a time. Change filename to username
         //
         $pieces = explode('.',$_FILES['file_avatar']['name']);
         $UPL->the_file = $UL->username . "." . $pieces[1];
         
         if ($UPL->upload())
         {
            $full_path = $UPL->upload_dir . $UPL->file_copy;
            $info = $UPL->getUploadedFileInfo($full_path);
            $UO->save($profile, 'avatar', $UPL->uploaded_file['name']);
            
            //
            // Log this event
            //
            $LOG->log("logUser",$L->checkLogin(),"log_user_updated", $UP->username);
            
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&profile=" . $profile);
            die();
         }
         else
         {
            //
            // Upload failed
            //
            $showAlert = TRUE;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = 'Avatar ' . $LANG['btn_upload'];
            $alertData['text'] = $UPL->getErrors();
            $alertData['help'] = '';
         }
      }
      // ,---------------,
      // | Delete Avatar |
      // '---------------'
      else if (isset ($_POST['btn_deleteAvatar']))
      {
         //
         // Delete all avatar files for this user
         //
         $mask = $CONF['app_avatar_dir'] . $UL->username . ".*";
         array_map('unlink', glob($mask));
         $UO->save($profile, 'avatar', '');
            
         //
         // Log this event
         //
         $LOG->log("logUser",$L->checkLogin(),"log_user_updated", $UP->username);
            
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&profile=" . $profile);
         die();
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
      $alertData['text'] = $LANG['profile_alert_save_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['profile'] = $profile;
$viewData['fullname'] = $UP->firstname . ' ' . $UP->lastname . ' (' . $UP->username . ')';
$viewData['avatar'] = ($UO->read($profile, 'avatar')) ? $UO->read($profile, 'avatar') : 'noavatar_' . $UO->read($profile, 'gender') . '.png';
$viewData['avatar_maxsize'] = $CONF['avatarMaxsize'];
$viewData['avatar_formats'] = implode(', ', $CONF['avatarExtensions']);
$viewData['showingroups'] = $UO->read($profile, 'showingroups');
$viewData['notifycalgroup'] = $UO->read($profile, 'notifycalgroup');

$groups = $G->getAll();

$viewData['personal'] = array (
   array ( 'prefix' => 'profile', 'name' => 'username', 'type' => 'text', 'value' => $UP->username, 'maxlength' => '80', 'disabled' => true, 'mandatory' => true, 'error' =>  (isset($inputAlert['username'])?$inputAlert['username']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'lastname', 'type' => 'text', 'value' => $UP->lastname, 'maxlength' => '80', 'error' =>  (isset($inputAlert['lastname'])?$inputAlert['lastname']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'firstname', 'type' => 'text', 'value' => $UP->firstname, 'maxlength' => '80', 'error' =>  (isset($inputAlert['firstname'])?$inputAlert['firstname']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'title', 'type' => 'text', 'value' => $UO->read($profile, 'title'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['title'])?$inputAlert['title']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'position', 'type' => 'text', 'value' => $UO->read($profile, 'position'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['position'])?$inputAlert['position']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'id', 'type' => 'text', 'value' => $UO->read($profile, 'id'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['id'])?$inputAlert['id']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'gender', 'type' => 'radio', 'values' => array ('male', 'female'), 'value' => $UO->read($profile, 'gender') ),
);

$viewData['contact'] = array (
   array ( 'prefix' => 'profile', 'name' => 'email', 'type' => 'text', 'value' => $UP->email, 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['email'])?$inputAlert['email']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'phone', 'type' => 'text', 'value' => $UO->read($profile, 'phone'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['phone'])?$inputAlert['phone']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'mobilephone', 'type' => 'text', 'value' => $UO->read($profile, 'mobile'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['mobile'])?$inputAlert['mobile']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'facebook', 'type' => 'text', 'value' => $UO->read($profile, 'facebook'), 'maxlength' => '80' ),
   array ( 'prefix' => 'profile', 'name' => 'google', 'type' => 'text', 'value' => $UO->read($profile, 'google'), 'maxlength' => '80' ),
   array ( 'prefix' => 'profile', 'name' => 'linkedin', 'type' => 'text', 'value' => $UO->read($profile, 'linkedin'), 'maxlength' => '80' ),
   array ( 'prefix' => 'profile', 'name' => 'skype', 'type' => 'text', 'value' => $UO->read($profile, 'skype'), 'maxlength' => '80' ),
   array ( 'prefix' => 'profile', 'name' => 'twitter', 'type' => 'text', 'value' => $UO->read($profile, 'twitter'), 'maxlength' => '80' ),
);

$viewData['languageList'][] = array ('val' => "default", 'name' => "Default", 'selected' => ($UO->read($profile, 'language') == "default")?true:false );
foreach ($appLanguages as $appLang)
{
   $viewData['languageList'][] = array ('val' => $appLang, 'name' => proper($appLang), 'selected' => ($UO->read($profile, 'language') == $appLang)?true:false );
}

$viewData['options'] = array ();
if ($C->read('allowUserTheme'))
{
   $viewData['themeList'][] = array ('val' => 'default', 'name' => 'Default', 'selected' => ($UO->read($profile, 'theme') == 'default')?true:false );
   foreach ($appThemes as $appTheme)
   {
      $viewData['themeList'][] = array ('val' => $appTheme, 'name' => proper($appTheme), 'selected' => ($UO->read($profile, 'theme') == $appTheme)?true:false );
   }
   $viewData['options'][] = array ( 'prefix' => 'profile', 'name' => 'theme', 'type' => 'list', 'values' => $viewData['themeList'] );
   $viewData['options'][] = array ( 'prefix' => 'profile', 'name' => 'menuBarInverse', 'type' => 'check', 'values' => '', 'value' => $UO->read($profile, 'menuBarInverse') );
}
$viewData['options'][] = array ( 'prefix' => 'profile', 'name' => 'language', 'type' => 'list', 'values' => $viewData['languageList'] );

$roles = $RO->getAll();
foreach ($roles as $role)
{
   $viewData['roles'][] = array ('val' => $role['id'], 'name' => $role['name'], 'selected' => ($UP->getRole($UP->username) == $role['id'])?true:false );
}
$viewData['account'] = array (
   array ( 'prefix' => 'profile', 'name' => 'role', 'type' => 'list', 'values' => $viewData['roles']),
   array ( 'prefix' => 'profile', 'name' => 'locked', 'type' => 'check', 'values' => '', 'value' => $UP->locked ),
   array ( 'prefix' => 'profile', 'name' => 'onhold', 'type' => 'check', 'values' => '', 'value' => $UP->onhold ),
   array ( 'prefix' => 'profile', 'name' => 'verify', 'type' => 'check', 'values' => '', 'value' => $UP->verify ),
);

$viewData['memberships'][] = array('val' => '0', 'name' => $LANG['none'], 'selected' => !$UG->isGroupMember($viewData['profile']));
foreach ($groups as $group)
{
   $viewData['memberships'][] = array('val' => $group['id'], 'name' => $group['name'], 'selected' => ($UG->isMemberOfGroup($viewData['profile'], $group['id']))?true:false);
}
$viewData['managerships'][] = array('val' => '0', 'name' => $LANG['none'], 'selected' => !$UG->isGroupManager($viewData['profile']));
foreach ($groups as $group)
{
   $viewData['managerships'][] = array('val' => $group['id'], 'name' => $group['name'], 'selected' => ($UG->isGroupManagerOfGroup($viewData['profile'], $group['id']))?true:false);
}
if (isAllowed("groupmemberships")) $disabled = false; else $disabled = true;
$viewData['groups'] = array (
   array ( 'prefix' => 'profile', 'name' => 'memberships', 'type' => 'listmulti', 'values' => $viewData['memberships'], 'disabled' => $disabled ),
   array ( 'prefix' => 'profile', 'name' => 'managerships', 'type' => 'listmulti', 'values' => $viewData['managerships'], 'disabled' => $disabled ),
);

$LANG['profile_password_comment'] .= $LANG['password_rules_'.$C->read('pwdStrength')];
$viewData['password'] = array (
   array ( 'prefix' => 'profile', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'error' =>  (isset($inputAlert['password'])?$inputAlert['password']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'error' =>  (isset($inputAlert['password2'])?$inputAlert['password2']:'') ),
);

$viewData['avatars'] = getFiles($CONF['app_avatar_dir'], NULL, 'is_');

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
