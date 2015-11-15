<?php
/**
 * database.php
 * 
 * Database page controller
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
 * Check if allowed
 */
if (!isAllowed($controller))
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
$inputAlert = array();
$dbData['year'] = '';
$dbData['month'] = '';

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
   if (isset($_POST['btn_delete']))
   {
      if (!formInputValid('txt_deleteConfirm', 'required|alpha|equals_string', 'DELETE')) $inputError = true;
   }
    
   if (!$inputError)
   {
      /**
       * ,--------,
       * | Delete |
       * '--------'
       */
      if ( isset($_POST['btn_delete']) )
      {
         if ( isset($_POST['chk_delUsers']) )
         {
            /**
             * Delete Users (all but admin)
             * Delete User options (all but admin)
             * Delete Daynotes
             * Delete Templates
             * Delete Allowances
             */
            $result = $U->deleteAll();
            $result = $UO->deleteAll();
            
            /**
             * Log this event
             */
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_users");
         }
         
         if ( isset($_POST['chk_delGroups']) )
         {
            /**
             * Delete Groups
             * Delete User-Group assignments
             */
            $result = $G->deleteAll();
            $result = $UG->deleteAll();
            
            /**
             * Log this event
             */
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_groups");
         }
         
         if ( isset($_POST['chk_delMessages']) )
         {
            /**
             * Delete Messages and all User-Message assignments
             */
            $result = $MSG->deleteAll();
            $result = $UMSG->deleteAll();
            
            /**
             * Log this event
            */
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_msg");
         }
          
         if ( isset($_POST['chk_delOrphMessages']) )
         {
            /**
             * Delete orphaned announcements
             */
            deleteOrphanedMessages();
             
            /**
             * Log this event
             */
            $LOG->log("logMessages",$L->checkLogin(),"log_db_delete_msg_orph");
         }
          
         if ( isset($_POST['chk_delPermissions']) )
         {
            $P->deleteAll();
            
            /**
             * Log this event
             */
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_perm");
         }
          
         if ( isset($_POST['chk_delLog']) )
         {
            $LOG->deleteAll();
             
            /**
             * Log this event
             */
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_log");
         }
         
         if ( isset($_POST['chkDBDeleteArchive']) )
         {
            /**
             * Delete archive records
             */
            $U->deleteAll(TRUE);
            $UG->deleteAll(TRUE);
            $UO->deleteAll(TRUE);
            $UMSG->deleteAll(TRUE);
             
            /**
             * Log this event
             */
            $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_archive");
         }
         /**
          * Success
          */
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['db_alert_delete'];
         $alertData['text'] = $LANG['db_alert_delete_success'];
         $alertData['help'] = '';
      }
      /**
       * ,--------,
       * | Export |
       * '--------'
       */
      else if ( isset($_POST['btn_export']) )
      {
         switch ($_POST['opt_expFormat'])
         {
            case 'csv': $format="csv"; break;
            case 'sql': $format="sql"; break;
            case 'xml': $format="xml"; break;
            default:    $format="sql"; break;
         }
         
         switch ($_POST['opt_expOutput'])
         {
            case 'browser': $type="browser"; break;
            case 'file':    $type="download"; break;
            default:        $type=""; break;
         }
         
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_export", "$format | $what | $type");
         header('Location: index.php?action=export&what=all&format='.$format.'&type='.$type);
      }
      /**
       * ,----------,
       * | Optimize |
       * '----------'
       */
      else if ( isset($_POST['btn_optimize']) )
      {
         /**
          * Optimize tables
          */
         $DB->optimizeTables();
         
         /**
          * Success
          */
         $showAlert = TRUE;
         $alertData['type'] = 'success';
         $alertData['title'] = $LANG['alert_success_title'];
         $alertData['subject'] = $LANG['db_alert_optimize'];
         $alertData['text'] = $LANG['db_alert_optimize_success'];
         $alertData['help'] = '';
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
      $alertData['text'] = $LANG['db_alert_failed'];
      $alertData['help'] = '';
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
