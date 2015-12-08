<?php
/**
 * statspresence.php
 * 
 * Presence statistics page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.005
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

/**
 * ========================================================================
 * Load controller stuff
 */

/**
 * ========================================================================
 * Initialize variables
 */

//
// Standard colors
//
$rgb['blue'] = '32,96,255';
$rgb['cyan'] = '96,200,255';
$rgb['green'] = '96,192,96';
$rgb['magenta'] = '200,96,200';
$rgb['orange'] = '255,179,0';
$rgb['red'] = '255,96,96';

$statsData['labels'] = "";
$statsData['data'] = "";
$statsData['absences'] = $A->getAll();
$statsData['groups'] = $G->getAll('DESC');

//
// Defaults
//
$statsData['region'] = '1';
$statsData['absid'] = 'all';
$statsData['groupid'] = 'all';
$statsData['period'] = 'year';
$statsData['from'] = date("Y") . '-01-01';
$statsData['to'] = date("Y") . '-12-31';
$statsData['scale'] = $C->read('statsScale');
if ($statsData['scale']=='smart') $statsData['scaleSmart'] = $C->read("statsSmartValue");
else                              $statsData['scaleSmart'] = '';
$statsData['scaleMax'] = '';
$statsData['chartjsScaleSettings'] = "scaleOverride: false";
$statsData['yaxis'] = 'users';
$chartColor = $rgb['green'];
$statsData['color'] = 'green';
$statsData['colorHex'] = rgb2hex($rgb['red'],false);
$statsData['defaultColorHex'] = rgb2hex($rgb['red'],false);
$statsData['chartjsColor'] = 'fillColor:"rgba('.$chartColor.',0.5)",strokeColor:"rgba('.$chartColor.',0.8)",highlightFill:"rgba('.$chartColor.',0.75)",highlightStroke:"rgba('.$chartColor.',1)",';

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
      /**
       * ,---------------,
       * | Apply         |
       * '---------------'
       */
      if (isset($_POST['btn_apply']))
      {
         //
         // Read absence type selection
         //
         $statsData['absid'] = $_POST['sel_absence'];
         
         //
         // Read group selection
         //
         $statsData['groupid'] = $_POST['sel_group'];
         $statsData['yaxis'] = $_POST['opt_yaxis'];
          
         //
         // Read period selection
         //
         $statsData['period'] = $_POST['sel_period'];
         if ($statsData['period']=='custom')
         {
            $statsData['from'] = $_POST['txt_from'];
            $statsData['to'] = $_POST['txt_to'];
         }
         
         //
         // Read diagram options
         //
         $statsData['color'] = $_POST['sel_color'];
         if ($statsData['color']=='custom')
         {
            if (isset($_POST['txt_colorHex']) AND strlen($_POST['txt_colorHex']))
            {
               $statsData['colorHex'] = $_POST['txt_colorHex'];
            }
         }
         else
         {
            $statsData['colorHex'] = rgb2hex($rgb[$statsData['color']],false);
         }
         $chartColor = implode(',',hex2rgb($statsData['colorHex']));
         $statsData['chartjsColor'] = 'fillColor:"rgba('.$chartColor.',0.5)",strokeColor:"rgba('.$chartColor.',0.8)",highlightFill:"rgba('.$chartColor.',0.75)",highlightStroke:"rgba('.$chartColor.',1)",';
         
         
         $statsData['scale'] = $_POST['sel_scale'];
         if ($statsData['scale']=='custom')
         {
            if (isset($_POST['txt_scaleMax']) AND strlen($_POST['txt_scaleMax']))
            {
               $statsData['scaleMax'] = $_POST['txt_scaleMax'];
            }
            else
            {
               $statsData['scaleMax'] = '30'; // Default value if none was given
            }
            $statsData['chartjsScaleSettings'] = "scaleOverride: true,scaleSteps: ".$statsData['scaleMax'].",scaleStepWidth: 1,scaleStartValue: 0";
         }
         elseif ($statsData['scale']=='smart')
         {
            if (isset($_POST['txt_scaleSmart']) AND strlen($_POST['txt_scaleSmart']))
            {
               $statsData['scaleSmart'] = $_POST['txt_scaleSmart'];
            }
            else
            {
               $statsData['scaleSmart'] = '4'; // Default value if none was given
            }
         }
         else 
         {
            $statsData['chartjsScaleSettings'] = "scaleOverride: false";
         }
         $statsData['yaxis'] = $_POST['opt_yaxis'];
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
      $alertData['text'] = $LANG['abs_alert_save_failed'];
      $alertData['help'] = '';
   }
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$stepWidth = 1;
switch ($statsData['period'])
{
   case 'year':
      $statsData['from'] = date("Y") . '-01-01';
      $statsData['to'] = date("Y") . '-12-31';
      $stepWidth = 10;
      break;

   case 'half':
      if (date("n") <= 6)
      {
         $statsData['from'] = date("Y") . '-01-01';
         $statsData['to'] = date("Y") . '-06-30';
      }
      else
      {
         $statsData['from'] = date("Y") . '-07-01';
         $statsData['to'] = date("Y") . '-12-31';
      }
      $stepWidth = 5;
      break;
      
   case 'quarter':
      if (date("n") <= 3)
      {
         $statsData['from'] = date("Y") . '-01-01';
         $statsData['to'] = date("Y") . '-03-31';
      }
      elseif (date("n") <= 6)
      {
         $statsData['from'] = date("Y") . '-04-01';
         $statsData['to'] = date("Y") . '-06-30';
      }
      elseif (date("n") <= 9)
      {
         $statsData['from'] = date("Y") . '-07-01';
         $statsData['to'] = date("Y") . '-09-30';
      }
      else
      {
         $statsData['from'] = date("Y") . '-10-01';
         $statsData['to'] = date("Y") . '-12-31';
      }
      $stepWidth = 5;
      break;
      
   case 'month':
      $statsData['from'] = date("Y") . '-' . date("m") . '-01';
      $myts = strtotime($statsData['from']);
      $statsData['to'] = date("Y") . '-' . date("m") . '-' . sprintf('%02d',date("t", $myts));
      break;

   case 'custom':
      // 
      // Nothing to do. POST variables already read.
      //
      break;
}

/**
 * Button titles
 */
if ($statsData['absid']=='all')
{
   $statsData['absName'] = $LANG['all'];
}
else 
{
   $statsData['absName'] = $A->getName($statsData['absid']);
}

if ($statsData['groupid'] == "all")
{
   $statsData['groupName'] = $LANG['all'];
}
else
{
   $statsData['groupName'] = $G->getNameById($_POST['sel_group']);
}

if ($statsData['yaxis'] == "users")
{
   $statsData['groupName'] .= ' ' . $LANG['stats_byusers'];
}
else
{
   $statsData['groupName'] .= ' ' . $LANG['stats_bygroups'];
}

$statsData['periodName'] = $statsData['from'] . ' - ' . $statsData['to']; 
$statsData['scaleName'] = $LANG[$statsData['scale']];

$labels = array();
$data = array();

/**
 * Read data based on yaxis selection
 */
$countFrom = str_replace('-', '' , $statsData['from']);
$countTo = str_replace('-', '' , $statsData['to']);
$businessDays = countBusinessDays($countFrom, $countTo, $statsData['region']);

if ($statsData['yaxis'] == 'users')
{
   /**
    * Y-axis: Users
    */
   $statsData['total'] = 0;
   if ($statsData['groupid'] == "all")
   {
      $users = $U->getAll('lastname', 'firstname', 'DESC', $archive = false, $includeAdmin = false);
   }
   else 
   {
      $users = $UG->getAllforGroup($statsData['groupid']);
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
      if ($statsData['absid'] == 'all')
      {
         foreach ($statsData['absences'] as $abs)
         {
            if ($A->get($abs['id']) AND !$A->counts_as_present)
            {
               $countFrom = str_replace('-', '' , $statsData['from']);
               $countTo = str_replace('-', '' , $statsData['to']);
               $userAbsences += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
            }
         }
      }
      else 
      {
         $countFrom = str_replace('-', '' , $statsData['from']);
         $countTo = str_replace('-', '' , $statsData['to']);
         $userAbsences += countAbsence($user['username'], $statsData['absid'], $countFrom, $countTo, false, false);
      }
      
      //
      // $count now contains the number of absences for this user. But we want his presences.
      // So we subtract the absensces from the amount of business days.
      //
      $userPresences = $businessDays - $userAbsences;
      
      $data[] = $userPresences;
      $statsData['total'] += $userPresences;
   }
}
else 
{
   /**
    * Y-axis: Groups
    */
   $statsData['total'] = 0;
   if ($statsData['groupid'] == "all")
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
            if ($statsData['absid'] == 'all')
            {
               foreach ($statsData['absences'] as $abs)
               {
                  if ($A->get($abs['id']) AND !$A->counts_as_present)
                  {
                     $countFrom = str_replace('-', '' , $statsData['from']);
                     $countTo = str_replace('-', '' , $statsData['to']);
                     $userAbsences += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
                  }
               }
            }
            else
            {
               $countFrom = str_replace('-', '' , $statsData['from']);
               $countTo = str_replace('-', '' , $statsData['to']);
               $userAbscences += countAbsence($user['username'], $statsData['absid'], $countFrom, $countTo, false, false);
            }
            
            //
            // $userCount now contains the number of absences for this user. But we want the presences.
            // So we subtract the absensces from the amount of business days.
            //
            $userPresences = $businessDays - $userAbsences;
            $groupPresences += $userPresences;
         }
         $data[] = $groupPresences;
         $statsData['total'] += $groupPresences;
      }
   }
   else 
   {
      $groupPresences = 0;
      $labels[] = '"' . $G->getNameById($statsData['groupid']) . '"';
      $users = $UG->getAllforGroup($statsData['groupid']);
      foreach ($users as $user)
      {
         $userAbsences = 0;
         if ($statsData['absid'] == 'all')
         {
            foreach ($statsData['absences'] as $abs)
            {
               if ($A->get($abs['id']) AND !$A->counts_as_present)
               {
                  $countFrom = str_replace('-', '' , $statsData['from']);
                  $countTo = str_replace('-', '' , $statsData['to']);
                  $userAbsences += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
               }
            }
         }
         else
         {
            $countFrom = str_replace('-', '' , $statsData['from']);
            $countTo = str_replace('-', '' , $statsData['to']);
            $userAbscences += countAbsence($user['username'], $statsData['absid'], $countFrom, $countTo, false, false);
         }
         
         //
         // $userCount now contains the number of absences for this user. But we want the presences.
         // So we subtract the absensces from the amount of business days.
         //
         $userPresences = $businessDays - $userAbsences;
         $groupPresences += $userPresences;
      }
      
      $data[] = $groupPresences;
      $statsData['total'] += $groupPresences;
   }
}

/**
 * Build Chart.js labels and data
 */
$statsData['labels'] = implode(',', $labels);
$statsData['data'] = implode(',', $data);
if ($statsData['scale']=='smart')
{
   $scaleSteps = max($data) + $statsData['scaleSmart'];
   $statsData['chartjsScaleSettings'] = "scaleOverride: true,scaleSteps: ".($scaleSteps/$stepWidth).",scaleStepWidth: ".$stepWidth.",scaleStartValue: 0";
}

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
