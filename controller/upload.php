<?php
/**
 * upload.php
 * 
 * Upload page controller
 *
 * @category TeamCal Neo 
 * @version 0.7.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
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
$UPL = new Upload();
$UPF = new UploadedFile();
$UF =  new UserFile();

//=============================================================================
//
// VARIABLE DEFAULTS
//
$uplDir = WEBSITE_ROOT . '/' . $CONF['app_upl_dir'];

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
            $UPF->create($UPL->the_file, $UL->username);
            $fileid = $UPF->getId($UPL->the_file);
            $UF->create($UL->username, $fileid);
            
            switch ($_POST['opt_shareWith'])
            {
               case "admin" :
                  $UF->create('admin', $fileid);
                  break;
                      
               case "all" :
                  $users = $U->getAll();
                  foreach ( $users as $user ) $UF->create($user['username'], $fileid);
                  break;
                      
               case "group" :
                  if (isset($_POST['sel_shareWithGroup']))
                  {
                     foreach ( $_POST['sel_shareWithGroup'] as $gto )
                     {
                        $groupusers = $UG->getAllForGroup($gto);
                        foreach ( $groupusers as $groupuser ) {
                           $UF->create($groupuser['username'], $fileid);
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
                           $UF->create($roleuser['username'], $fileid);
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
                     foreach ( $_POST['sel_shareWithUser'] as $uto ) $UF->create($uto, $fileid);
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
               header("Location: index.php?action=upload");
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
               if ($UL->username == 'admin' OR $UL->username == $UPF->getUploader($file))
               {
                  $fileid = $UPF->getId($file);
                  $UPF->delete($file);
                  $UF->deleteFile($fileid);
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
$files = getFiles($CONF['app_upl_dir'], $CONF['uplExtensions'], NULL);
foreach ($files as $file)
{
   if ($UL->username != 'admin')
   {
      $fid = $UPF->getId($file);
      if ($UF->hasAccess($UL->username, $fid)) $viewData['uplFiles'][] = $file;
   }
   else 
   {
      $viewData['uplFiles'][] = $file;
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
