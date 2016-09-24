<?php
/**
 * statsabstype.php
 * 
 * Absence type statistics page controller
 *
 * @category TeamCal Neo 
 * @version 0.9.010
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
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

//
// Standard colors
//
$rgb['blue'] = '32,96,255';
$rgb['cyan'] = '96,200,255';
$rgb['green'] = '96,192,96';
$rgb['magenta'] = '200,96,200';
$rgb['orange'] = '255,179,0';
$rgb['red'] = '255,96,96';

$viewData['labels'] = "";
$viewData['data'] = "";
$viewData['absences'] = $A->getAll();
$viewData['groups'] = $G->getAll('DESC');

//
// Defaults
//
$viewData['groupid'] = 'all';
$viewData['period'] = 'year';
$viewData['from'] = date("Y") . '-01-01';
$viewData['to'] = date("Y") . '-12-31';
$viewData['scale'] = $C->read('statsScale');
if ($viewData['scale']=='smart') $viewData['scaleSmart'] = $C->read("statsSmartValue");
else                              $viewData['scaleSmart'] = '';
$viewData['scaleMax'] = '';
$viewData['chartjsScaleSettings'] = "scaleOverride: false";
$viewData['yaxis'] = 'users';
$chartColor = $rgb['cyan'];
$viewData['color'] = 'cyan';
$viewData['colorHex'] = rgb2hex($rgb['cyan'],false);
$viewData['defaultColorHex'] = rgb2hex($rgb['cyan'],false);
$viewData['chartjsColor'] = 'fillColor:"rgba('.$chartColor.',0.5)",strokeColor:"rgba('.$chartColor.',0.8)",highlightFill:"rgba('.$chartColor.',0.75)",highlightStroke:"rgba('.$chartColor.',1)",';

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
         // Read group selection
         //
         $viewData['groupid'] = $_POST['sel_group'];
          
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
         if ($viewData['color']=='custom')
         {
            if (isset($_POST['txt_colorHex']) AND strlen($_POST['txt_colorHex']))
            {
               $viewData['colorHex'] = $_POST['txt_colorHex'];
            }
         }
         else
         {
            $viewData['colorHex'] = rgb2hex($rgb[$viewData['color']],false);
         }
         $chartColor = implode(',',hex2rgb($viewData['colorHex']));
         $viewData['chartjsColor'] = 'fillColor:"rgba('.$chartColor.',0.5)",strokeColor:"rgba('.$chartColor.',0.8)",highlightFill:"rgba('.$chartColor.',0.75)",highlightStroke:"rgba('.$chartColor.',1)",';
         
         
         $viewData['scale'] = $_POST['sel_scale'];
         if ($viewData['scale']=='custom')
         {
            if (isset($_POST['txt_scaleMax']) AND strlen($_POST['txt_scaleMax']))
            {
               $viewData['scaleMax'] = $_POST['txt_scaleMax'];
            }
            else
            {
               $viewData['scaleMax'] = '30'; // Default value if none was given
            }
            $viewData['chartjsScaleSettings'] = "scaleOverride: true,scaleSteps: ".$viewData['scaleMax'].",scaleStepWidth: 1,scaleStartValue: 0";
         }
         elseif ($viewData['scale']=='smart')
         {
            if (isset($_POST['txt_scaleSmart']) AND strlen($_POST['txt_scaleSmart']))
            {
               $viewData['scaleSmart'] = $_POST['txt_scaleSmart'];
            }
            else
            {
               $viewData['scaleSmart'] = '4'; // Default value if none was given
            }
         }
         else 
         {
            $viewData['chartjsScaleSettings'] = "scaleOverride: false";
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
      $alertData['text'] = $LANG['abs_alert_save_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
switch ($viewData['period'])
{
   case 'year':
      $viewData['from'] = date("Y") . '-01-01';
      $viewData['to'] = date("Y") . '-12-31';
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
if ($viewData['groupid'] == "all") $viewData['groupName'] = $LANG['all'];
else                                $viewData['groupName'] = $G->getNameById($_POST['sel_group']);

$viewData['periodName'] = $viewData['from'] . ' - ' . $viewData['to']; 
$viewData['scaleName'] = $LANG[$viewData['scale']];

$labels = array();
$data = array();

//
// Loop through all absence types (that count as absent)
//
$viewData['total'] = 0;
foreach ($viewData['absences'] as $abs)
{
   if ($A->get($abs['id']) AND !$A->counts_as_present)
   {
      $labels[] = '"' . $abs['name'] . '"';
      $count = 0;
      if ($viewData['groupid'] == "all")
      {
         //
         // Count for all groups
         //
         foreach ($viewData['groups'] as $group)
         {
            $users = $UG->getAllforGroup($group['id']);
            foreach ($users as $user)
            {
               $countFrom = str_replace('-', '' , $viewData['from']);
               $countTo = str_replace('-', '' , $viewData['to']);
               $count += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
            }
         }
         $data[] = $count;
         $viewData['total'] += $count;
      }
      else 
      {
         //
         // Count for a specific groups
         //
         $users = $UG->getAllforGroup($viewData['groupid']);
         foreach ($users as $user)
         {
            $countFrom = str_replace('-', '' , $viewData['from']);
            $countTo = str_replace('-', '' , $viewData['to']);
            $count += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
         }
         $data[] = $count;
         $viewData['total'] += $count;
      }
   }
}

//
// Build Chart.js labels and data
//
$viewData['labels'] = implode(',', $labels);
$viewData['data'] = implode(',', $data);
if ($viewData['scale']=='smart')
{
   $scaleSteps = max($data) + $viewData['scaleSmart'];
   $viewData['chartjsScaleSettings'] = "scaleOverride: true,scaleSteps: ".$scaleSteps.",scaleStepWidth: 1,scaleStartValue: 0";
}

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
