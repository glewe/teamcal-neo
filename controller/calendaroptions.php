<?php
/**
 * configcalendar.php
 * 
 * Calendar config page controller
 *
 * @category TeamCal Neo 
 * @version 0.3.002
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

/**
 * ========================================================================
 * Process form
 */
/**
 * ,-------,
 * | Apply |
 * '-------'
 */
if (isset($_POST['btn_caloptApply']))
{
   /**
    * Display
    */
   if ($_POST['opt_showMonths']) $C->save("showMonths", $_POST['opt_showMonths']);
   $C->save("todayBorderColor", sanitize($_POST['txt_todayBorderColor']));
   $C->save("todayBorderSize", intval($_POST['txt_todayBorderSize']));
   $C->save("pastDayColor", sanitize($_POST['txt_pastDayColor']));
   if (isset($_POST['chk_showWeekNumbers']) && $_POST['chk_showWeekNumbers']) $C->save("showWeekNumbers", "1"); else $C->save("showWeekNumbers", "0");
   $C->save("repeatHeaderCount", intval($_POST['txt_repeatHeaderCount']));
   $C->save("usersPerPage", intval($_POST['txt_usersPerPage']));
   if (isset($_POST['chk_userSearch']) && $_POST['chk_userSearch']) $C->save("userSearch", "1"); else $C->save("userSearch", "0");
   if (isset($_POST['chk_supportMobile']) && $_POST['chk_supportMobile']) $C->save("supportMobile", "1"); else $C->save("supportMobile", "0");
    
   /**
    * Filter
    */
   if (isset($_POST['chk_hideDaynotes']) && $_POST['chk_hideDaynotes']) $C->save("hideDaynotes", "1"); else $C->save("hideDaynotes", "0");
   if (isset($_POST['chk_hideManagers']) && $_POST['chk_hideManagers']) $C->save("hideManagers", "1"); else $C->save("hideManagers", "0");
   if (isset($_POST['chk_hideManagerOnlyAbsences'])) $C->save("hideManagerOnlyAbsences", "1"); else $C->save("hideManagerOnlyAbsences", "0");
   if (isset($_POST['chk_showUserRegion']) && $_POST['chk_showUserRegion']) $C->save("showUserRegion", "1"); else $C->save("showUserRegion", "0");
   if (isset($_POST['chk_markConfidential'])) $C->save("markConfidential", "1"); else $C->save("markConfidential", "0");
    
   /**
    * Options
    */
   if ($_POST['opt_firstDayOfWeek']) $C->save("firstDayOfWeek", $_POST['opt_firstDayOfWeek']);
   if (isset($_POST['chk_satBusi']) && $_POST['chk_satBusi']) $C->save("satBusi", "1"); else $C->save("satBusi", "0");
   if (isset($_POST['chk_sunBusi']) && $_POST['chk_sunBusi']) $C->save("sunBusi", "1"); else $C->save("sunBusi", "0");
   if ($_POST['sel_defregion']) $C->save("defregion", $_POST['sel_defregion']); else $C->save("defregion", "default");
   if ($_POST['opt_defgroupfilter']) $C->save("defgroupfilter", $_POST['opt_defgroupfilter']); else $C->save("defgroupfilter", 'All');
    
   /**
    * Remainder
    */
   if (isset($_POST['chk_includeRemainder']) && $_POST['chk_includeRemainder']) $C->save("includeRemainder", "1"); else $C->save("includeRemainder", "0");
   if (isset($_POST['chk_includeRemainderTotal']) && $_POST['chk_includeRemainderTotal']) $C->save("includeRemainderTotal", "1"); else $C->save("includeRemainderTotal", "0");
   if (isset($_POST['chk_includeTotals']) && $_POST['chk_includeTotals']) $C->save("includeTotals", "1"); else $C->save("includeTotals", "0");
   if (isset($_POST['chk_showRemainder']) && $_POST['chk_showRemainder']) $C->save("showRemainder", "1"); else $C->save("showRemainder", "0");
    
   /**
    * Summary
    */
   if (isset($_POST['chk_includeSummary']) && $_POST['chk_includeSummary']) $C->save("includeSummary", "1"); else $C->save("includeSummary", "0");
   if (isset($_POST['chk_showSummary']) && $_POST['chk_showSummary']) $C->save("showSummary", "1"); else $C->save("showSummary", "0");
    
   /**
    * Log this event
    */
   $LOG->log("logCalendarOptions", $UL->username, "log_calopt");
   header("Location: index.php?action=".$controller);
}

/**
 * ========================================================================
 * Prepare data for the view
 */
$caloptData['display'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'showMonths', 'type' => 'radio', 'values' => array ('1', '2', '3', '6', '12'), 'value' => $C->read("showMonths") ),
   array ( 'prefix' => 'calopt', 'name' => 'todayBorderColor', 'type' => 'color', 'value' => $C->read("todayBorderColor"), 'maxlength' => '6' ),
   array ( 'prefix' => 'calopt', 'name' => 'todayBorderSize', 'type' => 'text', 'value' => $C->read("todayBorderSize"), 'maxlength' => '2' ),
   array ( 'prefix' => 'calopt', 'name' => 'pastDayColor', 'type' => 'color', 'value' => $C->read("pastDayColor"), 'maxlength' => '6' ),
   array ( 'prefix' => 'calopt', 'name' => 'showWeekNumbers', 'type' => 'check', 'values' => '', 'value' => $C->read("showWeekNumbers") ),
   array ( 'prefix' => 'calopt', 'name' => 'repeatHeaderCount', 'type' => 'text', 'value' => $C->read("repeatHeaderCount"), 'maxlength' => '4' ),
   array ( 'prefix' => 'calopt', 'name' => 'usersPerPage', 'type' => 'text', 'value' => $C->read("usersPerPage"), 'maxlength' => '4' ),
   array ( 'prefix' => 'calopt', 'name' => 'userSearch', 'type' => 'check', 'values' => '', 'value' => $C->read("userSearch") ),
   array ( 'prefix' => 'calopt', 'name' => 'supportMobile', 'type' => 'check', 'values' => '', 'value' => $C->read("supportMobile") ),
   );

$caloptData['filter'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'hideManagers', 'type' => 'check', 'values' => '', 'value' => $C->read("hideManagers") ),
   array ( 'prefix' => 'calopt', 'name' => 'hideDaynotes', 'type' => 'check', 'values' => '', 'value' => $C->read("hideDaynotes") ),
   array ( 'prefix' => 'calopt', 'name' => 'hideManagerOnlyAbsences', 'type' => 'check', 'values' => '', 'value' => $C->read("hideManagerOnlyAbsences") ),
   array ( 'prefix' => 'calopt', 'name' => 'showUserRegion', 'type' => 'check', 'values' => '', 'value' => $C->read("showUserRegion") ),
   array ( 'prefix' => 'calopt', 'name' => 'markConfidential', 'type' => 'check', 'values' => '', 'value' => $C->read("markConfidential") ),
);

$regions = $R->getAllNames();
foreach ($regions as $region)
{
   $caloptData['regionList'][] = array ('val' => $region, 'name' => $region, 'selected' => ($C->read("defregion") == $region)?true:false );
}
$caloptData['options'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'firstDayOfWeek', 'type' => 'radio', 'values' => array ('1', '7'), 'value' => $C->read("firstDayOfWeek") ),
   array ( 'prefix' => 'calopt', 'name' => 'satBusi', 'type' => 'check', 'values' => '', 'value' => $C->read("satBusi") ),
   array ( 'prefix' => 'calopt', 'name' => 'sunBusi', 'type' => 'check', 'values' => '', 'value' => $C->read("sunBusi") ),
   array ( 'prefix' => 'calopt', 'name' => 'defregion', 'type' => 'list', 'values' => $caloptData['regionList'] ),
   array ( 'prefix' => 'calopt', 'name' => 'defgroupfilter', 'type' => 'radio', 'values' => array ('all', 'allbygroup'), 'value' => $C->read("defgroupfilter") ),
);

$caloptData['remainder'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'includeRemainder', 'type' => 'check', 'values' => '', 'value' => $C->read("includeRemainder") ),
   array ( 'prefix' => 'calopt', 'name' => 'includeRemainderTotal', 'type' => 'check', 'values' => '', 'value' => $C->read("includeRemainderTotal") ),
   array ( 'prefix' => 'calopt', 'name' => 'includeTotals', 'type' => 'check', 'values' => '', 'value' => $C->read("includeTotals") ),
   array ( 'prefix' => 'calopt', 'name' => 'showRemainder', 'type' => 'check', 'values' => '', 'value' => $C->read("showRemainder") ),
);

$caloptData['summary'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'includeSummary', 'type' => 'check', 'values' => '', 'value' => $C->read("includeSummary") ),
   array ( 'prefix' => 'calopt', 'name' => 'showSummary', 'type' => 'check', 'values' => '', 'value' => $C->read("showSummary") ),
);

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
