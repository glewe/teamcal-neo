<?php
/**
 * useredit.php
 * 
 * User edit page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.00
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

/**
 * ========================================================================
 * Check URL params
 */
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
   /**
    * URL param fail
    */
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}
else
{
   /**
    * ========================================================================
    * Check if allowed
    */
   $allowed = FALSE;
   if ($UL->username == $profile OR isAllowed($controller))
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
}

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */
$UPL = new Upload();
$UPL->upload_dir = WEBSITE_ROOT . '/' . $CONF['app_avatar_dir'];
$UPL->extensions = array ( '.gif', '.jpg', '.png' );
$inputAlert = array();

/**
 * ========================================================================
 * Process form
 */
if (!empty($_POST))
{
   /**
    * Sanitize input
    */
   $_POST = sanitize($_POST);
    
   /**
    * Form validation
    */
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
      /**
       * ,--------,
       * | Update |
       * '--------'
       */
      if (isset($_POST['btn_profileUpdate']))
      {
         $reloadPage = false;
         /**
          * Personal
          */
         $UP->lastname = $_POST['txt_lastname'];
         $UP->firstname = $_POST['txt_firstname'];
         $UO->save($profile, 'title', $_POST['txt_title']);
         $UO->save($profile, 'position', $_POST['txt_position']);
         $UO->save($profile, 'id', $_POST['txt_id']);
         if (isset($_POST['opt_gender'])) $UO->save($profile, 'gender', $_POST['opt_gender']); else $UO->save($profile, 'gender', 'male');
          
         /**
          * Contact
          */
         $UP->email = $_POST['txt_email'];
         $UO->save($profile, 'phone', $_POST['txt_phone']);
         $UO->save($profile, 'mobile', $_POST['txt_mobilephone']);
         $UO->save($profile, 'facebook', $_POST['txt_facebook']);
         $UO->save($profile, 'google', $_POST['txt_google']);
         $UO->save($profile, 'linkedin', $_POST['txt_linkedin']);
         $UO->save($profile, 'skype', $_POST['txt_skype']);
         $UO->save($profile, 'twitter', $_POST['txt_twitter']);
          
         /**
          * Options
          */
         if (isset($_POST['sel_theme'])) {
            if ($_POST['sel_theme'] != $UO->read($profile,'theme')) $reloadPage = true; // New theme needs a page reload later
            $UO->save($profile, "theme", $_POST['sel_theme']); 
         }
         else 
         {
            $UO->save($profile, 'language', 'bootstrap');
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
         
         /**
          * Account
          */
         if (isAllowed("useraccount")) 
         {
            if (isset($_POST['sel_role'])) $UP->role = $_POST['sel_role']; else $UP->role = '2';
            if (isset($_POST['chk_locked']) AND $_POST['chk_locked']) $UP->locked = '1'; else $UP->locked = '0';
            if (isset($_POST['chk_hidden']) AND $_POST['chk_hidden']) $UP->hidden = '1'; else $UP->hidden = '0';
            if (isset($_POST['chk_onhold']) AND $_POST['chk_onhold']) $UP->onhold = '1'; else $UP->onhold = '0';
            if (isset($_POST['chk_verify']) AND $_POST['chk_verify']) $UP->verify = '1'; else $UP->verify = '0';
         }
          
         /**
          * Password
          */
         if ( isset($_POST['txt_password']) AND strlen($_POST['txt_password']) AND isset($_POST['txt_password2']) AND strlen($_POST['txt_password2']) AND $_POST['txt_password'] == $_POST['txt_password2'] )
         {
            $UP->password = crypt($_POST['txt_password'], $CONF['salt']);
            $UP->last_pw_change = date("Y-m-d H:I:s");
         }
          
         /**
          * Groups
          */
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
          
         /**
          * Avatar
          */
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
          
         /**
          * Send notification e-mails to the subscribers of user events
          */
         if ($C->read("emailNotifications"))
         {
            sendUserEventNotifications("changed", $UP->username, $UP->firstname, $UP->lastname);
         }
          
         /**
          * Log this event
          */
         $LOG->log("logUser",$L->checkLogin(),"log_user_updated", $UP->username);
          
         /**
          * Reload page in case of language change, so it takes effect.
          */
         if ($reloadPage)
         {
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller."&profile=" . $profile);
            die();
         }
         
         /**
          * Success
          */
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['profile_alert_update'];
         $alertData['text'] = $LANG['profile_alert_update_success'];
         $alertData['help'] = '';
      }
      /**
       * ,--------,
       * | Upload |
       * '--------'
       */
      else if (isset ($_POST['btn_uploadAvatar']))
      {
         $UPL->do_filename_check = "y";
         $UPL->replace = "y";
         $UPL->the_temp_file = $_FILES['file_avatar']['tmp_name'];
         $UPL->the_file = $_FILES['file_avatar']['name'];
         $UPL->http_error = $_FILES['file_avatar']['error'];
         if ($UPL->upload())
         {
            $full_path = $UPL->upload_dir . $UPL->file_copy;
            $info = $UPL->getUploadedFileInfo($full_path);
            $UO->save($profile, 'avatar', $UPL->uploaded_file['name']);
         }
         else
         {
            /**
             * Upload failed
             */
            $showAlert = TRUE;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = 'Avatar ' . $LANG['btn_upload'];
            $alertData['text'] = $UPL->getErrors();
            $alertData['help'] = '';
         }
      }
      /**
       * ,--------------,
       * | Reset Avatar |
       * '--------------'
       */
      else if (isset ($_POST['btn_deleteAvatar']))
      {
         $file = $UPL->upload_dir . $UO->read($profile, 'avatar');
         if(is_file("$file")) unlink("$file");
         $UO->save($profile, 'avatar', '0');
      }
   }
   else
   {
      /**
       * Input validation failed
       */
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['profile_alert_save_failed'];
      $alertData['help'] = '';
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$profileData['profile'] = $profile;
$profileData['fullname'] = $UP->firstname . ' ' . $UP->lastname . ' (' . $UP->username . ')';
$profileData['avatar'] = ($UO->read($profile, 'avatar')) ? $UO->read($profile, 'avatar') : 'noavatar_' . $UO->read($profile, 'gender') . '.png';
$profileData['avatar_maxsize'] = 1024 * 100; // 100 KB
$profileData['avatar_formats'] = implode(', ', $UPL->extensions);
$profileData['showingroups'] = $UO->read($profile, 'showingroups');
$profileData['notifycalgroup'] = $UO->read($profile, 'notifycalgroup');

$groups = $G->getAll();

$profileData['personal'] = array (
   array ( 'prefix' => 'profile', 'name' => 'username', 'type' => 'text', 'value' => $UP->username, 'maxlength' => '80', 'disabled' => true, 'mandatory' => true, 'error' =>  (isset($inputAlert['username'])?$inputAlert['username']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'lastname', 'type' => 'text', 'value' => $UP->lastname, 'maxlength' => '80', 'error' =>  (isset($inputAlert['lastname'])?$inputAlert['lastname']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'firstname', 'type' => 'text', 'value' => $UP->firstname, 'maxlength' => '80', 'error' =>  (isset($inputAlert['firstname'])?$inputAlert['firstname']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'title', 'type' => 'text', 'value' => $UO->read($profile, 'title'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['title'])?$inputAlert['title']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'position', 'type' => 'text', 'value' => $UO->read($profile, 'position'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['position'])?$inputAlert['position']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'id', 'type' => 'text', 'value' => $UO->read($profile, 'id'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['id'])?$inputAlert['id']:'') ), 
   array ( 'prefix' => 'profile', 'name' => 'gender', 'type' => 'radio', 'values' => array ('male', 'female'), 'value' => $UO->read($profile, 'gender') ),
);

$profileData['contact'] = array (
   array ( 'prefix' => 'profile', 'name' => 'email', 'type' => 'text', 'value' => $UP->email, 'maxlength' => '80', 'mandatory' => true, 'error' =>  (isset($inputAlert['email'])?$inputAlert['email']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'phone', 'type' => 'text', 'value' => $UO->read($profile, 'phone'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['phone'])?$inputAlert['phone']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'mobilephone', 'type' => 'text', 'value' => $UO->read($profile, 'mobile'), 'maxlength' => '80', 'error' =>  (isset($inputAlert['mobile'])?$inputAlert['mobile']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'facebook', 'type' => 'text', 'value' => $UO->read($profile, 'facebook'), 'maxlength' => '80' ),
   array ( 'prefix' => 'profile', 'name' => 'google', 'type' => 'text', 'value' => $UO->read($profile, 'google'), 'maxlength' => '80' ),
   array ( 'prefix' => 'profile', 'name' => 'linkedin', 'type' => 'text', 'value' => $UO->read($profile, 'linkedin'), 'maxlength' => '80' ),
   array ( 'prefix' => 'profile', 'name' => 'skype', 'type' => 'text', 'value' => $UO->read($profile, 'skype'), 'maxlength' => '80' ),
   array ( 'prefix' => 'profile', 'name' => 'twitter', 'type' => 'text', 'value' => $UO->read($profile, 'twitter'), 'maxlength' => '80' ),
);

$profileData['languageList'][] = array ('val' => "default", 'name' => "Default", 'selected' => ($UO->read($profile, 'language') == "default")?true:false );
foreach ($appLanguages as $appLang)
{
   $profileData['languageList'][] = array ('val' => $appLang, 'name' => proper($appLang), 'selected' => ($UO->read($profile, 'language') == $appLang)?true:false );
}

$profileData['options'] = array ();
if ($C->read('allowUserTheme'))
{
   $profileData['themeList'][] = array ('val' => 'default', 'name' => 'Default', 'selected' => ($UO->read($profile, 'theme') == 'default')?true:false );
   foreach ($appThemes as $appTheme)
   {
      $profileData['themeList'][] = array ('val' => $appTheme, 'name' => proper($appTheme), 'selected' => ($UO->read($profile, 'theme') == $appTheme)?true:false );
   }
   $profileData['options'][] = array ( 'prefix' => 'profile', 'name' => 'theme', 'type' => 'list', 'values' => $profileData['themeList'] );
}
$profileData['options'][] = array ( 'prefix' => 'profile', 'name' => 'language', 'type' => 'list', 'values' => $profileData['languageList'] );

$roles = $RO->getAll();
foreach ($roles as $role)
{
   $profileData['roles'][] = array ('val' => $role['id'], 'name' => $role['name'], 'selected' => ($UP->getRole($UP->username) == $role['id'])?true:false );
}
$profileData['account'] = array (
   array ( 'prefix' => 'profile', 'name' => 'role', 'type' => 'list', 'values' => $profileData['roles']),
   array ( 'prefix' => 'profile', 'name' => 'locked', 'type' => 'check', 'values' => '', 'value' => $UP->locked ),
   array ( 'prefix' => 'profile', 'name' => 'onhold', 'type' => 'check', 'values' => '', 'value' => $UP->onhold ),
   array ( 'prefix' => 'profile', 'name' => 'verify', 'type' => 'check', 'values' => '', 'value' => $UP->verify ),
);

$profileData['memberships'][] = array('val' => '0', 'name' => $LANG['none'], 'selected' => !$UG->isGroupMember($profileData['profile']));
foreach ($groups as $group)
{
   $profileData['memberships'][] = array('val' => $group['id'], 'name' => $group['name'], 'selected' => ($UG->isMemberOfGroup($profileData['profile'], $group['id']))?true:false);
}
$profileData['managerships'][] = array('val' => '0', 'name' => $LANG['none'], 'selected' => !$UG->isGroupManager($profileData['profile']));
foreach ($groups as $group)
{
   $profileData['managerships'][] = array('val' => $group['id'], 'name' => $group['name'], 'selected' => ($UG->isGroupManagerOfGroup($profileData['profile'], $group['id']))?true:false);
}
if (isAllowed("groupmemberships")) $disabled = false; else $disabled = true;
$profileData['groups'] = array (
   array ( 'prefix' => 'profile', 'name' => 'memberships', 'type' => 'listmulti', 'values' => $profileData['memberships'], 'disabled' => $disabled ),
   array ( 'prefix' => 'profile', 'name' => 'managerships', 'type' => 'listmulti', 'values' => $profileData['managerships'], 'disabled' => $disabled ),
);

$LANG['profile_password_comment'] .= $LANG['password_rules_'.$C->read('pwdStrength')];
$profileData['password'] = array (
   array ( 'prefix' => 'profile', 'name' => 'password', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'error' =>  (isset($inputAlert['password'])?$inputAlert['password']:'') ),
   array ( 'prefix' => 'profile', 'name' => 'password2', 'type' => 'password', 'value' => '', 'maxlength' => '50', 'error' =>  (isset($inputAlert['password2'])?$inputAlert['password2']:'') ),
);

$profileData['avatars'] = getFiles($CONF['app_avatar_dir'], NULL, 'is_');

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
