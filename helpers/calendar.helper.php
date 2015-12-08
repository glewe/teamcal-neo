<?php
/**
 * calendar.helper.php
 *
 * Collection of calendar related functions
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcneo.lewe.com/doc/license.txt
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

// ---------------------------------------------------------------------------
/**
 * Checks wether the maximum absences threshold is reached
 *
 * @param string $year Year of the day to count for
 * @param string $month Month of the day to count for
 * @param string $day Day to count for
 * @param string $base Threshold base: user or group
 * @param string $group Group to refer to in case of base=group
 * @return boolean True if reached, false if not
 */
function absenceThresholdReached($year, $month, $day, $base, $group = '')
{
   global $C, $CONF, $G, $T, $U, $UG;
   
   if ($base == "group")
   {
      /**
       * Count group members
       */
      $members = $UG->getAllForGroup($group);
      $usercount = $UG->countMembers($group);
      
      /**
       * Count all group absences for this day
       */
      $absences = 0;
      foreach ( $members as $member )
      {
         $abss = $T->countAllAbsences($member['username'], $year, $month, $day, $day);
         //echo "<script type=\"text/javascript\">alert(\"Count Absences: ".$member['username']."|".$year.$month.$day."|".$day.": ".$abss."\");</script>";
         $absences += $abss;
      }
   }
   else
   {
      /**
       * Count all members
       */
      $usercount = $U->countUsers();
      
      /**
       * Count all absences for this day
       */
      $absences = 0;
      $absences += $T->countAllAbsences('%', $year, $month, $day);
   }
   
   /**
    * Now we know how many absences we have already. Add one to those because this check is done
    * to check for a threshold breach with another absence on top of that.
    */
   $absences++;
     
   /**
    * Check absences against threshold
    */
   $absencerate = ((100 * $absences) / $usercount);
   $threshold = intval($C->read("declThreshold"));
   if ($absencerate >= $threshold)
   {
      //echo "<script type=\"text/javascript\">alert(\"Absence Rate | Threshold: ".$absencerate."|".$threshold."\");</script>";
      return true;
   }
   else
   {
      return false;
   }
}

// ---------------------------------------------------------------------------
/**
 * Checks an array of requested absences against the declination rules and
 * other possible restrictions.
 *
 * @param string $username Username for which the requested absences shall be checked
 * @param string $year Year for which the requested absences shall be checked
 * @param string $month Month for which the requested absences shall be checked
 * @param array $requestedAbsences Array of the requested absences
 *       
 * @return array $approvalResult (
 *         boolean approvalResult // none, partial, all
 *         array declinedAbsences
 *         array approvedAbsences
 *         boolean allChangesInPast
 *         )
 */
function approveAbsences($username, $year, $month, $currentAbsences, $requestedAbsences)
{
   global $C, $LANG, $T, $U, $UG, $UL;
   
   $approvalResult = array (
      'approvalResult' => 'all',
      'approvedAbsences' => array (),
      'currentAbsences' => $currentAbsences,
      'declinedAbsences' => array (),
      'declinedReasons' => array (),
      'allChangesInPast' => false 
   );
   
   $approvedAbsences = array ();
   $declinedAbsences = array ();
   $declinedReasons = array ();
    
   /**
    * Get date information about the month of the request
    */
   $monthInfo = dateInfo($year, $month);
   
   /**
    * Get the current template of the user for whom this request was made
    * Also, get all related groups for this user (to check group thresholds)
    */
   $userGroups = $UG->getAllforUser($username);
   
   /**
    * Initialize arrays
    */
   for($i = 1; $i <= $monthInfo['daysInMonth']; $i++)
   {
      $approvedAbsences[$i] = '0';
      $declinedAbsences[$i] = '0';
      $declinedReasons[$i] = '';
   }
   
   /**
    * Check whether $currentAbsences and $requestedAbsences differ in any way.
    * If not, we can save us the trouble of the one by one comparison.
    */
   $arraysDiffer = false;
   $approvalResult['allChangesInPast'] = true;
   $todayDate = date("Ymd", time());
   for($i = 1; $i <= $monthInfo['daysInMonth']; $i++)
   {
      if ($currentAbsences[$i] != $requestedAbsences[$i])
      {
         /**
          * We have a difference
          */
         $arraysDiffer = true;
         /**
          * Check whether at least one change is not in the past.
          * We need that
          * info later for not sending notification mails if all is in the past.
          */
         $iDate = intval($year . $month . sprintf("%02d", $i));
         if ($iDate >= $todayDate) $approvalResult['allChangesInPast'] = false;
      }
   }
   
   /**
    * Before we go any further,
    * - if the requesting user is admin OR
    * - the affected user is in a role out of scope of declination
    */
   if ($UL->username == 'admin' OR !$U->hasRole($username, $C->read("declScope")) OR isAllowed("calendareditall"))
   {
      $approvalResult['approvalResult'] = 'all';
      return $approvalResult;
   }
   
   /**
    * Now loop through each request and check for declination reasons
    */
   if ($arraysDiffer)
   {
      for($i = 1; $i <= $monthInfo['daysInMonth']; $i++)
      {
         /**
          * See if there was a change requested for this day
          */
         if ($currentAbsences[$i] != $requestedAbsences[$i])
         {
            $requestedDate = $year . '-' . $month . '-' . sprintf("%02d", ($i));
            
            /**
             * Assume approved for now. If one of the declination check fails we will overwrite.
             */
            $approvedAbsences[$i] = $requestedAbsences[$i];
            
            /**
             * ABSENCE THRESHOLD
             * Only check this if the requested absence is in fact an absence (not Zero)
             */
            if ($C->read("declAbsence") AND $requestedAbsences[$i] != '0')
            {
               if ($C->read("declBase") == "group")
               {
                  /**
                   * There is a declination threshold for groups.
                   * We have to go through each group of this user and see
                   * wether the threshold would be violated by this request.
                   */
                  $groups = "";
                  foreach ( $userGroups as $row )
                  {
                     if (absenceThresholdReached($year, $month, $i, "group", $row['groupid']))
                     {
                        /**
                         * Only decline and add the affected group if the requesting user
                         * - is not allowed to edit group calendars OR
                         * - is neither member nor manager of the affected group
                         */
                        if (!isAllowed("calendareditgroup") or (!$UG->isGroupManagerOfGroup($luser, $row['id']) and !$UG->isMemberOfGroup($luser, $row['groupid'])))
                        {
                           $affectedgroups[] = $row['groupid'];
                           $groups .= $row['groupid'] . ", ";
                        }
                     }
                  }
                  
                  if (strlen($groups))
                  {
                     /**
                      * Absence threshold for one or more groups is reached.
                      * Absence cannot be set.
                      */
                     $groups = substr($groups, 0, strlen($groups) - 2);
                     $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong>" . $LANG['err_decl_group_threshold'] . $groups;
                     $declinedAbsences[$i] = $requestedAbsences[$i];
                     $approvedAbsences[$i] = $currentAbsences[$i];
                  }
               }
               else
               {
                  if (absenceThresholdReached($year, $month, $i, "all"))
                  {
                     /**
                      * Absence threshold for all is reached.
                      * Absence cannot be set.
                      */
                     $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong>" . $LANG['err_decl_total_threshold'];
                     $declinedAbsences[$i] = $requestedAbsences[$i];
                     $approvedAbsences[$i] = $currentAbsences[$i];
                  }
               }
            }
            
            /**
             * BEFORE DATE
             */
            if ($C->read("declBefore"))
            {
               if (!strlen($beforeDate = $C->read("declBeforeDate")))
               {
                  /**
                   * A specific before date is not set. The it is today.
                   */
                  $beforeDate = getISOToday();
               }
               
               if ($requestedDate < $beforeDate)
               {
                  /**
                   * Requested absence is before the before date.
                   * Absence cannot be set.
                   */
                  $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong>" . $LANG['err_decl_before_date'] . $beforeDate;
                  $declinedAbsences[$i] = $requestedAbsences[$i];
                  $approvedAbsences[$i] = $currentAbsences[$i];
               }
            }
            
            /**
             * PERIOD 1-3
             */
            $periods = 3;
            for ($p=1; $p<=$periods; $p++)
            {
               if ($C->read("declPeriod".$p))
               {
                  $startDate = $C->read("declPeriod".$p."Start");
                  $endDate = $C->read("declPeriod".$p."End");
                  if ($requestedDate >= $startDate AND $requestedDate <= $endDate)
                  {
                     /**
                      * Requested absence is inside a declination period.
                      * Absence cannot be set.
                      */
                     $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong>" . $LANG['err_decl_period'] . $startDate . " - " . $endDate;
                     $declinedAbsences[$i] = $requestedAbsences[$i];
                     $approvedAbsences[$i] = $currentAbsences[$i];
                  }
               }
            }
            
         }
         else
         {
            /**
             * No absence change. Add to approved.
             */
            $approvedAbsences[$i] = $currentAbsences[$i];
         }
      }
      
      /**
       * Check for partial or total declination
       */
      $approved = true;
      $declined = false;
      for($i = 1; $i <= $monthInfo['daysInMonth']; $i++)
      {
         if ($approvedAbsences[$i] != $requestedAbsences[$i])
         {
            /**
             * At least one request is declined
             */
            $declined = true;
         }
         else
         {
            /**
             * At least one request is approved
             */
            $approved = true;
         }
         
         if ($approved and !$declined)
         {
            /**
             * All requests are approved
             */
            $approvalResult['approvalResult'] = 'all';
         }
         elseif ($approved and $declined)
         {
            /**
             * Some are approved, some declined
             */
            $approvalResult['approvalResult'] = 'partial';
         }
         else
         {
            /**
             * All declined
             */
            $approvalResult['approvalResult'] = 'none';
         }
      }
   }

   if (false) // Enable to debug
   {
      print_r($currentAbsences);print " :: Current <br>";
      print_r($requestedAbsences);print " :: Requested <br>";
      print_r($approvedAbsences);print " :: Approved <br>";
      print_r($declinedAbsences);print " :: Declined <br>";
      print_r($declinedReasons);print " :: Reasons <br>";
   }
    
   $approvalResult['approvedAbsences'] = $approvedAbsences;
   $approvalResult['declinedAbsences'] = $declinedAbsences;
   $approvalResult['declinedReasons'] = $declinedReasons;
    
   return $approvalResult;
}
?>
