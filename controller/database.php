<?php
/**
 * database.php
 * 
 * Database page controller
 *
 * @category TeamCal Neo 
 * @version 1.3.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
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
$inputAlert = array();
$viewData['cleanBefore'] = '';


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
   if (isset($_POST['btn_delete']))
   {
      if (!formInputValid('txt_deleteConfirm', 'required|alpha|equals_string', 'DELETE')) $inputError = true;
   }
   
   if (isset($_POST['btn_cleanup']))
   {
      if (!formInputValid('txt_cleanBefore', 'required|date')) $inputError = true;
      if (!formInputValid('txt_cleanConfirm', 'required|alpha|equals_string', 'CLEANUP')) $inputError = true;
      $viewData['cleanBefore'] = $_POST['txt_cleanBefore'];
   }
    
   if (!$inputError)
   {
      // ,---------,
      // | Cleanup |
      // '---------'
      if ( isset($_POST['btn_cleanup']) )
      {
         if ( isset($_POST['chk_cleanDaynotes']) )
         {
            $result = $D->deleteAllBefore(str_replace('-','',$_POST['txt_cleanBefore']));
         }
         
         if ( isset($_POST['chk_cleanMonths']) )
         {
            $result = $M->deleteBefore(substr($_POST['txt_cleanBefore'],0,4), substr($_POST['txt_cleanBefore'],4,2));
         }

         if ( isset($_POST['chk_cleanTemplates']) )
         {
            $result = $T->deleteBefore(substr($_POST['txt_cleanBefore'],0,4), substr($_POST['txt_cleanBefore'],4,2));
         }
          
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['db_alert_delete'];
         $alertData['text'] = $LANG['db_alert_delete_success'];
         $alertData['help'] = '';
      }
      // ,--------,
      // | Delete |
      // '--------'
      else if ( isset($_POST['btn_delete']) )
      {
         if ( isset($_POST['chk_delUsers']) )
         {
            //
            // Delete Users (all but admin)
            // Delete User options (all but admin)
            // Delete Daynotes
            // Delete Templates
            // Delete Allowances
            //
            $result = $U->deleteAll();
            $result = $UO->deleteAll();
            $result = $D->deleteAll();
            $result = $T->deleteAll();
            $result = $AL->deleteAll();
            
            //
            // Log this event
            //
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_users");
         }
         
         if ( isset($_POST['chk_delGroups']) )
         {
            //
            // Delete Groups
            // Delete User-Group assignments
            //
            $result = $G->deleteAll();
            $result = $UG->deleteAll();
            
            //
            // Log this event
            //
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_groups");
         }
         
         if ( isset($_POST['chk_delMessages']) )
         {
            //
            // Delete Messages and all User-Message assignments
            //
            $result = $MSG->deleteAll();
            $result = $UMSG->deleteAll();
            
            //
            // Log this event
           //
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_msg");
         }
          
         if ( isset($_POST['chk_delOrphMessages']) )
         {
            //
            // Delete orphaned announcements
            //
            deleteOrphanedMessages();
             
            //
            // Log this event
            //
            $LOG->log("logMessages",$L->checkLogin(),"log_db_delete_msg_orph");
         }
          
         if ( isset($_POST['chk_delPermissions']) )
         {
            $P->deleteAll();
            
            //
            // Log this event
            //
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_perm");
         }
          
         if ( isset($_POST['chk_delLog']) )
         {
            $LOG->deleteAll();
             
            //
            // Log this event
            //
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_log");
         }
         
         if ( isset($_POST['chkDBDeleteArchive']) )
         {
            //
            // Delete archive records
            //
            $U->deleteAll(TRUE);
            $UG->deleteAll(TRUE);
            $UO->deleteAll(TRUE);
            $UMSG->deleteAll(TRUE);
             
            //
            // Log this event
            //
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_archive");
         }
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['db_alert_delete'];
         $alertData['text'] = $LANG['db_alert_delete_success'];
         $alertData['help'] = '';
      }
      // ,----------,
      // | Optimize |
      // '----------'
      else if ( isset($_POST['btn_optimize']) )
      {
         //
         // Optimize tables
         //
         $DB->optimizeTables();
         
         //
         // Success
         //
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['db_alert_optimize'];
         $alertData['text'] = $LANG['db_alert_optimize_success'];
         $alertData['help'] = '';
      }
      // ,----------,
      // | Save URL |
      // '----------'
      else if ( isset($_POST['btn_saveURL']) )
      {
         if (filter_var($_POST['txt_dbURL'], FILTER_VALIDATE_URL)) 
         {
            $C->save("dbURL",$_POST['txt_dbURL']); 
            
            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['db_alert_url'];
            $alertData['text'] = $LANG['db_alert_url_success'];
            $alertData['help'] = '';
         }
         else 
         {
            //
            // Fail
            //
            $showAlert = TRUE;
            $alertData['type'] = 'warning';
            $alertData['title'] = $LANG['alert_warning_title'];
            $alertData['subject'] = $LANG['db_alert_url'];
            $alertData['text'] = $LANG['db_alert_url_fail'];
            $alertData['help'] = '';
            $C->save("dbURL","#");
         }
      }
      // ,----------,
      // | Reset DB |
      // '----------'
      else if ( isset($_POST['btn_reset']) AND $_POST['txt_dbResetString'] == "YesIAmSure" )
      {
         $query = file_get_contents("sql/sample.sql");
         
         //
         // Replace prefix in sample file
         //
         if (strlen($CONF['db_table_prefix']))
            $query = str_replace("leaf_",$CONF['db_table_prefix'],$query);
         else
            $query = str_replace("leaf_","",$query);
         
         //
         // Run query
         //
         $result = $DB->runQuery($query);
            
         if ($result) 
         {
            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['db_alert_reset'];
            $alertData['text'] = $LANG['db_alert_reset_success'];
            $alertData['help'] = '';
         }
         else 
         {
            //
            // Fail
            //
            $showAlert = TRUE;
            $alertData['type'] = 'warning';
            $alertData['title'] = $LANG['alert_warning_title'];
            $alertData['subject'] = $LANG['db_alert_reset'];
            $alertData['text'] = $LANG['db_alert_reset_fail'];
            $alertData['help'] = '';
            $C->save("dbURL","#");
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
      $alertData['text'] = $LANG['db_alert_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['dbURL'] = $C->read('dbURL');

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
