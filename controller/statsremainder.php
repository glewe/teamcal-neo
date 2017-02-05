<?php
/**
 * statsremainder.php
 * 
 * Remainder statistics page controller
 *
 * @category TeamCal Neo 
 * @version 1.3.006
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
$viewData['groupid'] = 'all';
$viewData['year'] = date("Y");
$viewData['from'] = date("Y") . '-01-01';
$viewData['to'] = date("Y") . '-12-31';
$viewData['yaxis'] = 'users';
$viewData['color'] = 'orange';
if($color=$C->read("statsDefaultColorRemainder")) $viewData['color'] = $color; else $viewData['color'] = 'orange';

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
      if (!formInputValid('txt_periodYear', 'numeric')) $inputError = true;
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
         // Read group selection
         //
         $viewData['groupid'] = $_POST['sel_group'];
          
         //
         // Read year
         //
         $viewData['year'] = $_POST['sel_year'];
         
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
$viewData['from'] = $viewData['year'] . '-01-01';
$viewData['to'] = $viewData['year'] . '-12-31';

//
// Button titles
//
if ($viewData['groupid'] == "all") $viewData['groupName'] = $LANG['all'];
else                                $viewData['groupName'] = $G->getNameById($_POST['sel_group']);

$viewData['periodName'] = $viewData['from'] . ' - ' . $viewData['to']; 

$labels = array();
$dataAllowance = array();
$dataRemainder = array();

//
// Loop through all absence types (that count as absent and that have an allowance)
//
$viewData['total'] = 0;
foreach ($viewData['absences'] as $abs)
{
   if ($A->get($abs['id']) AND !$A->counts_as_present AND intval($A->allowance) > 0 )
   {
      $labels[] = '"' . $abs['name'] . '"';
      $absenceAllowance = intval($A->allowance);
      
      if ($viewData['groupid'] == "all")
      {
         //
         // Count for all groups
         //
         $totalAbsenceAllowance = 0;
         $totalGroupRemainder = 0;
         foreach ($viewData['groups'] as $group)
         {
            $users = $UG->getAllforGroup($group['id']);
            $userCount = count($users);
            $totalAbsenceAllowance += $absenceAllowance * $userCount;
            $groupRemainder = $absenceAllowance * $userCount;
            foreach ($users as $user)
            {
               $countFrom = str_replace('-', '' , $viewData['from']);
               $countTo = str_replace('-', '' , $viewData['to']);
               $groupRemainder -= countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
            }
            $totalGroupRemainder += $groupRemainder;
         }
         $dataAllowance[] = $totalAbsenceAllowance;
         $dataRemainder[] = $totalGroupRemainder;
         $viewData['total'] += $totalGroupRemainder;
      }
      else 
      {
         //
         // Count for a specific group
         //
         $users = $UG->getAllforGroup($viewData['groupid']);
         $userCount = count($users);
         $groupRemainder = $absenceAllowance * $userCount;
         $dataAllowance[] = $groupRemainder;
         foreach ($users as $user)
         {
            $countFrom = str_replace('-', '' , $viewData['from']);
            $countTo = str_replace('-', '' , $viewData['to']);
            $groupRemainder -= countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
         }
         $dataRemainder[] = $groupRemainder;
         $viewData['total'] += $groupRemainder;
      }
   }
}

//
// Build Chart.js labels and data
//
$viewData['labels'] = implode(',', $labels);
$viewData['dataAllowance'] = implode(',', $dataAllowance);
$viewData['dataRemainder'] = implode(',', $dataRemainder);
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
