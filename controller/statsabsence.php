<?php
/**
 * statistics.php
 * 
 * Statistics page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.004
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
$statsData['blue'] = '96,96,255';
$statsData['cyan'] = '96,200,255';
$statsData['green'] = '96,192,96';
$statsData['magenta'] = '200,96,200';
$statsData['orange'] = '255,179,0';
$statsData['red'] = '255,96,96';

$statsData['colorSet']['blue'] = 'fillColor:"rgba('.$statsData['blue'].',0.5)",strokeColor:"rgba('.$statsData['blue'].',0.8)",highlightFill:"rgba('.$statsData['blue'].',0.75)",highlightStroke:"rgba('.$statsData['blue'].',1)",';
$statsData['colorSet']['cyan'] = 'fillColor:"rgba('.$statsData['cyan'].',0.5)",strokeColor:"rgba('.$statsData['cyan'].',0.8)",highlightFill:"rgba('.$statsData['cyan'].',0.75)",highlightStroke:"rgba('.$statsData['cyan'].',1)",';
$statsData['colorSet']['green'] = 'fillColor:"rgba('.$statsData['green'].',0.5)",strokeColor:"rgba('.$statsData['green'].',0.8)",highlightFill:"rgba('.$statsData['green'].',0.75)",highlightStroke:"rgba('.$statsData['green'].',1)",';
$statsData['colorSet']['magenta'] = 'fillColor:"rgba('.$statsData['magenta'].',0.5)",strokeColor:"rgba('.$statsData['magenta'].',0.8)",highlightFill:"rgba('.$statsData['magenta'].',0.75)",highlightStroke:"rgba('.$statsData['magenta'].',1)",';
$statsData['colorSet']['orange'] = 'fillColor:"rgba('.$statsData['orange'].',0.5)",strokeColor:"rgba('.$statsData['orange'].',0.8)",highlightFill:"rgba('.$statsData['orange'].',0.75)",highlightStroke:"rgba('.$statsData['orange'].',1)",';
$statsData['colorSet']['red'] = 'fillColor:"rgba('.$statsData['red'].',0.5)",strokeColor:"rgba('.$statsData['red'].',0.8)",highlightFill:"rgba('.$statsData['red'].',0.75)",highlightStroke:"rgba('.$statsData['red'].',1)",';

$statsData['labels'] = "";
$statsData['data'] = "";

$statsData['absences'] = $A->getAll();
$statsData['groups'] = $G->getAll();

$statsData['absid'] = 'all';
$statsData['groupid'] = 'all';
$statsData['period'] = 'year';
$statsData['from'] = date("Y") . '-01-01';
$statsData['to'] = date("Y") . '-12-31';
$statsData['scale'] = 'auto';
$statsData['scaleSmart'] = '';
$statsData['scaleMax'] = '';
$statsData['chartjsScaleSettings'] = "scaleOverride: false";


$users = $U->getAll('lastname', 'firstname', 'DESC', $archive = false, $includeAdmin = false);

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
         // Read scale selection
         //
         $statsData['scale'] = $_POST['sel_scale'];
         if ($statsData['scale']=='custom')
         {
            if (strlen($_POST['txt_scaleMax']))
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
            if (strlen($_POST['txt_scaleSmart']))
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
switch ($statsData['period'])
{
   case 'year':
      $statsData['from'] = date("Y") . '-01-01';
      $statsData['to'] = date("Y") . '-12-31';
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
      break;
      
   case 'month':
      $statsData['from'] = date("Y") . '-' . date("m") . '-01';
      $statsData['to'] = date("Y") . '-' . date("m") . '-' . sprintf('%02d',cal_days_in_month(CAL_GREGORIAN, date("j"), date("Y")));
      break;

   case 'custom':
      // 
      // Nothing to do. POST variables already read.
      //
      break;
}

/**
 * Build the name pieces for the buttons
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
   $users = $U->getAll('lastname', 'firstname', 'DESC', $archive = false, $includeAdmin = false);
}
else
{
   $statsData['groupName'] = $G->getNameById($_POST['sel_group']);
   $users = $UG->getAllForGroup($_POST['sel_group']);
}

$statsData['periodName'] = $statsData['from'] . ' - ' . $statsData['to']; 

$statsData['scaleName'] = $LANG[$statsData['scale']];

/**
 * Load active users
 */
$statsData['users'] = array();
$labels = array();
$values = array();
foreach ($users as $user)
{
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
            $count += countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
         }
      }
   }
   else 
   {
      $countFrom = str_replace('-', '' , $statsData['from']);
      $countTo = str_replace('-', '' , $statsData['to']);
      $count += countAbsence($user['username'], $statsData['absid'], $countFrom, $countTo, false, false);
   }

   // Add count to array
   $values[] = $count;
}

/**
 * Build Chart.js labels and data
 */
$statsData['labels'] = implode(',', $labels);
$statsData['data'] = implode(',', $values);
if ($statsData['scale']=='smart')
{
   $scaleSteps = max($values) + $statsData['scaleSmart'];
   $statsData['chartjsScaleSettings'] = "scaleOverride: true,scaleSteps: ".$scaleSteps.",scaleStepWidth: 1,scaleStartValue: 0";
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
