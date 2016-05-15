<?php
/**
 * upload.php
 * 
 * Upload page controller
 *
 * @category TeamCal Neo 
 * @version 0.6.000
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

//=============================================================================
//
// VARIABLE DEFAULTS
//
$viewData = array();
$imgDir = WEBSITE_ROOT . '/' . $CONF['app_image_dir'];
$docDir = WEBSITE_ROOT . '/' . $CONF['app_doc_dir'];

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
      // ,--------------,
      // | Upload Image |
      // '--------------'
      if (isset($_POST['btn_uploadImage']))
      {
         $UPL = new Upload();
         $UPL->upload_dir = $imgDir;
         $UPL->extensions = $CONF['imgExtensions'];
         $UPL->do_filename_check = "y";
         $UPL->replace = "y";
         $UPL->the_temp_file = $_FILES['file_image']['tmp_name'];
         $UPL->the_file = $_FILES['file_image']['name'];
         $UPL->http_error = $_FILES['file_image']['error'];
         
         if ($UPL->upload())
         {
            //
            // Log this event
            //
            $LOG->log("logUpload", $UL->username, "log_upload_image", $UPL->uploaded_file['name']);
            header("Location: index.php?action=upload");
         }
         else
         {
            //
            // Upload failed
            //
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['btn_upload'];
            $alertData['text'] = $UPL->getErrors();
            $alertData['help'] = '';
         }
      }
      // ,-----------------,
      // | Upload Document |
      // '-----------------'
      elseif (isset($_POST['btn_uploadDoc']))
      {
         $UPL = new Upload();
         $UPL->upload_dir = $docDir;
         $UPL->extensions = $CONF['docExtensions'];
         $UPL->do_filename_check = "y";
         $UPL->replace = "y";
         $UPL->the_temp_file = $_FILES['file_doc']['tmp_name'];
         $UPL->the_file = $_FILES['file_doc']['name'];
         $UPL->http_error = $_FILES['file_doc']['error'];
         
         if ($UPL->upload())
         {
            //
            // Log this event
            //
            $LOG->log("logUpload", $UL->username, "log_upload_image", $UPL->uploaded_file['name']);
            header("Location: index.php?action=upload");
         }
         else
         {
            //
            // Upload failed
            //
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['btn_upload'];
            $alertData['text'] = $UPL->getErrors();
            $alertData['help'] = '';
         }
      }
      // ,--------------,
      // | Delete Image |
      // '--------------'
      elseif (isset($_POST['btn_deleteImage']))
      {
         if (isset($_POST['chk_image']))
         {
            $selected_files = $_POST['chk_image'];
            foreach($selected_files as $file)
            {
               unlink($imgDir . $file);
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
      // ,-----------------,
      // | Delete Document |
      // '-----------------'
      elseif (isset($_POST['btn_deleteDoc']))
      {
         if (isset($_POST['chk_doc']))
         {
            $selected_files = $_POST['chk_doc'];
            foreach($selected_files as $file)
            {
               unlink($docDir . $file);
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
$viewData['image_maxsize'] = $CONF['imgMaxsize'];
$viewData['image_formats'] = implode(', ', $CONF['imgExtensions']);
$viewData['imageFiles'] = getFiles($CONF['app_image_dir'], $CONF['imgExtensions'], NULL);

$viewData['doc_maxsize'] = $CONF['docMaxsize'];
$viewData['doc_formats'] = implode(', ', $CONF['docExtensions']);
$viewData['docFiles'] = getFiles($CONF['app_doc_dir'], $CONF['docExtensions'], NULL);

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
