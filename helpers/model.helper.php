<?php
/**
 * model.helper.php
 *
 * Collection of model related functions
 *
 * @category TeamCal Neo 
 * @version 0.9.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

// ---------------------------------------------------------------------------
/**
 * Archives a user and all related records
 *
 * @param string $username Username to archive
 */
function archiveUser($username)
{
   global $CONF, $AL, $D, $G, $L, $LOG, $T, $U, $UMSG, $UG, $UO;
   
   /**
    * Do not archive if username exists in any of the archive table
    */
   if ($U->exists($username, TRUE)) return FALSE;
   if ($UG->exists($username, TRUE)) return FALSE;
   if ($UO->exists($username, TRUE)) return FALSE;
   if ($T->exists($username, TRUE)) return FALSE;
   if ($D->exists($username, TRUE)) return FALSE;
   if ($AL->exists($username, TRUE)) return FALSE;
   if ($UMSG->exists($username, TRUE)) return FALSE;
   
   /**
    * Get fullname for log
    */
   $U->findByName($username);
   $fullname = trim($U->firstname . " " . $U->lastname);
   
   /**
    * Archive user
    * Archive memberships
    * Archive options
    * Archive templates
    * Archive daynotes
    * Archive allowances
    * Archive messages
    */
   $U->archive($username);
   $UG->archive($username);
   $UO->archive($username);
   $T->archive($username);
   $D->archive($username);
   $AL->archive($username);
   $UMSG->archive($username);
   
   /**
    * Delete user from active tables
    */
   deleteUser($username, false, false);
   
   /**
    * Log this event
    */
   $LOG->log("logUser", $L->checkLogin(), "log_user_archived", $fullname . " (" . $username . ")");
   
   return true;
}

// ---------------------------------------------------------------------------
/**
 * Deletes all orphaned announcements, meaning those announcements that are
 * not assigned to any user.
 */
function deleteOrphanedMessages()
{
   global $MSG, $UMSG;
   
   $messages = $MSG->getAll();
   foreach ( $messages as $msg )
   {
      if (!count($UMSG->getAllByMsgId($msg['id']))) $MSG->delete($msg['id']);
   }
}

// ---------------------------------------------------------------------------
/**
 * Deletes a user and all related records
 *
 * @param string $deluser User to delete
 * @param boolean $fromArchive Flag whether to delete from archive tables 
 * @param boolean $sendNotifications Flag whether to send notifications 
 */
function deleteUser($username, $fromArchive = FALSE, $sendNotifications = true)
{
   global $AV, $CONF, $L, $LOG, $T, $U, $UMSG, $UG, $UO;
   
   /**
    * Get fullname for log
    */
   $U->findByName($username);
   $fullname = trim($U->firstname . " " . $U->lastname);
   
   /**
    * Delete user
    * Delete memberships
    * Delete options
    * Delete messages
    * Delete avatars
    * Delete month templates
    */
   $U->deleteByName($username, $fromArchive);
   $UG->deleteByUser($username, $fromArchive);
   $UO->deleteByUser($username, $fromArchive);
   $UMSG->deleteByUser($username, $fromArchive);
   if ($fromArchive) $AV->delete($username, $UO->read($username, 'avatar'));
   $T->deleteByUser($username, $fromArchive);
    
   /**
    * Send notification e-mails
    */
   sendUserEventNotifications("deleted", $username, $U->firstname, $U->lastname);
    
   /**
    * Log this event
    */
   if ($fromArchive) $LOG->log("logUser", $L->checkLogin(), "log_user_archived_deleted", $fullname . " (" . $username . ")");
   else $LOG->log("logUser", $L->checkLogin(), "log_user_deleted", $fullname . " (" . $username . ")");
}

// ---------------------------------------------------------------------
/**
 * Imports users from a CSV file into the database
 *
 * @param string $defgroup Default user group to assign the imported users to
 * @param boolean $lock Flag indicating whether to lock the user accounts or not
 * @param boolean $hide Flag indicating whether to hide the user accounts or not
 * 
 * @return boolean Success flag
 */
function importUsersFromCSV($defgroup, $lock = true, $hide = true)
{
   /**
    * The expected columns are:
    * 0        1         2        3     4        5     6      7     8
    * username|firstname|lastname|title|position|phone|mobile|email|idnumber
    */
   global $CONF,$LANG, $L, $LOG;
   $UI = new User;
   $UGI = new UserGroup;
   $UOI = new UserOption;
   
   $result = true;
   $fpointer = fopen($this->file_name, "r");
   
   if ($fpointer)
   {
      while ( $arr = fgetcsv($fpointer, 10 * 1024, ";") )
      {
         if (is_array($arr) && !empty($arr))
         {
            if (count($arr) != 11)
            {
               $this->error = $LANG['uimp_err_col_1'] . $arr[0] . $LANG['uimp_err_col_2'] . count($arr) . $LANG['uimp_err_col_3'];
               unset($arr);
               fclose($fpointer);
               $result = false;
               return;
            }
            else
            {
               if (!$UI->findByName(trim($arr[0])) and $arr[0] != "admin" and preg_match('/^[a-zA-Z0-9]*$/', $arr[0]))
               {
                  $UI->username = trim($arr[0]);
                  $UI->password = crypt("password", $CONF['salt']);
                  $UI->firstname = $arr[1];
                  $UI->lastname = $arr[2];
                  $UI->title = $arr[3];
                  $UI->position = $arr[4];
                  $UI->phone = $arr[5];
                  $UI->mobile = $arr[6];
                  $UI->email = $arr[7];
                  $UI->idnumber = $arr[8];
                  $UI->birthday = $arr[9];
                  $UI->clearUserType($CONF['UTADMIN']);
                  $UI->clearUserType($CONF['UTDIRECTOR']);
                  $UI->setUserType($CONF['UTMALE']);
                  $UI->setUserType($CONF['UTUSER']);
                  $UI->clearStatus($CONF['USLOCKED']);
                  $UI->clearStatus($CONF['USLOGLOC']);
                  $UI->clearStatus($CONF['USHIDDEN']);
                  if ($lock) $UI->setStatus($CONF['USLOCKED']);
                  if ($hide) $UI->setStatus($CONF['USHIDDEN']);
                  $UI->notify = 0;
                  $UI->create();
                  
                  $UGI->create($UI->username, $defgroup, "member");
                  
                  $UOI->create($UI->username, "owngroupsonly", "no");
                  if (strtolower($arr[10]) == "yes" || strtolower($arr[10]) == "1")
                  {
                     $UOI->create($UI->username, "showbirthday", "yes");
                  }
                  else
                  {
                     $UOI->create($UI->username, "showbirthday", "no");
                  }
                  $UOI->create($UI->username, "ignoreage", "no");
                  $UOI->create($UI->username, "notifybirthday", "no");
                  $UOI->create($UI->username, "language", $deflang);
                  $UOI->create($UI->username, "defgroup", "All");
                  
                  $fullname = $UI->firstname . " " . $UI->lastname;
                  $LOG->log("logUser", $L->checkLogin(), "log_csv_import", $UI->username . " (" . $fullname . ")");
                  $this->count_imported++;
               }
               else
               {
                  $this->count_skipped++;
               }
            }
         }
      }
      unset($arr);
      fclose($fpointer);
   }
}

// ---------------------------------------------------------------------------
/**
 * Checks whether a user is authorized in the active permission scheme
 *
 * @param string $scheme Permission scheme to check
 * @param string $permission Permission to check
 * @param string $targetuser Some features reference data of other users. This is the target
 * 
 * @return boolean True if allowed, false if not.
 */
function isAllowed($permission = '')
{
   global $C, $L, $P, $UL;
   
   $pscheme = $C->read("permissionScheme");
   
   if ($currentuser = $L->checkLogin())
   {
      /**
       * Someone is logged in.
       * Check permission by role.
       */
      $UL->findByName($currentuser);
      return $P->isAllowed($pscheme, $permission, $UL->role);
   }
   else
   {
      /**
       * It's a public user
       */
      return $P->isAllowed($pscheme, $permission, 3);
   }
}

// ---------------------------------------------------------------------------
/**
 * Restores a user and all related records from archive
 *
 * @param string $username Username to restore
 * 
 * @return boolean True or False indicating success
 */
function restoreUser($username)
{
   global $CONF, $AL, $D, $L, $LOG, $T, $U, $UMSG, $UG, $UO;
   
   /**
    * Do not restore if username exists in any of the active tables
    */
   if ($U->exists($username)) return FALSE;
   if ($UG->exists($username)) return FALSE;
   if ($UO->exists($username)) return FALSE;
   if ($T->exists($username)) return FALSE;
   if ($D->exists($username)) return FALSE;
   if ($AL->exists($username)) return FALSE;
   if ($UMSG->exists($username)) return FALSE;
   
   /**
    * Get fullname for log
    */
   $U->findByName($username);
   $fullname = trim($U->firstname . " " . $U->lastname);
   
   /**
    * Restore user
    * Restore memberships
    * Restore options
    * Restore templates
    * Restore daynotes
    * Restore allowances
    * Restore announcements
    */
   $U->restore($username);
   $UG->restore($username);
   $UO->restore($username);
   $T->restore($username);
   $D->restore($username);
   $AL->restore($username);
   $UMSG->restore($username);
   
   /**
    * Delete user from archive tables
    */
   deleteUser($username, $archive = true);
   
   /**
    * Log this event
    */
   $LOG->log("logUser", $L->checkLogin(), "log_user_restored", $fullname . " (" . $username . ")");
   
   return true;
}
?>
