<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;
use DateTime;

/**
 * Calendar Options Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class CalendarOptionsController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    $allConfig = $this->allConfig;

    if (!isAllowed($this->CONF['controllers']['calendaroptions']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License (Randomly)
    $date    = new DateTime();
    $weekday = $date->format('N');
    if ($weekday == (string) rand(1, 7)) {
      $alertData        = [];
      $showAlert        = false;
      $licExpiryWarning = (int) $this->C->read('licExpiryWarning');
      $LIC              = new LicenseModel($this->DB->db, $this->CONF);
      $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);
    }

    $viewData               = [];
    $viewData['pageHelp']   = $allConfig['pageHelp'];
    $viewData['showAlerts'] = $allConfig['showAlerts'];

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && isset($_POST['btn_apply'])) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (!formInputValid('txt_pastDayColor', 'hex_color'))
        $inputError = true;
      if (!formInputValid('txt_regionalHolidaysColor', 'hex_color'))
        $inputError = true;
      if (!formInputValid('txt_todayBorderColor', 'hex_color'))
        $inputError = true;
      if (!formInputValid('txt_summaryAbsenceTextColor', 'hex_color'))
        $inputError = true;
      if (!formInputValid('txt_summaryPresenceTextColor', 'hex_color'))
        $inputError = true;

      if (!$inputError) {
        $newConfig                      = [];
        $newConfig["todayBorderColor"]  = ltrim(sanitize($_POST['txt_todayBorderColor']), '#');
        $newConfig["todayBorderSize"]   = intval($_POST['txt_todayBorderSize']);
        $newConfig["pastDayColor"]      = ltrim(sanitize($_POST['txt_pastDayColor']), '#');
        $newConfig["showWeekNumbers"]   = (isset($_POST['chk_showWeekNumbers']) && $_POST['chk_showWeekNumbers']) ? "1" : "0";
        $newConfig["repeatHeaderCount"] = intval($_POST['txt_repeatHeaderCount']);
        $newConfig["usersPerPage"]      = intval($_POST['txt_usersPerPage']);
        $newConfig["showAvatars"]       = (isset($_POST['chk_showAvatars']) && $_POST['chk_showAvatars']) ? "1" : "0";
        $newConfig["showRoleIcons"]     = (isset($_POST['chk_showRoleIcons']) && $_POST['chk_showRoleIcons']) ? "1" : "0";
        $newConfig["showTooltipCount"]  = (isset($_POST['chk_showTooltipCount']) && $_POST['chk_showTooltipCount']) ? "1" : "0";
        $newConfig["supportMobile"]     = (isset($_POST['chk_supportMobile']) && $_POST['chk_supportMobile']) ? "1" : "0";
        $newConfig["symbolAsIcon"]      = (isset($_POST['chk_symbolAsIcon']) && $_POST['chk_symbolAsIcon']) ? "1" : "0";
        if (isset($_POST['sel_monitorAbsence'])) {
          $newConfig["monitorAbsence"] = implode(',', $_POST['sel_monitorAbsence']);
        }
        else {
          $newConfig["monitorAbsence"] = '0';
        }
        $newConfig["calendarFontSize"] = strlen($_POST['txt_calendarFontSize']) ? intval($_POST['txt_calendarFontSize']) : 100;

        if (strlen($_POST['txt_showMonths'])) {
          $postValue               = intval($_POST['txt_showMonths']);
          $newConfig["showMonths"] = max(1, min(12, $postValue));
        }
        else {
          $newConfig["showMonths"] = 1;
        }

        $newConfig["regionalHolidays"]      = (isset($_POST['chk_regionalHolidays']) && $_POST['chk_regionalHolidays']) ? "1" : "0";
        $newConfig["regionalHolidaysColor"] = ltrim(sanitize($_POST['txt_regionalHolidaysColor']), '#');
        $newConfig["sortByOrderKey"]        = (isset($_POST['chk_sortByOrderKey']) && $_POST['chk_sortByOrderKey']) ? "1" : "0";

        $newConfig["hideDaynotes"]            = (isset($_POST['chk_hideDaynotes']) && $_POST['chk_hideDaynotes']) ? "1" : "0";
        $newConfig["hideManagers"]            = (isset($_POST['chk_hideManagers']) && $_POST['chk_hideManagers']) ? "1" : "0";
        $newConfig["hideManagerOnlyAbsences"] = (isset($_POST['chk_hideManagerOnlyAbsences']) && $_POST['chk_hideManagerOnlyAbsences']) ? "1" : "0";
        $newConfig["showUserRegion"]          = (isset($_POST['chk_showUserRegion']) && $_POST['chk_showUserRegion']) ? "1" : "0";

        if (isset($_POST['sel_trustedRoles'])) {
          $newConfig["trustedRoles"] = implode(',', $_POST['sel_trustedRoles']);
        }

        if ($_POST['opt_firstDayOfWeek'])
          $newConfig["firstDayOfWeek"] = $_POST['opt_firstDayOfWeek'];
        $newConfig["satBusi"]          = (isset($_POST['chk_satBusi']) && $_POST['chk_satBusi']) ? "1" : "0";
        $newConfig["sunBusi"]          = (isset($_POST['chk_sunBusi']) && $_POST['chk_sunBusi']) ? "1" : "0";
        $newConfig["defregion"]        = $_POST['sel_defregion'] ? $_POST['sel_defregion'] : "default";
        $newConfig["showRegionButton"] = (isset($_POST['chk_showRegionButton']) && $_POST['chk_showRegionButton']) ? "1" : "0";
        $newConfig["defgroupfilter"]   = $_POST['opt_defgroupfilter'] ? $_POST['opt_defgroupfilter'] : 'All';
        $newConfig["currentYearOnly"]  = (isset($_POST['chk_currentYearOnly']) && $_POST['chk_currentYearOnly']) ? "1" : "0";

        if (isset($_POST['sel_currentYearRoles'])) {
          $newConfig["currYearRoles"] = implode(',', $_POST['sel_currentYearRoles']);
        }

        $newConfig["takeover"]                         = (isset($_POST['chk_takeover']) && $_POST['chk_takeover']) ? "1" : "0";
        $newConfig["notificationsAllGroups"]           = (isset($_POST['chk_notificationsAllGroups']) && $_POST['chk_notificationsAllGroups']) ? "1" : "0";
        $newConfig["managerOnlyIncludesAdministrator"] = (isset($_POST['chk_managerOnlyIncludesAdministrator']) && $_POST['chk_managerOnlyIncludesAdministrator']) ? "1" : "0";

        $newConfig["statsDefaultColorAbsences"]     = $_POST['sel_statsDefaultColorAbsences'] ? $_POST['sel_statsDefaultColorAbsences'] : "red";
        $newConfig["statsDefaultColorPresences"]    = $_POST['sel_statsDefaultColorPresences'] ? $_POST['sel_statsDefaultColorPresences'] : "green";
        $newConfig["statsDefaultColorAbsencetype"]  = $_POST['sel_statsDefaultColorAbsencetype'] ? $_POST['sel_statsDefaultColorAbsencetype'] : "cyan";
        $newConfig["statsDefaultColorPresencetype"] = $_POST['sel_statsDefaultColorPresencetype'] ? $_POST['sel_statsDefaultColorPresencetype'] : "green";
        $newConfig["statsDefaultColorRemainder"]    = $_POST['sel_statsDefaultColorRemainder'] ? $_POST['sel_statsDefaultColorRemainder'] : "orange";
        $newConfig["statsDefaultColorTrends"]       = $_POST['sel_statsDefaultColorTrends'] ? $_POST['sel_statsDefaultColorTrends'] : "red";
        $newConfig["statsDefaultColorDayofweek"]    = $_POST['sel_statsDefaultColorDayofweek'] ? $_POST['sel_statsDefaultColorDayofweek'] : "purple";
        $newConfig["statsDefaultColorDuration"]     = $_POST['sel_statsDefaultColorDuration'] ? $_POST['sel_statsDefaultColorDuration'] : "orange";

        $newConfig["includeSummary"]           = (isset($_POST['chk_includeSummary']) && $_POST['chk_includeSummary']) ? "1" : "0";
        $newConfig["showSummary"]              = (isset($_POST['chk_showSummary']) && $_POST['chk_showSummary']) ? "1" : "0";
        $newConfig["summaryAbsenceTextColor"]  = ltrim(sanitize($_POST['txt_summaryAbsenceTextColor']), '#');
        $newConfig["summaryPresenceTextColor"] = ltrim(sanitize($_POST['txt_summaryPresenceTextColor']), '#');

        $this->C->saveBatch($newConfig);
        $this->_instances['allConfig'] = array_merge($this->allConfig, $newConfig);
        $allConfig                     = $this->allConfig;
        $this->LOG->logEvent("logCalendarOptions", $this->UL->username, "log_calopt");

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['calopt_title'];
        $alertData['text']    = $this->LANG['calopt_alert_edit_success'];
        $alertData['help']    = '';

        if (isset($_SESSION))
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['calopt_title'];
        $alertData['text']    = $this->LANG['calopt_alert_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    // Prepare View Data
    $absences                  = $this->A->getAll();
    $caloptData                = [];
    $arrMonitorAbs             = explode(',', (string) $allConfig['monitorAbsence']);
    $caloptData['absenceList'] = [];
    foreach ($absences as $abs) {
      $caloptData['absenceList'][] = ['val' => $abs['id'], 'name' => $abs['name'], 'selected' => in_array($abs['id'], $arrMonitorAbs)];
    }

    $caloptData['display'] = [
      ['label' => $this->LANG['calopt_todayBorderColor'], 'prefix' => 'calopt', 'name' => 'todayBorderColor', 'type' => 'coloris', 'value' => (!empty($allConfig['todayBorderColor']) ? '#' . $allConfig['todayBorderColor'] : ''), 'maxlength' => '6', 'error' => (isset($inputAlert['todayBorderColor']) ? $inputAlert['todayBorderColor'] : '')],
      ['label' => $this->LANG['calopt_todayBorderSize'], 'prefix' => 'calopt', 'name' => 'todayBorderSize', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['todayBorderSize'], 'maxlength' => '2'],
      ['label' => $this->LANG['calopt_pastDayColor'], 'prefix' => 'calopt', 'name' => 'pastDayColor', 'type' => 'coloris', 'value' => (!empty($allConfig['pastDayColor']) ? '#' . $allConfig['pastDayColor'] : ''), 'maxlength' => '6', 'error' => (isset($inputAlert['pastDayColor']) ? $inputAlert['pastDayColor'] : '')],
      ['label' => $this->LANG['calopt_showWeekNumbers'], 'prefix' => 'calopt', 'name' => 'showWeekNumbers', 'type' => 'check', 'values' => '', 'value' => $allConfig['showWeekNumbers']],
      ['label' => $this->LANG['calopt_repeatHeaderCount'], 'prefix' => 'calopt', 'name' => 'repeatHeaderCount', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['repeatHeaderCount'], 'maxlength' => '4'],
      ['label' => $this->LANG['calopt_usersPerPage'], 'prefix' => 'calopt', 'name' => 'usersPerPage', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['usersPerPage'], 'maxlength' => '4'],
      ['label' => $this->LANG['calopt_showAvatars'], 'prefix' => 'calopt', 'name' => 'showAvatars', 'type' => 'check', 'values' => '', 'value' => $allConfig['showAvatars']],
      ['label' => $this->LANG['calopt_showRoleIcons'], 'prefix' => 'calopt', 'name' => 'showRoleIcons', 'type' => 'check', 'values' => '', 'value' => $allConfig['showRoleIcons']],
      ['label' => $this->LANG['calopt_showTooltipCount'], 'prefix' => 'calopt', 'name' => 'showTooltipCount', 'type' => 'check', 'values' => '', 'value' => $allConfig['showTooltipCount']],
      ['label' => $this->LANG['calopt_supportMobile'], 'prefix' => 'calopt', 'name' => 'supportMobile', 'type' => 'check', 'values' => '', 'value' => $allConfig['supportMobile']],
      ['label' => $this->LANG['calopt_symbolAsIcon'], 'prefix' => 'calopt', 'name' => 'symbolAsIcon', 'type' => 'check', 'values' => '', 'value' => $allConfig['symbolAsIcon']],
      ['label' => $this->LANG['calopt_monitorAbsence'], 'prefix' => 'calopt', 'name' => 'monitorAbsence', 'type' => 'listmulti', 'values' => $caloptData['absenceList']],
      ['label' => $this->LANG['calopt_calendarFontSize'], 'prefix' => 'calopt', 'name' => 'calendarFontSize', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['calendarFontSize'], 'maxlength' => '3'],
      ['label' => $this->LANG['calopt_showMonths'], 'prefix' => 'calopt', 'name' => 'showMonths', 'type' => 'text', 'placeholder' => '', 'value' => $allConfig['showMonths'], 'maxlength' => '2'],
      ['label' => $this->LANG['calopt_regionalHolidays'], 'prefix' => 'calopt', 'name' => 'regionalHolidays', 'type' => 'check', 'values' => '', 'value' => $allConfig['regionalHolidays']],
      ['label' => $this->LANG['calopt_regionalHolidaysColor'], 'prefix' => 'calopt', 'name' => 'regionalHolidaysColor', 'type' => 'coloris', 'value' => (!empty($allConfig['regionalHolidaysColor']) ? '#' . $allConfig['regionalHolidaysColor'] : ''), 'maxlength' => '6', 'error' => (isset($inputAlert['regionalHolidaysColor']) ? $inputAlert['regionalHolidaysColor'] : '')],
      ['label' => $this->LANG['calopt_sortByOrderKey'], 'prefix' => 'calopt', 'name' => 'sortByOrderKey', 'type' => 'check', 'values' => '', 'value' => $allConfig['sortByOrderKey']],
    ];

    $roles                  = $this->RO->getAll();
    $arrTrustedRoles        = explode(',', $allConfig['trustedRoles']);
    $caloptData['roleList'] = [];
    foreach ($roles as $role) {
      $caloptData['roleList'][] = ['val' => $role['id'], 'name' => $role['name'], 'selected' => in_array($role['id'], $arrTrustedRoles)];
    }
    $caloptData['filter'] = [
      ['label' => $this->LANG['calopt_hideManagers'], 'prefix' => 'calopt', 'name' => 'hideManagers', 'type' => 'check', 'values' => '', 'value' => $allConfig['hideManagers']],
      ['label' => $this->LANG['calopt_hideDaynotes'], 'prefix' => 'calopt', 'name' => 'hideDaynotes', 'type' => 'check', 'values' => '', 'value' => $allConfig['hideDaynotes']],
      ['label' => $this->LANG['calopt_hideManagerOnlyAbsences'], 'prefix' => 'calopt', 'name' => 'hideManagerOnlyAbsences', 'type' => 'check', 'values' => '', 'value' => $allConfig['hideManagerOnlyAbsences']],
      ['label' => $this->LANG['calopt_showUserRegion'], 'prefix' => 'calopt', 'name' => 'showUserRegion', 'type' => 'check', 'values' => '', 'value' => $allConfig['showUserRegion']],
      ['label' => $this->LANG['calopt_trustedRoles'], 'prefix' => 'calopt', 'name' => 'trustedRoles', 'type' => 'listmulti', 'values' => $caloptData['roleList']],
    ];

    $regions                  = $this->R->getAllNames();
    $caloptData['regionList'] = [];
    foreach ($regions as $region) {
      $caloptData['regionList'][] = ['val' => $region, 'name' => $region, 'selected' => ($allConfig['defregion'] == $region)];
    }
    $arrCurrYearRoles        = explode(',', $allConfig['currYearRoles']);
    $caloptData['roleList2'] = [];
    foreach ($roles as $role) {
      $caloptData['roleList2'][] = ['val' => $role['id'], 'name' => $role['name'], 'selected' => in_array($role['id'], $arrCurrYearRoles)];
    }
    $caloptData['options'] = [
      ['label' => $this->LANG['calopt_firstDayOfWeek'], 'prefix' => 'calopt', 'name' => 'firstDayOfWeek', 'type' => 'radio', 'values' => ['1', '7'], 'value' => $allConfig['firstDayOfWeek']],
      ['label' => $this->LANG['calopt_satBusi'], 'prefix' => 'calopt', 'name' => 'satBusi', 'type' => 'check', 'values' => '', 'value' => $allConfig['satBusi']],
      ['label' => $this->LANG['calopt_sunBusi'], 'prefix' => 'calopt', 'name' => 'sunBusi', 'type' => 'check', 'values' => '', 'value' => $allConfig['sunBusi']],
      ['label' => $this->LANG['calopt_defregion'], 'prefix' => 'calopt', 'name' => 'defregion', 'type' => 'list', 'values' => $caloptData['regionList']],
      ['label' => $this->LANG['calopt_showRegionButton'], 'prefix' => 'calopt', 'name' => 'showRegionButton', 'type' => 'check', 'values' => '', 'value' => $allConfig['showRegionButton']],
      ['label' => $this->LANG['calopt_defgroupfilter'], 'prefix' => 'calopt', 'name' => 'defgroupfilter', 'type' => 'radio', 'values' => ['all', 'allbygroup'], 'value' => $allConfig['defgroupfilter']],
      ['label' => $this->LANG['calopt_currentYearOnly'], 'prefix' => 'calopt', 'name' => 'currentYearOnly', 'type' => 'check', 'values' => '', 'value' => $allConfig['currentYearOnly']],
      ['label' => $this->LANG['calopt_currentYearRoles'], 'prefix' => 'calopt', 'name' => 'currentYearRoles', 'type' => 'listmulti', 'values' => $caloptData['roleList2']],
      ['label' => $this->LANG['calopt_takeover'], 'prefix' => 'calopt', 'name' => 'takeover', 'type' => 'check', 'values' => '', 'value' => $allConfig['takeover']],
      ['label' => $this->LANG['calopt_notificationsAllGroups'], 'prefix' => 'calopt', 'name' => 'notificationsAllGroups', 'type' => 'check', 'values' => '', 'value' => $allConfig['notificationsAllGroups']],
      ['label' => $this->LANG['calopt_managerOnlyIncludesAdministrator'], 'prefix' => 'calopt', 'name' => 'managerOnlyIncludesAdministrator', 'type' => 'check', 'values' => '', 'value' => $allConfig['managerOnlyIncludesAdministrator']],
    ];

    $statsPages      = ['Absences', 'Presences', 'Absencetype', 'Presencetype', 'Remainder', 'Trends', 'Dayofweek', 'Duration'];
    $colors          = [
      'blue'    => '#0d6efd',
      'cyan'    => '#0dcaf0',
      'green'   => '#198754',
      'grey'    => '#6c757d',
      'magenta' => '#d63384',
      'orange'  => '#fd7e14',
      'purple'  => '#6f42c1',
      'red'     => '#dc3545',
      'yellow'  => '#ffc107',
    ];
    $statsColorArray = [];
    foreach ($statsPages as $statsPage) {
      $statsColorArray[$statsPage] = [];
      foreach ($colors as $color => $hex) {
        $defaultValue                  = match ($statsPage) {
          'Absences'    => 'red',
          'Absencetype' => 'cyan',
          'Remainder'   => 'orange',
          'Trends'      => 'yellow',
          'Dayofweek'   => 'purple',
          'Duration'    => 'orange',
          default       => 'green',
        };
        $currentValue                  = $allConfig['statsDefaultColor' . $statsPage] ?? $defaultValue;
        $isSelected                    = ($currentValue == $color || $currentValue == $hex);
        $statsColorArray[$statsPage][] = ['val' => $color, 'name' => $this->LANG[$color], 'selected' => $isSelected];
      }
    }
    $caloptData['stats'] = [
      ['label' => $this->LANG['calopt_statsDefaultColorAbsences'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorAbsences', 'type' => 'list', 'values' => $statsColorArray['Absences']],
      ['label' => $this->LANG['calopt_statsDefaultColorPresences'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorPresences', 'type' => 'list', 'values' => $statsColorArray['Presences']],
      ['label' => $this->LANG['calopt_statsDefaultColorAbsencetype'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorAbsencetype', 'type' => 'list', 'values' => $statsColorArray['Absencetype']],
      ['label' => $this->LANG['calopt_statsDefaultColorPresencetype'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorPresencetype', 'type' => 'list', 'values' => $statsColorArray['Presencetype']],
      ['label' => $this->LANG['calopt_statsDefaultColorRemainder'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorRemainder', 'type' => 'list', 'values' => $statsColorArray['Remainder']],
      ['label' => $this->LANG['calopt_statsDefaultColorTrends'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorTrends', 'type' => 'list', 'values' => $statsColorArray['Trends']],
      ['label' => $this->LANG['calopt_statsDefaultColorDayofweek'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorDayofweek', 'type' => 'list', 'values' => $statsColorArray['Dayofweek']],
      ['label' => $this->LANG['calopt_statsDefaultColorDuration'], 'prefix' => 'calopt', 'name' => 'statsDefaultColorDuration', 'type' => 'list', 'values' => $statsColorArray['Duration']],
    ];

    $caloptData['summary'] = [
      ['label' => $this->LANG['calopt_includeSummary'], 'prefix' => 'calopt', 'name' => 'includeSummary', 'type' => 'check', 'values' => '', 'value' => $allConfig['includeSummary']],
      ['label' => $this->LANG['calopt_showSummary'], 'prefix' => 'calopt', 'name' => 'showSummary', 'type' => 'check', 'values' => '', 'value' => $allConfig['showSummary']],
      ['label' => $this->LANG['calopt_summaryAbsenceTextColor'], 'prefix' => 'calopt', 'name' => 'summaryAbsenceTextColor', 'type' => 'coloris', 'value' => (!empty($allConfig['summaryAbsenceTextColor']) ? '#' . $allConfig['summaryAbsenceTextColor'] : ''), 'maxlength' => '7'],
      ['label' => $this->LANG['calopt_summaryPresenceTextColor'], 'prefix' => 'calopt', 'name' => 'summaryPresenceTextColor', 'type' => 'coloris', 'value' => (!empty($allConfig['summaryPresenceTextColor']) ? '#' . $allConfig['summaryPresenceTextColor'] : ''), 'maxlength' => '7'],
    ];

    $viewData['formFields'] = [];
    $sections               = ['display', 'filter', 'options', 'stats', 'summary'];
    foreach ($sections as $section) {
      $viewData['formFields'][$section] = [];
      foreach ($caloptData[$section] as $field) {
        /** @var array<string, mixed> $field */
        $viewData['formFields'][$section][] = [
          'label'       => $field['label'],
          'prefix'      => $field['prefix'],
          'name'        => $field['name'],
          'type'        => $field['type'],
          'value'       => $field['value'] ?? '',
          'maxlength'   => $field['maxlength'] ?? '',
          'placeholder' => $field['placeholder'] ?? '',
          'options'     => $field['options'] ?? [],
          'values'      => $field['values'] ?? [],
          'help'        => $field['help'] ?? '',
          'required'    => $field['required'] ?? false,
          'error'       => $field['error'] ?? '',
        ];
      }
    }

    $this->render('calendaroptions', $viewData);
  }
}
