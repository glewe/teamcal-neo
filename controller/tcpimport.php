<?php
/**
 * tcpimport.php
 * 
 * TeamCal Pro Import page controller
 *
 * @category TemCal Neo 
 * @version 1.9.000
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

//=============================================================================
//
// VARIABLE DEFAULTS
//
$viewData['tcpDbServer'] = '';
$viewData['tcpDbName']   = '';
$viewData['tcpDbUser']   = '';
$viewData['tcpDbPrefix'] = '';

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

   $viewData['tcpDbServer'] = $_POST['txt_tcpDbServer'];
   $viewData['tcpDbName']   = $_POST['txt_tcpDbName'];
   $viewData['tcpDbUser']   = $_POST['txt_tcpDbUser'];
   $viewData['tcpDbPrefix'] = $_POST['txt_tcpDbPrefix'];
    
   //
   // Form validation
   //
   $inputError = false;
   
   if (!$inputError)
   {
      // ,--------,
      // | Import |
      // '--------'
      if (isset($_POST['btn_import']))
      {
         //
         // Connect to TeamCal Pro databases
         //
         $dberror=false;
         try
         {
            $pdoTCP = new PDO('mysql:host=' . $_POST['txt_tcpDbServer'] . ';dbname=' . $_POST['txt_tcpDbName'] . ';charset=utf8', $_POST['txt_tcpDbUser'], $_POST['txt_tcpDbPassword']);
         } catch ( PDOException $e )
         {
            $dberror=true;
         }
         
         if (!$dberror)
         {
            $tcpPrefix = $_POST['txt_tcpDbPrefix'];
            $queryFailed = false;
            $importResult = '';
            
            //-----------------------------------------------------------------
            //
            // Absence Types
            //
            if ($_POST['opt_absImport'] == 'replace' OR $_POST['opt_absImport'] == 'add' )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."absences`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_absImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $A->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     $A->name = $rec['name'];
                     $A->symbol = $rec['symbol'];
                     $A->icon = 'times'; // Default Font Awesome icon X
                     $A->color = $rec['color'];
                     $A->bgcolor = $rec['bgcolor'];
                     $A->bgtrans = $rec['bgtransparent'];
                     $A->factor = $rec['factor'];
                     $A->allowance = $rec['allowance'];
                     $A->show_in_remainder = $rec['show_in_remainder'];
                     $A->show_totals = $rec['show_totals'];
                     $A->approval_required = $rec['approval_required'];
                     $A->counts_as_present = $rec['counts_as_present'];
                     $A->manager_only = $rec['manager_only'];
                     $A->hide_in_profile = $rec['hide_in_profile'];
                     $A->confidential = $rec['confidential'];
                     $A->create();
                  }
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_abs", " (".$_POST['opt_absImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_abs'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_abs'].'</li>';
               }
            } // Absence Types
          
            //-----------------------------------------------------------------
            //
            // Groups
            //
            if ($_POST['opt_groupsImport'] == 'replace' OR $_POST['opt_groupsImport'] == 'add' )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."groups`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_groupsImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $G->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     $G->name = $rec['groupname'];
                     $G->description = $rec['description'];
                     $G->minpresent = $rec['min_present'];
                     $G->maxabsent = $rec['max_absent'];
                     $G->create();
                  }
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_groups", " (".$_POST['opt_groupsImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_groups'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_groups'].'</li>';
               }
            } // Groups
          
            //-----------------------------------------------------------------
            //
            // Holidays
            //
            if ($_POST['opt_holsImport'] == 'replace' OR $_POST['opt_holsImport'] == 'add' )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."holidays`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_holsImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $H->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     if ($rec['cfgsym'] > 1 ) // Do not import Business day and Weekend day (0 and 1)
                     {
                        $H->name = $rec['dspname'];
                        $H->description = '';
                        $H->color = '';
                        $H->bgcolor = $rec['dspcolor'];
                        if ($rec['options'] & 0x01) $H->businessday = '1'; else $H->businessday = '0'; 
                        $H->create();
                     }
                  }
                  
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_hols", " (".$_POST['opt_holsImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_hols'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_hols'].'</li>';
               }
            } // Holidays
          
            //-----------------------------------------------------------------
            //
            // Regions
            //
            if ($_POST['opt_regsImport'] == 'replace' OR $_POST['opt_regsImport'] == 'add' )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."regions`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_regsImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $R->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     $R->name = $rec['regionname'];
                     $R->description = $rec['description'];
                     $R->create();
                  }
                  
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_regs", " (".$_POST['opt_regsImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_regs'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_regs'].'</li>';
               }
            } // Regions
          
            //-----------------------------------------------------------------
            //
            // Users, User Options
            //
            if ($_POST['opt_usersImport'] == 'replace' OR $_POST['opt_usersImport'] == 'add' )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."users`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
               
                  if ($_POST['opt_usersImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $U->deleteAll();
                     $UG->deleteAll();
                     $UMSG->deleteAll();
                     $UO->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     //
                     // User Accounts
                     //
                     $U->username = $rec['username'];
                     $U->password = $rec['password'];
                     $U->firstname = $rec['firstname'];
                     $U->lastname = $rec['lastname'];
                     $U->email = $rec['email'];
                     
                     // Only Admin and User roles are mapped
                     if ($rec['usertype'] & 0x04) $U->role = '1'; // Admin
                     if ($rec['usertype'] & 0x01) $U->role = '2'; // User

                     if ($rec['status'] & 0x01) $U->locked = '1'; // Locked
                     if ($rec['status'] & 0x08) $U->hidden = '1'; // Hidden
                     if ($rec['status'] & 0x04) $U->onhold = '1'; // On hold

                     $U->verify = '0';
                     $U->bad_logins = '0';
                     $U->grace_start = DEFAULT_TIMESTAMP;
                     $U->last_pw_change = date('YmdHis');
                     $U->last_login = DEFAULT_TIMESTAMP;
                     $U->created = date('YmdHis');
                                
                     $U->create();
                     
                     //
                     // User Options
                     //
                     if ($rec['usertype'] & 0x08)
                     {
                        $myGender = 'male';
                        $myAvatar = 'default_male.png';
                     }
                     else
                     {
                        $myGender = 'female';
                        $myAvatar = 'default_female.png';
                     }
                     $UO->create($rec['username'], 'title', $rec['title']);
                     $UO->create($rec['username'], 'position', $rec['position']);
                     $UO->create($rec['username'], 'phone', $rec['phone']);
                     $UO->create($rec['username'], 'mobile', $rec['mobile']);
                     $UO->create($rec['username'], 'id', $rec['idnumber']);
                     $UO->create($rec['username'], 'gender', $myGender);
                     $UO->create($rec['username'], 'avatar', $myAvatar);
                     $UO->create($rec['username'], 'theme', 'default');
                     $UO->create($rec['username'], 'language', 'default');
                     $UO->create($rec['username'], 'custom1', $rec['custom1']);
                     $UO->create($rec['username'], 'custom2', $rec['custom2']);
                     $UO->create($rec['username'], 'custom3', $rec['custom3']);
                     $UO->create($rec['username'], 'custom4', $rec['custom4']);

                     //
                     // Custom Field Titles
                     //
                     $arrCustomfields = array('userCustom1','userCustom2','userCustom3','userCustom4',);
                     foreach($arrCustomfields as $customField) {
                        $myQuery = "SELECT `value` FROM `".$tcpPrefix."config` WHERE `name` = '".$customField."';";
                        $query = $pdoTCP->prepare($myQuery);
                        $result = $query->execute();
                        if ($result)
                        {
                           $records = array ();
                           while ( $row = $query->fetch() )
                           {
                              $C->save($customField,$row['value']);
                           }
                        }
                     }
                  }
                  
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_users", " (".$_POST['opt_usersImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_users'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_users'].'</li>';
               }
               
            } // Users Table
          
            //-----------------------------------------------------------------
            //
            // The following tables can only be imported if a corresponding table
            // from above has been too.
            //
            
            //-----------------------------------------------------------------
            //
            // Allowances
            // 
            // Required imports from above
            // - Absences
            // - Users
            //
            if (  ($_POST['opt_alloImport'] == 'replace' OR $_POST['opt_alloImport'] == 'add') AND 
                  ($_POST['opt_absImport'] == 'replace' OR $_POST['opt_absImport'] == 'add') AND
                  ($_POST['opt_usersImport'] == 'replace' OR $_POST['opt_usersImport'] == 'add')
               )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."allowances`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_alloImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $AL->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     //
                     // Get the TCP absence name for this ID
                     //
                     $myQuery = "SELECT name FROM `".$tcpPrefix."absences` WHERE id = '".$rec['absid']."' ;";
                     $query = $pdoTCP->prepare($myQuery);
                     $result = $query->execute();
                     if ($result and $rowTCP = $query->fetch())
                     {
                        //
                        // Get the absence ID from TCN for that name (the ID may be different)
                        //
                        $A->getByName($rowTCP['name']);
                        
                        //
                        // Create TCN allowance record
                        //
                        $AL->username = $rec['username'];
                        $AL->absid = $A->id;
                        $AL->carryover = $rec['lastyear'];
                        $AL->create();
                     }
                  }
                  
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_allo", " (".$_POST['opt_alloImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_allo'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_allo'].'</li>';
               }
            } // Allowances
          
            //-----------------------------------------------------------------
            //
            // Daynotes
            // 
            // Required imports from above
            // - Regions
            // - Users
            //
            if (  ($_POST['opt_daynImport'] == 'replace' OR $_POST['opt_daynImport'] == 'add') AND 
                  ($_POST['opt_regsImport'] == 'replace' OR $_POST['opt_regsImport'] == 'add') AND
                  ($_POST['opt_usersImport'] == 'replace' OR $_POST['opt_usersImport'] == 'add')
               )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."daynotes`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_daynImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $D->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     //
                     // Create TCN Daynote record
                     //
                     $D->yyyymmdd = $rec['yyyymmdd'];
                     $D->username = $rec['username'];
                     $D->daynote = $rec['daynote'];
                     if ($regId = $R->getId($rec['region'])) $D->region = $regId; else $D->region = '1';
                     $D->color = 'info';
                     $D->create();
                  }
                  
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_dayn", " (".$_POST['opt_daynImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_dayn'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_dayn'].'</li>';
               }
            } // Daynotes
          
            //-----------------------------------------------------------------
            //
            // Roles
            //
            if ($_POST['opt_rolesImport'] == 'add' )
            {
               $RO->name = 'Director';
               $RO->description = 'Directors';
               $RO->color = 'warning';
               $RO->create();

               $RO->name = 'Assistant';
               $RO->description = 'Assistants';
               $RO->color = 'success';
               $RO->create();
               
               if ($_POST['opt_usersImport'] == 'replace' OR $_POST['opt_usersImport'] == 'add')
               {
                  //
                  // Read TeamCal Pro users table to get their role (usertype). If one is Director or Assistant,
                  // assign them to that role in TCN as well.
                  //
                  $myQuery = "SELECT * FROM `".$tcpPrefix."users` WHERE username != 'admin';";
                  $query = $pdoTCP->prepare($myQuery);
                  $result = $query->execute();
                  if ($result)
                  {
                     $records = array ();
                     while ( $row = $query->fetch() )
                     {
                        $records[] = $row;
                     }
                     
                     foreach ($records as $rec)
                     {
                        if ( $rec['usertype'] & 0x40 ) // Assistant
                        {
                           $RO->getByName('Assistant');
                           $U->setRole($rec['username'],$RO->id);
                        }
                        
                        if ( $rec['usertype'] & 0x10 ) // Director
                        {
                           $RO->getByName('Director');
                           $U->setRole($rec['username'],$RO->id);
                        }
                     }
                     
                     //
                     // Log this event
                     //
                     $LOG->log("logImport",L_USER,"log_tcpimp_roles", " (".$_POST['opt_rolesImport'].")");
                     $importResult .= '<li>'.$LANG['tcpimp_roles'].'</li>';
                  }
                  else
                  {
                     $queryFailed = true;
                     $importResult .= '<li>'.$LANG['tcpimp_roles'].'</li>';
                  }
                  
               }
            } // Roles
            
            //-----------------------------------------------------------------
            //
            // User_Group
            //
            // Required imports from above
            // - Groups
            // - Users
            //
            if (  ($_POST['opt_ugrImport'] == 'replace' OR $_POST['opt_ugrImport'] == 'add') AND
                  ($_POST['opt_groupsImport'] == 'replace' OR $_POST['opt_groupsImport'] == 'add') AND
                  ($_POST['opt_usersImport'] == 'replace' OR $_POST['opt_usersImport'] == 'add')
               )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."user_group`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_ugrImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $UG->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     //
                     // Get TCN group ID
                     //
                     $G->getByName($rec['groupname']);
                      
                     //
                     // Create TCN user_group record
                     //
                     $UG->save($rec['username'],$G->id,$rec['type']);
                  }
            
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_ugr", " (".$_POST['opt_ugrImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_ugr'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_ugr'].'</li>';
               }
            } // User_group
            
            //-----------------------------------------------------------------
            //
            // Month Templates
            //
            // Required imports from above
            // - Regions
            // - Holidays
            //
            if (  ($_POST['opt_mtplImport'] == 'replace' OR $_POST['opt_mtplImport'] == 'add') AND
                  ($_POST['opt_holsImport'] == 'replace' OR $_POST['opt_holsImport'] == 'add') AND
                  ($_POST['opt_regsImport'] == 'replace' OR $_POST['opt_regsImport'] == 'add')
               )
            {
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."months`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_mtplImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $M->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     $tcpYear = substr($rec['yearmonth'],0,4);
                     $tcpMonth = substr($rec['yearmonth'],4,2);
                     $tcpRegion = $rec['region'];
                     
                     //
                     // Get the dateinfo of this month
                     //
                     $dateInfo = dateInfo($tcpYear, $tcpMonth,'1');

                     $M->year = $tcpYear;
                     $M->month = $tcpMonth;

                     $regionID = '1'; // Default
                     if ($R->getByName($tcpRegion)) $regionID = $R->id; 
                     $M->region = $regionID;
                      
                     // Weekdays
                     //
                     // Get the weekdays for each day of this month and add it to $M.
                     // Make sure we run through all possible 31 days. If the current month
                     // has less days, set the rest to 0.
                     //
                     for($i = 1; $i <= 31; $i++)
                     {
                        if ($i <= $dateInfo['daysInMonth'])
                        {
                           $dInfo = dateInfo($tcpYear, $tcpMonth, $i);
                           $dayofweek = $dInfo['wday'];
                        }
                        else
                        {
                           $dayofweek = 0;
                        }
                        $prop = 'wday' . $i;
                        $M->$prop = $dayofweek;
                     }
                     
                     // Weeknumbers
                     //
                     // Get the weeknumbers for each day of this month and add it to $M.
                     // Make sure we run through all possible 31 days. If the current month
                     // has less days, set the rest to 0.
                     //
                     for($i = 1; $i <= 31; $i++)
                     {
                        if ($i <= $dateInfo['daysInMonth'])
                        {
                           $dInfo = dateInfo($tcpYear, $tcpMonth, $i);
                           $weeknumber = $dInfo['week'];
                        }
                        else
                        {
                           $weeknumber = 0;
                        }
                        $prop = 'week' . $i;
                        $M->$prop = $weeknumber;
                     }

                     // Holidays
                     //
                     // Get the holiday for each day of this month and add it to $M.
                     // Make sure we run through all possible 31 days. If the current month
                     // has less days, set the rest to 0.
                     //
                     for($i = 1; $i <= 31; $i++)
                     {
                        $holiday = 0; // Default
                        if ($i <= $dateInfo['daysInMonth'])
                        {
                           //
                           // Get TCP holiday name from TCP holiday ID
                           //
                           $tcpHolID = substr($rec['template'],$i-1,1);
                           if ($tcpHolID > 1) // Do not import Business day and Weekend day (0 and 1)
                           {
                              $tcpHolQuery = "SELECT * FROM `".$tcpPrefix."holidays` WHERE cfgsym = ".$tcpHolID.";";
                              $query = $pdoTCP->prepare($tcpHolQuery);
                              $result = $query->execute();
                              if ($result and $row = $query->fetch())
                              {
                                 $tcpHolName = $row['dspname'];
                              }
                               
                              //
                              // Get TCN holiday ID from TCP holiday name
                              //
                              if ($H->getByName($tcpHolName)) $holiday = $H->id;
                           }
                           else
                           {
                              $holiday = 0;
                           }
                        }
                        $prop = 'hol' . $i;
                        $M->$prop = $holiday;
                     }
                     $M->create();
                  }
            
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_mtpl", " (".$_POST['opt_mtplImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_mtpl'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_mtpl'].'</li>';
               }
            } // Month Templates
            
            //-----------------------------------------------------------------
            //
            // User Templates
            //
            // Required imports from above
            // - Absence Types
            // - Users
            //
            if (  ($_POST['opt_utplImport'] == 'replace' OR $_POST['opt_utplImport'] == 'add') AND
                  ($_POST['opt_absImport'] == 'replace' OR $_POST['opt_absImport'] == 'add') AND
                  ($_POST['opt_usersImport'] == 'replace' OR $_POST['opt_usersImport'] == 'add')
                  )
            {
                
               //
               // Read TeamCal Pro table
               //
               $myQuery = "SELECT * FROM `".$tcpPrefix."templates`;";
               $query = $pdoTCP->prepare($myQuery);
               $result = $query->execute();
               
               if ($result)
               {
                  $records = array ();
                  while ( $row = $query->fetch() )
                  {
                     $records[] = $row;
                  }
                   
                  if ($_POST['opt_utplImport'] == 'replace' )
                  {
                     //
                     // Empty corresponding TeamCal Neo table
                     //
                     $T->deleteAll();
                  }
                   
                  //
                  // Fill corresponding TeamCal Neo table
                  //
                  foreach ($records as $rec)
                  {
                     //
                     // Get the dateinfo of this month
                     //
                     $dateInfo = dateInfo($rec['year'], $rec['month'],'1');

                     $T->username = $rec['username'];
                     $T->year = $rec['year'];
                     $T->month = $rec['month'];
                     
                     //
                     // Loop through all absences
                     //
                     for($i = 1; $i <= 31; $i++)
                     {
                        $absence = '0'; // Default
                         
                        if ($rec['abs' . $i] > 0)
                        {
                           //
                           // Get TCP absence name from TCP absence ID
                           //
                           $tcpAbsQuery = "SELECT * FROM `".$tcpPrefix."absences` WHERE id = ".$rec['abs' . $i].";";
                           $query = $pdoTCP->prepare($tcpAbsQuery);
                           $result = $query->execute();
                           if ($result and $row = $query->fetch())
                           {
                              $tcpAbsName = $row['name'];
                           }
                     
                           //
                           // Get TCN absence ID from TCP absence name
                           //
                           if ($A->getByName($tcpAbsName)) $absence = $A->id;
                        }
                        
                        $prop = 'abs' . $i;
                        $T->$prop = $absence;
                     }
                     $T->create();
                  }
                  
                  //
                  // Log this event
                  //
                  $LOG->log("logImport",L_USER,"log_tcpimp_utpl", " (".$_POST['opt_utplImport'].")");
                  $importResult .= '<li>'.$LANG['tcpimp_utpl'].'</li>';
               }
               else
               {
                  $queryFailed = true;
                  $importResult .= '<li>'.$LANG['tcpimp_utpl'].'</li>';
               }
            } // User Templates
            
            
            //-----------------------------------------------------------------
            //
            // Prepare result message
            //
            if (!$queryFailed AND strlen($importResult))
            {
               //
               // Success
               //
               $showAlert = TRUE;
               $alertData['type'] = 'success';
               $alertData['title'] = $LANG['alert_success_title'];
               $alertData['subject'] = $LANG['tcpimp_alert_title'];
               $alertData['text'] = $LANG['tcpimp_alert_success'];
               $alertData['help'] = $LANG['tcpimp_alert_success_help'].'<ul>'.$importResult.'</ul>';
            }
            else 
            {
               //
               // One or more TCP queries failed
               //
               $showAlert = TRUE;
               $alertData['type'] = 'danger';
               $alertData['title'] = $LANG['alert_danger_title'];
               $alertData['subject'] = $LANG['tcpimp_alert_title'];
               $alertData['text'] = $LANG['tcpimp_alert_fail'];
               $alertData['help'] = $importResult;
            }
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
$viewData['sample'] = '';

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
