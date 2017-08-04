<?php
/**
 * statspresence.php
 * 
 * Presence statistics page controller
 *
 * @category TeamCal Neo 
 * @version 1.5.004
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

//
// Defaults
//
$viewData['labels'] = "";
$viewData['data'] = "";
$viewData['absences'] = $A->getAll();
$viewData['groups'] = $G->getAll('DESC');
$viewData['region'] = '1';
$viewData['absid'] = 'all';
$viewData['groupid'] = 'all';
$viewData['period'] = 'year';
$viewData['from'] = date("Y") . '-01-01';
$viewData['to'] = date("Y") . '-12-31';
$viewData['yaxis'] = 'users';
if($color=$C->read("statsDefaultColorPresences")) $viewData['color'] = $color; else $viewData['color'] = 'green';

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
   if (isset($_POST['btn_apply']))
   {
      if (!formInputValid('txt_from', 'date')) $inputError = true;
      if (!formInputValid('txt_to', 'date')) $inputError = true;
      if (!formInputValid('txt_scaleSmart', 'numeric')) $inputError = true;
      if (!formInputValid('txt_scaleMax', 'numeric')) $inputError = true;
      if (!formInputValid('txt_colorHex', 'hexadecimal')) $inputError = true;
   }

   if (!$inputError)
   {
      // ,-------,
      // | Apply |
      // '-------'
      if (isset($_POST['btn_apply']))
      {
         //
         // Read absence type selection
         //
         $viewData['absid'] = $_POST['sel_absence'];
         
         //
         // Read group selection
         //
         $viewData['groupid'] = $_POST['sel_group'];
         $viewData['yaxis'] = $_POST['opt_yaxis'];
          
         //
         // Read period selection
         //
         $viewData['period'] = $_POST['sel_period'];
         if ($viewData['period']=='custom')
         {
            $viewData['from'] = $_POST['txt_from'];
            $viewData['to'] = $_POST['txt_to'];
         }
         
         //
         // Read diagram options
         //
         $viewData['color'] = $_POST['sel_color'];
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
      $alertData['text'] = $LANG['abs_alert_save_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$stepWidth = 1;
switch ($viewData['period'])
{
   case 'year':
      $viewData['from'] = date("Y") . '-01-01';
      $viewData['to'] = date("Y") . '-12-31';
      $stepWidth = 10;
      break;

   case 'half':
      if (date("n") <= 6)
      {
         $viewData['from'] = date("Y") . '-01-01';
         $viewData['to'] = date("Y") . '-06-30';
      }
      else
      {
         $viewData['from'] = date("Y") . '-07-01';
         $viewData['to'] = date("Y") . '-12-31';
      }
      $stepWidth = 5;
      break;
      
   case 'quarter':
      if (date("n") <= 3)
      {
         $viewData['from'] = date("Y") . '-01-01';
         $viewData['to'] = date("Y") . '-03-31';
      }
      elseif (date("n") <= 6)
      {
         $viewData['from'] = date("Y") . '-04-01';
         $viewData['to'] = date("Y") . '-06-30';
      }
      elseif (date("n") <= 9)
      {
         $viewData['from'] = date("Y") . '-07-01';
         $viewData['to'] = date("Y") . '-09-30';
      }
      else
      {
         $viewData['from'] = date("Y") . '-10-01';
         $viewData['to'] = date("Y") . '-12-31';
      }
      $stepWidth = 5;
      break;
      
   case 'month':
      $viewData['from'] = date("Y") . '-' . date("m") . '-01';
      $myts = strtotime($viewData['from']);
      $viewData['to'] = date("Y") . '-' . date("m") . '-' . sprintf('%02d',date("t", $myts));
      break;

   case 'custom':
      // 
      // Nothing to do. POST variables already read.
      //
      break;
}

//
// Button titles
//
if ($viewData['absid']=='all') $viewData['absName'] = $LANG['all'];
else                            $viewData['absName'] = $A->getName($viewData['absid']);

if ($viewData['groupid'] == "all") $viewData['groupName'] = $LANG['all'];
else                                $viewData['groupName'] = $G->getNameById($_POST['sel_group']);

if ($viewData['yaxis'] == "users") $viewData['groupName'] .= ' ' . $LANG['stats_byusers'];
else                                $viewData['groupName'] .= ' ' . $LANG['stats_bygroups'];

$viewData['periodName'] = $viewData['from'] . ' - ' . $viewData['to']; 

$labels = array();
$data = array();

//
// Read data based on yaxis selection
//
$countFrom = str_replace('-', '' , $viewData['from']);
$countTo = str_replace('-', '' , $viewData['to']);
$businessDays = countBusinessDays($countFrom, $countTo, $viewData['region']);

if ($viewData['yaxis'] == 'users')
{
   //
   // Y-axis: Users
   //
   $viewData['total'] = 0;
   if ($viewData['groupid'] == "all")
   {
      $users = $U->getAll('lastname', 'firstname', 'DESC', $archive = false, $includeAdmin = false);
   }
   else 
   {
      $users = $UG->getAllforGroup($viewData['groupid']);
   }
   foreach ($users as $user)
   {
      $userAbsences = 0;
      $userPresences = 0;
      $U->findByName($user['username']);
      
      if ( $U->firstname!="" ) 
         $labels[] = '"' . $U->lastname.", ".$U->firstname . '"';
      else 
         $labels[] = '"' . $U->lastname . '"';
   
      $count = 0;
      if ($viewData['absid'] == 'all')
      {
         foreach ($viewData['absences'] as $abs)
         {
            if ($A->get($abs['id']) AND !$A->counts_as_present)
            {
               $countFrom = str_replace('-', '' , $viewData['from']);
               $countTo = str_replace('-', '' , $viewData['to']);
               $userAbsences += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
            }
         }
      }
      else 
      {
         $countFrom = str_replace('-', '' , $viewData['from']);
         $countTo = str_replace('-', '' , $viewData['to']);
         $userAbsences += countAbsence($user['username'], $viewData['absid'], $countFrom, $countTo, false, false);
      }
      
      //
      // $count now contains the number of absences for this user. But we want his presences.
      // So we subtract the absensces from the amount of business days.
      //
      $userPresences = $businessDays - $userAbsences;
      
      $data[] = $userPresences;
      $viewData['total'] += $userPresences;
   }
}
else 
{
   //
   // Y-axis: Groups
   //
   $viewData['total'] = 0;
   if ($viewData['groupid'] == "all")
   {
      $groups = $G->getAll('DESC');
      foreach ($groups as $group)
      {
         $groupPresences = 0;
         $labels[] = '"' . $group['name'] . '"';
         $users = $UG->getAllforGroup($group['id']);
         foreach ($users as $user)
         {
            $userAbsences = 0;
            if ($viewData['absid'] == 'all')
            {
               foreach ($viewData['absences'] as $abs)
               {
                  if ($A->get($abs['id']) AND !$A->counts_as_present)
                  {
                     $countFrom = str_replace('-', '' , $viewData['from']);
                     $countTo = str_replace('-', '' , $viewData['to']);
                     $userAbsences += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
                  }
               }
            }
            else
            {
               $countFrom = str_replace('-', '' , $viewData['from']);
               $countTo = str_replace('-', '' , $viewData['to']);
               $userAbsences += countAbsence($user['username'], $viewData['absid'], $countFrom, $countTo, false, false);
            }
            
            //
            // $userCount now contains the number of absences for this user. But we want the presences.
            // So we subtract the absensces from the amount of business days.
            //
            $userPresences = $businessDays - $userAbsences;
            $groupPresences += $userPresences;
         }
         $data[] = $groupPresences;
         $viewData['total'] += $groupPresences;
      }
   }
   else 
   {
      $groupPresences = 0;
      $labels[] = '"' . $G->getNameById($viewData['groupid']) . '"';
      $users = $UG->getAllforGroup($viewData['groupid']);
      foreach ($users as $user)
      {
         $userAbsences = 0;
         if ($viewData['absid'] == 'all')
         {
            foreach ($viewData['absences'] as $abs)
            {
               if ($A->get($abs['id']) AND !$A->counts_as_present)
               {
                  $countFrom = str_replace('-', '' , $viewData['from']);
                  $countTo = str_replace('-', '' , $viewData['to']);
                  $userAbsences += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
               }
            }
         }
         else
         {
            $countFrom = str_replace('-', '' , $viewData['from']);
            $countTo = str_replace('-', '' , $viewData['to']);
            $userAbscences += countAbsence($user['username'], $viewData['absid'], $countFrom, $countTo, false, false);
         }
         
         //
         // $userCount now contains the number of absences for this user. But we want the presences.
         // So we subtract the absensces from the amount of business days.
         //
         $userPresences = $businessDays - $userAbsences;
         $groupPresences += $userPresences;
      }
      
      $data[] = $groupPresences;
      $viewData['total'] += $groupPresences;
   }
}

//
// Build Chart.js labels and data
//
$viewData['labels'] = implode(',', $labels);
$viewData['data'] = implode(',', $data);
if (count($labels)<=10) $viewData['height'] = count($labels) * 20; else $viewData['height'] = count($labels) * 10;

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
