<?php
/**
 * calendar.helper.php
 *
 * Collection of calendar related functions
 *
 * @category TeamCal Neo 
 * @version 1.2.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
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
function approveAbsences($username, $year, $month, $currentAbsences, $requestedAbsences, $regionId)
{
   global $A, $C, $D, $G, $LANG, $T, $U, $UG, $UL;
   
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
   $thresholdReached = false;
    
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
               $today = date('Ymd');
               $declStartdate = str_replace('-','',$C->read('declAbsenceStartdate'));
               $declEnddate = str_replace('-','',$C->read('declAbsenceEnddate'));
               
               $applyRule = true; // Assume true
               switch ($C->read('declAbsencePeriod'))
               {
                  case 'nowEnddate':
                     if ($today>$declEnddate) $applyRule = false;
                     break;
                  case 'startdateForever':
                     if ($today<$declStartdate) $applyRule = false;
                     break;
                  case 'startdateEnddate':
                     if ($today<$declStartdate OR $today>$declEnddate) $applyRule = false;
                     break;
               }

               if($applyRule)
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
                           if (!isAllowed("calendareditgroup") or (!$UG->isGroupManagerOfGroup($UL->username, $row['id']) and !$UG->isMemberOfGroup($UL->username, $row['groupid'])))
                           {
                              $affectedgroups[] = $row['groupid'];
                              $groups .= $G->getNameById($row['groupid']) . ", ";
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
                        $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $LANG['alert_decl_group_threshold'] . $groups;
                        $declinedAbsences[$i] = $requestedAbsences[$i];
                        $approvedAbsences[$i] = $currentAbsences[$i];
                        $thresholdReached = true;
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
                        $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $LANG['alert_decl_total_threshold'];
                        $declinedAbsences[$i] = $requestedAbsences[$i];
                        $approvedAbsences[$i] = $currentAbsences[$i];
                        $thresholdReached = true;
                     }
                  }
               }
            }
            
            /**
             * BEFORE DATE
             */
            if ($C->read("declBefore"))
            {
               $today = date('Ymd');
               $declStartdate = str_replace('-','',$C->read('declBeforeStartdate'));
               $declEnddate = str_replace('-','',$C->read('declBeforeEnddate'));
                
               $applyRule = true; // Assume true
               switch ($C->read('declBeforePeriod'))
               {
                  case 'nowEnddate':
                     if ($today>$declEnddate) $applyRule = false;
                     break;
                  case 'startdateForever':
                     if ($today<$declStartdate) $applyRule = false;
                     break;
                  case 'startdateEnddate':
                     if ($today<$declStartdate OR $today>$declEnddate) $applyRule = false;
                     break;
               }
               
               if($applyRule)
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
                     $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $LANG['alert_decl_before_date'] . $beforeDate;
                     $declinedAbsences[$i] = $requestedAbsences[$i];
                     $approvedAbsences[$i] = $currentAbsences[$i];
                     $thresholdReached = true;
                  }
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
                  $today = date('Ymd');
                  $declStartdate = str_replace('-','',$C->read('declPeriod1Startdate'));
                  $declEnddate = str_replace('-','',$C->read('declPeriod1Enddate'));
                  
                  $applyRule = true; // Assume true
                  switch ($C->read('declPeriod1Period'))
                  {
                     case 'nowEnddate':
                        if ($today>$declEnddate) $applyRule = false;
                        break;
                     case 'startdateForever':
                        if ($today<$declStartdate) $applyRule = false;
                        break;
                     case 'startdateEnddate':
                        if ($today<$declStartdate OR $today>$declEnddate) $applyRule = false;
                        break;
                  }
                   
                  if($applyRule)
                  {
                     $startDate = $C->read("declPeriod".$p."Start");
                     $endDate = $C->read("declPeriod".$p."End");
                     if ($requestedDate >= $startDate AND $requestedDate <= $endDate)
                     {
                        /**
                         * Requested absence is inside a declination period.
                         * Absence cannot be set.
                         */
                        if (!strlen($declMessage=$C->read("declPeriod".$p."Message"))) $declMessage = $LANG['alert_decl_period'] . $startDate . " - " . $endDate;
                        $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $declMessage;
                        $declinedAbsences[$i] = $requestedAbsences[$i];
                        $approvedAbsences[$i] = $currentAbsences[$i];
                        $thresholdReached = true;
                     }
                  }
               }
            }
            
            /**
             * ABSENCE APPROVAL REQUIRED
             */
            if ($A->getApprovalRequired($requestedAbsences[$i]) AND !$thresholdReached)
            {
               /**
                * ThresholdReached overrules absence approval
                * Only decline if the requesting user
                * - is not allowed to edit group calendars OR
                * - is neither member nor manager of the affected group
                */
               if (!isAllowed("calendareditgroup") or (!$UG->isGroupManagerOfGroup($UL->username, $row['id']) and !$UG->isMemberOfGroup($UL->username, $row['groupid'])))
               {
                  /**
                   * Absence requires approval.
                   */
                  $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $A->getName($requestedAbsences[$i]) . "): " . $LANG['alert_decl_approval_required'];
                  
                  /**
                   * Set absence but add approval daynote.
                   */
                  $declinedAbsences[$i] = $requestedAbsences[$i];
                  $approvedAbsences[$i] = $requestedAbsences[$i];
                  
                  $D->yyyymmdd = $T->year . $T->month . sprintf("%02d", ($i));
                  $D->username = $username;
                  $D->region = $regionId;
                  $D->daynote = $LANG['alert_decl_approval_required_daynote'];
                  $D->color = 'warning';
                  $D->create();
                  
                  /**
                   * Notify managers
                   */
                  sendAbsenceApprovalNotifications($username, $year, $month, $i, $requestedAbsences[$i]);
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
      
      //
      // Check the rquested absences against max allowances.
      //
      for($i = 1; $i <= $monthInfo['daysInMonth']; $i++)
      {
         //
         // CHECK ALLOWANCE PER WEEK
         //
         if ($allow=$A->getAllowWeek($requestedAbsences[$i]))
         {
            //
            // Allowance per week is positive
            // Check how much of the absence this user took already in this week
            //
            // Get the first day of the week that this absence request is in
            //
            $date = new DateTime($T->year . '-' . $T->month . '-' . sprintf("%02d", ($i)));
            $first = $date->modify('this week -1 days');
            $myts = $date->getTimestamp();
            $fromyear = date("Y", $myts);
            $frommonth = date("m", $myts);
            $fromday = date("d", $myts);
            $countFrom = $fromyear.$frommonth.$fromday;
            
            //
            // Get the last day of the week that this absence request is in
            //
            $date = new DateTime($T->year . '-' . $T->month . '-' . sprintf("%02d", ($i)));
            $last = $date->modify('this week +5 days');
            $myts = $date->getTimestamp();
            $toyear = date("Y", $myts);
            $tomonth = date("m", $myts);
            $today = date("d", $myts);
            $countTo = $toyear.$tomonth.$today;

            $fromThisMonth = intval($fromday);
            $toThisMonth = intval($today);
            
            $taken = 0;
            if (intval($frommonth) < intval($T->month))
            {
               //
               // The week for this day starts in the previous month.
               // Count what was already taken.
               //
               $taken = countAbsence($username, $requestedAbsences[$i], $countFrom, $countTo, true, false);
               $fromThisMonth = 1;
            }
            
            if (intval($tomonth) > intval($T->month))
            {
               //
               // The week for this day ends in the next month.
               // Count what was already taken.
               //
               $taken = countAbsence($username, $requestedAbsences[$i], $countFrom, $countTo, true, false);
               $toThisMonth = $monthInfo['daysInMonth'];
            }
            
            //
            // Add the ones for this month
            //
            $total = $taken;
            for($j = $fromThisMonth; $j <= $toThisMonth; $j++)
            {
               if($requestedAbsences[$j] == $requestedAbsences[$i]) $total++;
            }

            if ($total > $allow)
            {
               /**
                * Absence allowance per month reached
                */
               $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $A->getName($requestedAbsences[$i]) . ")" . str_replace('%1%', $allow, $LANG['alert_decl_allowweek_reached']);
         
               /**
                * Set absence but add approval daynote.
                */
               $declinedAbsences[$i] = $requestedAbsences[$i];
               $approvedAbsences[$i] = $currentAbsences[$i];
            }
         }
         
         //
         // CHECK ALLOWANCE PER MONTH
         //
         if ($allow=$A->getAllowMonth($requestedAbsences[$i]))
         {
            //
            // Allowance per month is positive
            // Check how much of the absence this user took already in this month
            //
            $myts = strtotime($T->year . '-' . $T->month . '-01');
            $daysInMonth = date("t", $myts);
            $countFrom = $T->year.$T->month.'01';
            $countTo = $T->year.$T->month.$daysInMonth;

            //
            // Count total of this absence in merged array
            //
            $total = 0;
            for($j = 1; $j <= $monthInfo['daysInMonth']; $j++)
            {
               if($requestedAbsences[$j] == $requestedAbsences[$i]) $total++;
            }
            
            if ($total > $allow)
            {
               //
               // Absence allowance per month reached
               //
               $declinedReasons[$i] = "<strong>" . $T->year . "-" . $T->month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $A->getName($requestedAbsences[$i]) . ")" . str_replace('%1%', $allow, $LANG['alert_decl_allowmonth_reached']);
               
               //
               // Decline absence
               //
               $declinedAbsences[$i] = $requestedAbsences[$i];
               $approvedAbsences[$i] = $currentAbsences[$i];
            }
         }
      }
      
      
      /**
       * Check for partial or total declination
       */
      $approved = true;
      $declined = false;
      for($i = 1; $i <= $monthInfo['daysInMonth']; $i++)
      {
         if ( ($approvedAbsences[$i] != $requestedAbsences[$i]) OR $declinedAbsences[$i] != '0')
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

// ---------------------------------------------------------------------------
/**
 * Counts all occurences of a given absence type for a given user in a given
 * time period
 *
 * @param   string $user       User to count for
 * @param   string $absid      Absence type ID to count
 * @param   string $from       Date to count from (including)
 * @param   string $to         Date to count to (including)
 * @param   boolean $useFactor Multiply count by factor
 * @param   boolean $combined  Count other absences that count as this one
 * @return  integer            Result of the count
 */
function countAbsence($user='%', $absid, $from, $to, $useFactor=FALSE, $combined=FALSE)
{
   global $A, $CONF, $T;

   $absences=$A->getAll();
   
   //
   // Figure out starting month and ending month
   //
   $startyear = intval(substr($from, 0, 4));
   $startmonth = intval(substr($from, 4, 2));
   $startday = intval(substr($from, 6, 2));
   $endyear = intval(substr($to, 0, 4));
   $endmonth = intval(substr($to, 4, 2));
   $endday = intval(substr($to, 6, 2));

   //
   // Get the count for this absence type
   //
   $factor = $A->getFactor($absid);
   $count = 0;
   $firstday = $startday;
   if ($firstday < 1 || $firstday > 31) $firstday = 1;

   $year = $startyear;
   $month = $startmonth;
   $ymstart = intval($year.sprintf("%02d",$month));
   $ymend= intval($endyear.sprintf("%02d",$endmonth));

   //
   // Loop through every month of the requested period
   //
   while ($ymstart<=$ymend)
   {
      if ($year==$startyear AND $month==$startmonth)
      {
         $lastday = 0;
         if ($startmonth == $endmonth)
         {
            //
            // We only have one month. Make sure to only count until the requested end day.
            //
            $lastday = $endday;
         }
         $count+=$T->countAbsence($user,$year,$month,$absid,$startday,$lastday);
      }
      else if ($year==$endyear AND $month==$endmonth)
      {
         $count+=$T->countAbsence($user,$year,$month,$absid,1,$endday);
      }
      else
      {
         $count+=$T->countAbsence($user,$year,$month,$absid);
      }
      
      if ($month==12)
      {
         $year++;
         $month = 1;
      }
      else
      {
         $month++;
      }
      $ymstart = intval($year.sprintf("%02d",$month));
   }
    
   if ($useFactor) $count*=$factor;

   //
   // If requested, count all those absence types that count as this one
   //
   $otherTotal = 0;
   if ($combined)
   {
      foreach ($absences as $otherAbs)
      {
         if ($otherId=$otherAbs['counts_as'] AND $otherId==$absid)
         {
            $otherCount = 0;
            $otherFactor = $otherAbs['factor'];
            $year = $startyear;
            $month = $startmonth;
            $ymstart = intval($year.sprintf("%02d",$month));
            $ymend= intval($endyear.sprintf("%02d",$endmonth));
            while ($ymstart<=$ymend)
            {
               if ($year==$startyear AND $month==$startmonth)
               {
                  $otherCount+=$T->countAbsence($user,$year,$month,$otherAbs['id'],$startday);
               }
               else if ($year==$endyear AND $month==$endmonth)
               {
                  $otherCount+=$T->countAbsence($user,$year,$month,$otherAbs['id'],1,$endday);
               }
               else
               {
                  $otherCount+=$T->countAbsence($user,$year,$month,$otherAbs['id']);
               }
                
               if ($month==12)
               {
                  $year++;
                  $month = 1;
               }
               else
               {
                  $month++;
               }
               $ymstart = intval($year.sprintf("%02d",$month));
            }

            //
            // A combined count always uses the factor. Doesn't make sense otherwise.
            //
            $otherTotal += $otherCount * $otherFactor;
         }
      }
   }

   $count += $otherTotal;
   return $count;
}

// ---------------------------------------------------------------------------
/**
 * Counts all business days or man days in a given time period
 *
 * @param string $cntfrom Date to count from (including)
 * @param string $cntto Date to count to (including)
 * @param string $region Region to count for
 * @param boolean $cntManDays Switch whether to multiply the business days by the amount of users and return that value instead
 * @return integer Result of the count
 */
function countBusinessDays($cntfrom, $cntto, $region = '1', $cntManDays = false)
{
   global $CONF, $H, $M, $U;

   $startyear = intval(substr($cntfrom, 0, 4));
   $startmonth = intval(substr($cntfrom, 4, 2));
   $startday = intval(substr($cntfrom, 6, 2));
   $endyear = intval(substr($cntto, 0, 4));
   $endmonth = intval(substr($cntto, 4, 2));
   $endday = intval(substr($cntto, 6, 2));
   $startyearmonth = intval(substr($cntfrom, 0, 6));
   $endyearmonth = intval(substr($cntto, 0, 6));
    
   $count = 0;
   $year = $startyear;
   $month = $startmonth;
   $firstday = $startday;
   if ($firstday < 1 OR $firstday > 31) $firstday = 1;

   $yearmonth = $startyearmonth;
   while ($yearmonth <= $endyearmonth)
   {
      $M->getMonth($year, $month, $region);
      $monthInfo = dateInfo($year, $month, '1');
      $lastday = $monthInfo['daysInMonth'];
      if ($yearmonth == $endyearmonth)
      {
         // 
         // This is the last month. Make sure we just read it up to the specified endday.
         //
         if ($endday < $monthInfo['daysInMonth']) $lastday = $endday;
      }
      
      // 
      // Now loop through each day of the month
      //
      for ($i = $firstday; $i <= $lastday; $i++)
      {
         $weekday = 'wday'.$i;
         $holiday = 'hol'.$i;
         if ($M->$weekday < 6)
         {
            //
            // This is a weekday. Check if Holiday before counting it.
            //
            if ($M->$holiday)
            {
               //
               // This is a weekday but a Holiday. Only count this if this Holiday counts as business day.
               //
               if ($H->isBusinessDay($month['hol'.$i])) $count++;
            }
            else
            {
               $count++;
            }
         }
         elseif ($M->$weekday == 6)
         {
            //
            // This is a Saturday. Check if counts as business day.
            //
            if ($H->isBusinessDay('2')) $count++;
         }
         elseif ($M->$weekday == 7)
         {
            //
            // This is a Sunday. Check if counts as business day.
            //
            if ($H->isBusinessDay('3')) $count++;
         }
      }
      
      //
      // Now set the next month for the loop
      //
      if (intval(substr($yearmonth, 4, 2)) == 12)
      {
         $year = intval(substr($yearmonth, 0, 4));
         $year++;
         $yearmonth = strval($year) . "01";
      }
      else
      {
         $year = intval(substr($yearmonth, 0, 4));
         $month = intval(substr($yearmonth, 4, 2));
         $month++;
         $yearmonth = strval($year) . sprintf("%02d",strval($month));
      }
      $firstday = 1;
   }

   if ($cntManDays)
   {
      //
      // Now we have the amount of business days in this period.
      // In order to get the remaining man days we need to multiply that amount
      // with all users (not hidden and not admin).
      //
      return $count * $U->countUsers();
   }
   else
   {
      return $count;
   }
}

// ---------------------------------------------------------------------------
/**
 * Creates an empty month template marking Saturdays and Sundays as weekend
 *
 * @param string $target Month template (month) or user template (user)
 * @param string $owner Template owner. Either region name for month template or user ID for user template
 * @param string $year Four character string representing the year
 * @param string $month Two character string representing the month
 *
 * @return bool Success code
 */
function createMonth($year, $month, $target, $owner)
{
   $dateInfo = dateInfo($year, $month, '1');
   $dayofweek = $dateInfo['wday'];
   $weeknumber = $dateInfo['week'];

   if ($target == 'region')
   {
      $MT = new Months();
      $MT->region = $owner;

      for($i = 1; $i <= $dateInfo['daysInMonth']; $i++)
      {
         $prop = 'wday' . $i;
         $MT->$prop = $dayofweek;
          
         $prop = 'week' . $i;
         $myts = strtotime($year . '-' . $month . '-' . $i);
         $MT->$prop = date("W", $myts);

         $dayofweek += 1;
         if ($dayofweek == 8)
         {
            $dayofweek = 1;
            $weeknumber++;
         }
      }
   }
   else if ($target == 'user')
   {
      $MT = new Templates();
      $MT->username = $owner;
      for($i = 1; $i <= $dateInfo['daysInMonth']; $i++)
      {
         $prop = 'abs' . $i;
         $MT->$prop = '0';
      }
   }
   else
   {
      return false;
   }
    
   $MT->year = $year;
   $MT->month = sprintf("%02d", $month);
   
   //echo "<script type=\"text/javascript\">alert(\"Debug: ".$MT->year.'|'.$MT->month.'|'.$MT->region."\");</script>";
   //print_r($MT);
   
   $MT->create();
   return true;
}

// ---------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a user calendar change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $username The username
 * @param string $year Numeric representation of the year
 * @param string $month Numeric representation of the month
 * @param string $day Numeric representation of the day
 * @param string $absence Absence ID
 */
function sendAbsenceApprovalNotifications($username, $year, $month, $day, $absence)
{
   global $A, $C, $LANG, $U, $UG, $UO;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $appURL = $C->read('appURL');
   
   $subject = $LANG['email_subject_absence_approval'];
   
   $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
   $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
   $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_absence_approval.html');
   $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
   
   $message = str_replace('%intro%', $intro, $message);
   $message = str_replace('%body%', $body, $message);
   $message = str_replace('%outro%', $outro, $message);
   $message = str_replace('%app_name%', $appTitle, $message);
   $message = str_replace('%app_url%', $appURL, $message);
   
   $message = str_replace('%fullname%', $U->getFullname($username), $message);
   $message = str_replace('%username%', $username, $message);
   $message = str_replace('%absence%', $A->getName($absence), $message);
   $message = str_replace('%date%', $year . "-" . $month . "-" . $day, $message);
   
   $users = $U->getAll();
   foreach ( $users as $profile )
   {
      if ($UG->isGroupManagerOfUser($profile['username'], $username)) 
      {
         sendEmail($profile['username'], $subject, $message);
      }
   }
}

// ---------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to an absence change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $absname The absence name
 */
function sendAbsenceEventNotifications($event, $absname)
{
   global $C, $LANG, $U, $UO;
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $appURL = $C->read('appURL');
   $events = array (
      'changed',
      'created',
      'deleted'
   );
    
   if (in_array($event, $events))
   {
      $subject = $LANG['email_subject_group_' . $event];

      $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
      $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
      $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_absence_' . $event . '.html');
      $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');

      $message = str_replace('%intro%', $intro, $message);
      $message = str_replace('%body%', $body, $message);
      $message = str_replace('%outro%', $outro, $message);
      $message = str_replace('%app_name%', $appTitle, $message);
      $message = str_replace('%app_url%', $appURL, $message);

      $message = str_replace('%absname%', $absname, $message);

      $users = $U->getAll();
      foreach ( $users as $profile )
      {
         if ($UO->read($profile['username'], 'notifyabsence')) sendEmail($profile['username'], $subject, $message);
      }
   }
}

// ---------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a holiday change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $holname The holiday name
 * @param string $holdesc The holiday description
 */
function sendHolidayEventNotifications($event, $holname, $holdesc = '')
{
   global $C, $LANG, $U, $UO;
       
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $appURL = $C->read('appURL');
   $events = array (
      'changed',
      'created',
      'deleted'
   );
    
   if (in_array($event, $events))
   {
      $subject = $LANG['email_subject_group_' . $event];

      $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
      $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
      $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_holiday_' . $event . '.html');
      $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');

      $message = str_replace('%intro%', $intro, $message);
      $message = str_replace('%body%', $body, $message);
      $message = str_replace('%outro%', $outro, $message);
      $message = str_replace('%app_name%', $appTitle, $message);
      $message = str_replace('%app_url%', $appURL, $message);

      $message = str_replace('%holname%', $holname, $message);
      $message = str_replace('%holdesc%', $holdesc, $message);

      $users = $U->getAll();
      foreach ( $users as $profile )
      {
         if ($UO->read($profile['username'], 'notifyholiday')) sendEmail($profile['username'], $subject, $message);
      }
   }
}

// ---------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a month change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $year The year
 * @param string $month The month
 * @param string $region The region
 */
function sendMonthEventNotifications($event, $year, $month, $region)
{
   global $C, $LANG, $U, $UO;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $appURL = $C->read('appURL');
   $events = array (
      'created',
      'changed',
      'deleted' 
   );
   
   if (in_array($event, $events))
   {
      $subject = $LANG['email_subject_month_' . $event];
      
      $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
      $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
      $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_month_' . $event . '.html');
      $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
      
      $message = str_replace('%intro%', $intro, $message);
      $message = str_replace('%body%', $body, $message);
      $message = str_replace('%outro%', $outro, $message);
      $message = str_replace('%app_name%', $appTitle, $message);
      $message = str_replace('%app_url%', $appURL, $message);
      
      $message = str_replace('%year%', $year, $message);
      $message = str_replace('%month%', $month, $message);
      $message = str_replace('%region%', $region, $message);
      
      $users = $U->getAll();
      foreach ( $users as $profile )
      {
         if ($UO->read($profile['username'], 'notifymonth')) sendEmail($profile['username'], $subject, $message);
      }
   }
}

// ---------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a user calendar change event
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $username The username
 * @param string $year Numeric representation of the year
 * @param string $month Numeric representation of the month
 */
function sendUserCalEventNotifications($event, $username, $year, $month)
{
   global $A, $C, $LANG, $T, $U, $UG, $UO;
   
   $language = $C->read('defaultLanguage');
   $appTitle = $C->read('appTitle');
   $appURL = $C->read('appURL');
   $events = array (
      'changed'
   );
    
   if (in_array($event, $events))
   {
      $subject = $LANG['email_subject_usercal_' . $event];
      
      $message = file_get_contents(WEBSITE_ROOT . '/templates/email_html.html');
      $intro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/intro.html');
      $body = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/body_usercal_' . $event . '.html');
      $outro = file_get_contents(WEBSITE_ROOT . '/templates/' . $language . '/outro.html');
      
      $message = str_replace('%intro%', $intro, $message);
      $message = str_replace('%body%', $body, $message);
      $message = str_replace('%outro%', $outro, $message);
      $message = str_replace('%app_name%', $appTitle, $message);
      $message = str_replace('%app_url%', $appURL, $message);
      
      $message = str_replace('%fullname%', $U->getFullname($username), $message);
      $message = str_replace('%username%', $username, $message);
      $message = str_replace('%month%', $year . "-" . $month, $message);
      
      //
      // Build html calendar table for email
      //
      $template = $T->getTemplate($username, $year, $month);
      $calendar = '<table><tr>';
      for ($i=1; $i<=31; $i++)
      {
         $calendar .= '<th>' . $i . '</th>';
      }
      $calendar .= '</tr><tr>';
      for ($i=1; $i<=31; $i++)
      {
         $prop = 'abs' . $i;
         $calendar .= '<td>' . $A->getName($template[$prop]) . '</td>';
      }
      $calendar .= '</tr></table>';
      
      $message = str_replace('%calendar%', $calendar, $message);
      
      $users = $U->getAll();
      $ugroups = $UG->getAllforUser($username);
      $sendmail = false;
      foreach ( $users as $profile )
      {
         if ($UO->read($profile['username'], 'notifycal')) 
         {
            if ($notifycalgroup = $UO->read($profile['username'], 'notifycalgroup'))
            {
               $ngroups = explode(',', $notifycalgroup);
               foreach ($ugroups as $ugroup)
               {
                  if (is_array($ngroups) AND in_array($ugroup['name'], $ngroups)) $sendmail = true;
               }
               if ($sendmail) sendEmail($profile['username'], $subject, $message);
            }
         }
      }
   }
}
?>
