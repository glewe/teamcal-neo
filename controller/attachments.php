<?php
/**
 * attachments.php
 * 
 * Attachments page controller
 *
 * @category TeamCal Neo 
 * @version 1.5.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
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
$UPL = new Upload();
$AT = new Attachment();
$UAT =  new UserAttachment();

//=============================================================================
//
// VARIABLE DEFAULTS
//
$uplDir = WEBSITE_ROOT . '/' . APP_UPL_DIR;

$viewData = array();
$viewData['shareWith'] = 'all';
$viewData['shareWithGroup'] = array ();
$viewData['shareWithRole'] = array ();
$viewData['shareWithUser'] = array ();
$viewData['uplFiles'] = array ();

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
      // ,-------------,
      // | Upload File |
      // '-------------'
      if (isset($_POST['btn_uploadFile']))
      {
         $UPL->upload_dir = $uplDir;
         $UPL->extensions = $CONF['uplExtensions'];
         $UPL->do_filename_check = "y";
         $UPL->replace = "y";
         $UPL->the_temp_file = $_FILES['file_image']['tmp_name'];
         $UPL->the_file = $_FILES['file_image']['name'];
         $UPL->http_error = $_FILES['file_image']['error'];
         
         if ($UPL->upload())
         {
            $AT->create($UPL->the_file, $UL->username);
            $fileid = $AT->getId($UPL->the_file);
            $UAT->create($UL->username, $fileid);
            
            switch ($_POST['opt_shareWith'])
            {
               case "admin" :
                  $UAT->create('admin', $fileid);
                  break;
                      
               case "all" :
                  $users = $U->getAll();
                  foreach ( $users as $user ) $UAT->create($user['username'], $fileid);
                  break;
                      
               case "group" :
                  if (isset($_POST['sel_shareWithGroup']))
                  {
                     foreach ( $_POST['sel_shareWithGroup'] as $gto )
                     {
                        $groupusers = $UG->getAllForGroup($gto);
                        foreach ( $groupusers as $groupuser ) {
                           $UAT->create($groupuser['username'], $fileid);
                        }
                     }
                  }
                  else
                  {
                     //
                     // No group selected
                     //
                     $showAlert = TRUE;
                     $alertData['type'] = 'warning';
                     $alertData['title'] = $LANG['alert_warning_title'];
                     $alertData['subject'] = $LANG['msg_no_group_subject'];
                     $alertData['text'] = $LANG['msg_no_group_text'];
                     $alertData['help'] = '';
                  }
                  break;
                   
               case "role" :
                  if (isset($_POST['sel_shareWithRole']))
                  {
                     foreach ( $_POST['sel_shareWithRole'] as $rto )
                     {
                        $roleusers = $U->getAllForRole($rto);
                        foreach ( $roleusers as $roleuser ) {
                           $UAT->create($roleuser['username'], $fileid);
                        }
                     }
                  }
                  else
                  {
                     //
                     // No group selected
                     //
                     $showAlert = TRUE;
                     $alertData['type'] = 'warning';
                     $alertData['title'] = $LANG['alert_warning_title'];
                     $alertData['subject'] = $LANG['msg_no_group_subject'];
                     $alertData['text'] = $LANG['msg_no_group_text'];
                     $alertData['help'] = '';
                  }
                  break;
                   
               case "user" :
                  if (isset($_POST['sel_shareWithUser']))
                  {
                     foreach ( $_POST['sel_shareWithUser'] as $uto ) $UAT->create($uto, $fileid);
                  }
                  else
                  {
                     //
                     // No user selected
                     //
                     $showAlert = TRUE;
                     $alertData['type'] = 'warning';
                     $alertData['title'] = $LANG['alert_warning_title'];
                     $alertData['subject'] = $LANG['msg_no_user_subject'];
                     $alertData['text'] = $LANG['msg_no_user_text'];
                     $alertData['help'] = '';
                  }
                  break;
            }
            
            if (!$showAlert)
            {
               //
               // Log this event
               //
               $LOG->log("logUpload", $UL->username, "log_upload_image", $UPL->uploaded_file['name']);
               header("Location: index.php?action=".$controller);
            }
         }
         else
         {
            //
            // Upload failed
            //
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['alert_upl_img_subject'];
            $alertData['text'] = $UPL->getErrors();
            $alertData['help'] = '';
         }
      }
      // ,-------------,
      // | Delete File |
      // '-------------'
      elseif (isset($_POST['btn_deleteFile']))
      {
         if (isset($_POST['chk_file']))
         {
            $selected_files = $_POST['chk_file'];
            foreach($selected_files as $file)
            {
               if ($UL->username == 'admin' OR $UL->username == $AT->getUploader($file))
               {
                  $fileid = $AT->getId($file);
                  $AT->delete($file);
                  $UAT->deleteFile($fileid);
                  unlink($uplDir . $file);
               }
            }
         }
         else
         {
            //
            // No file selected
            //
            $showAlert = true;
            $alertData['type'] = 'warning';
            $alertData['title'] = $LANG['alert_warning_title'];
            $alertData['subject'] = $LANG['msg_no_file_subject'];
            $alertData['text'] = $LANG['msg_no_file_text'];
            $alertData['help'] = '';
         }
      }
      // ,---------------,
      // | Update Shares |
      // '---------------'
      $files = $AT->getAll();
      foreach ($files as $file)
      {
         if (isset($_POST['btn_updateShares'.$file['id']]))
         {
            $UAT->deleteFile($file['id']);
            if (isset($_POST['sel_shares'.$file['id']]))
            {
               foreach ( $_POST['sel_shares'.$file['id']] as $uto ) $UAT->create($uto, $file['id']);
            }
         }
         else if (isset($_POST['btn_clearShares'.$file['id']]))
         {
            $UAT->deleteFile($file['id']);
         }
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
$viewData['upl_maxsize'] = $CONF['uplMaxsize'];
$viewData['upl_formats'] = implode(', ', $CONF['uplExtensions']);
$files = getFiles(APP_UPL_DIR, $CONF['uplExtensions'], NULL);
foreach ($files as $file)
{
   $fid = $AT->getId($file);
   if ($UL->username != 'admin')
   {
      if ($UAT->hasAccess($UL->username, $fid)) $viewData['uplFiles'][] = array('fid' => $fid, 'fname' => $file);
   }
   else 
   {
      $viewData['uplFiles'][] = array('fid' => $fid, 'fname' => $file);
   }
}
$viewData['groups'] = $G->getAll();
$viewData['roles'] = $RO->getAll();
$viewData['users'] = $U->getAll();

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
