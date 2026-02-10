<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Declination Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class DeclinationController extends BaseController
{
  /** @var array<string, mixed> $viewData */
  private array $viewData = [];

  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['declination']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $this->viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $this->viewData['showAlerts'] = $this->allConfig['showAlerts'];

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_save'])) {
        $this->validateForm($inputError);
      }

      if (!$inputError) {
        if (isset($_POST['btn_save'])) {
          $this->saveForm();
          $this->viewData['showAlert'] = true;
          $this->viewData['alertData'] = [
            'type'    => 'success',
            'title'   => $this->LANG['alert_success_title'],
            'subject' => $this->LANG['decl_alert_save'],
            'text'    => $this->LANG['decl_alert_save_success'],
            'help'    => '',
          ];
        }
      }
      else {
        $this->viewData['showAlert'] = true;
        $this->viewData['alertData'] = [
          'type'    => 'danger',
          'title'   => $this->LANG['alert_danger_title'],
          'subject' => $this->LANG['alert_input'],
          'text'    => $this->LANG['decl_alert_save_failed'],
          'help'    => '',
        ];
      }
    }

    $this->prepareViewData();
    $this->render('declination', $this->viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Validates the form input.
   *
   * @param bool $inputError Reference to the input error flag
   * @return void
   */
  private function validateForm(&$inputError) {
    // Batch validation for sections with similar patterns
    $sections = [
      'absence' => [
        'checkbox'     => 'chk_absence',
        'fields'       => [
          ['name' => 'txt_threshold', 'rules' => 'max_length|numeric', 'args' => '2'],
        ],
        'periodOption' => 'opt_absencePeriod',
        'periodFields' => [
          'nowEnddate'       => [['name' => 'txt_absenceEnddate', 'rules' => 'required|date']],
          'startdateForever' => [['name' => 'txt_absenceStartdate', 'rules' => 'required|date']],
          'startdateEnddate' => [
            ['name' => 'txt_absenceStartdate', 'rules' => 'required|date'],
            ['name' => 'txt_absenceEnddate', 'rules' => 'required|date']
          ],
        ],
      ],
      'before'  => [
        'checkbox'     => 'chk_before',
        'fields'       => [
          ['name' => 'opt_beforeoption', 'rules' => 'required'],
        ],
        'extra'        => function (&$inputError) {
          if (($_POST['opt_beforeoption'] ?? '') === 'date' && !formInputValid('txt_beforedate', 'required|date')) {
            $inputError = true;
          }
        },
        'periodOption' => 'opt_beforePeriod',
        'periodFields' => [
          'nowEnddate'       => [['name' => 'txt_beforeEnddate', 'rules' => 'required|date']],
          'startdateForever' => [['name' => 'txt_beforeStartdate', 'rules' => 'required|date']],
          'startdateEnddate' => [
            ['name' => 'txt_beforeStartdate', 'rules' => 'required|date'],
            ['name' => 'txt_beforeEnddate', 'rules' => 'required|date']
          ],
        ],
      ],
      'period1' => [
        'checkbox'     => 'chk_period1',
        'fields'       => [
          ['name' => 'txt_period1start', 'rules' => 'required|date'],
          ['name' => 'txt_period1end', 'rules' => 'required|date'],
          ['name' => 'txt_period1Message', 'rules' => 'alpha_numeric_dash_blank_special'],
        ],
        'periodOption' => 'opt_period1Period',
        'periodFields' => [
          'nowEnddate'       => [['name' => 'txt_period1Enddate', 'rules' => 'required|date']],
          'startdateForever' => [['name' => 'txt_period1Startdate', 'rules' => 'required|date']],
          'startdateEnddate' => [
            ['name' => 'txt_period1Startdate', 'rules' => 'required|date'],
            ['name' => 'txt_period1Enddate', 'rules' => 'required|date']
          ],
        ],
      ],
      'period2' => [
        'checkbox'     => 'chk_period2',
        'fields'       => [
          ['name' => 'txt_period2start', 'rules' => 'required|date'],
          ['name' => 'txt_period2end', 'rules' => 'required|date'],
          ['name' => 'txt_period2Message', 'rules' => 'alpha_numeric_dash_blank_special'],
        ],
        'periodOption' => 'opt_period2Period',
        'periodFields' => [
          'nowEnddate'       => [['name' => 'txt_period2Enddate', 'rules' => 'required|date']],
          'startdateForever' => [['name' => 'txt_period2Startdate', 'rules' => 'required|date']],
          'startdateEnddate' => [
            ['name' => 'txt_period2Startdate', 'rules' => 'required|date'],
            ['name' => 'txt_period2Enddate', 'rules' => 'required|date']
          ],
        ],
      ],
      'period3' => [
        'checkbox'     => 'chk_period3',
        'fields'       => [
          ['name' => 'txt_period3start', 'rules' => 'required|date'],
          ['name' => 'txt_period3end', 'rules' => 'required|date'],
          ['name' => 'txt_period3Message', 'rules' => 'alpha_numeric_dash_blank_special'],
        ],
        'periodOption' => 'opt_period3Period',
        'periodFields' => [
          'nowEnddate'       => [['name' => 'txt_period3Enddate', 'rules' => 'required|date']],
          'startdateForever' => [['name' => 'txt_period3Startdate', 'rules' => 'required|date']],
          'startdateEnddate' => [
            ['name' => 'txt_period3Startdate', 'rules' => 'required|date'],
            ['name' => 'txt_period3Enddate', 'rules' => 'required|date']
          ],
        ],
      ],
    ];
    foreach ($sections as $section) {
      if (isset($_POST[$section['checkbox']])) {
        if ($this->validateSectionFields($section['fields']))
          $inputError = true;
        if (isset($section['extra']))
          $section['extra']($inputError);
        if (isset($_POST[$section['periodOption']])) {
          $periodKey = $_POST[$section['periodOption']];
          if (isset($section['periodFields'][$periodKey])) {
            if ($this->validatePeriodFields($section['periodFields'][$periodKey]))
              $inputError = true;
          }
        }
      }
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Validates fields for a specific section.
   *
   * @param array<int, array<string, mixed>> $fields Array of field definitions
   *
   * @return bool True if error found, false otherwise
   */
  private function validateSectionFields(array $fields): bool {
    $hasError = false;
    foreach ($fields as $field) {
      $args = $field['args'] ?? '';
      if (!formInputValid($field['name'], $field['rules'], $args))
        $hasError = true;
    }
    return $hasError;
  }

  //---------------------------------------------------------------------------
  /**
   * Validates period fields.
   *
   * @param array<int, array<string, string>> $periodFields Array of period field definitions
   *
   * @return bool True if error found, false otherwise
   */
  private function validatePeriodFields(array $periodFields): bool {
    $hasError = false;
    foreach ($periodFields as $periodField) {
      if (!formInputValid($periodField['name'], $periodField['rules']))
        $hasError = true;
    }
    return $hasError;
  }

  //---------------------------------------------------------------------------
  /**
   * Saves the form data to the configuration.
   *
   * @return void
   */
  private function saveForm() {
    $configToSave = [];
    $this->saveSectionConfig($configToSave, 'absence');
    $this->saveSectionConfig($configToSave, 'before');
    $this->saveSectionConfig($configToSave, 'period1');
    $this->saveSectionConfig($configToSave, 'period2');
    $this->saveSectionConfig($configToSave, 'period3');

    $scope = '';
    if (isset($_POST['sel_roles'])) {
      $scope = implode(',', $_POST['sel_roles']);
    }
    $configToSave["declScope"] = $scope;

    $this->C->saveBatch($configToSave);
    $this->LOG->logEvent("logConfig", $this->UL->username, "log_decl_updated");
    $this->_instances['allConfig'] = array_merge($this->allConfig, $configToSave);
  }

  //---------------------------------------------------------------------------
  /**
   * Helper to save configuration for a specific section.
   *
   * @param array<string, mixed> $config      Reference to the config array
   * @param string               $sectionName Name of the section
   *
   * @return void
   */
  private function saveSectionConfig(array &$config, string $sectionName): void {
    $config['decl' . ucfirst($sectionName)] = isset($_POST['chk_' . $sectionName]) ? '1' : '0';
    if (isset($_POST['chk_' . $sectionName])) {
      if ($sectionName == 'absence') {
        $config['declThreshold'] = $_POST['txt_threshold'];
      }
      elseif ($sectionName == 'before') {
        $config['declBeforeoption'] = $_POST['opt_beforeoption'];
        $config['declBeforedate']   = $_POST['txt_beforedate'];
      }
      elseif (in_array($sectionName, ['period1', 'period2', 'period3'])) {
        $config['decl' . ucfirst($sectionName) . 'Start']   = $_POST['txt_' . $sectionName . 'start'];
        $config['decl' . ucfirst($sectionName) . 'End']     = $_POST['txt_' . $sectionName . 'end'];
        $config['decl' . ucfirst($sectionName) . 'Message'] = $_POST['txt_' . $sectionName . 'Message'];
      }

      $config['decl' . ucfirst($sectionName) . 'Period'] = $_POST['opt_' . $sectionName . 'Period'];
      if ($config['decl' . ucfirst($sectionName) . 'Period'] == 'nowEnddate') {
        $config['decl' . ucfirst($sectionName) . 'Enddate'] = $_POST['txt_' . $sectionName . 'Enddate'];
      }
      elseif ($config['decl' . ucfirst($sectionName) . 'Period'] == 'startdateForever') {
        $config['decl' . ucfirst($sectionName) . 'Startdate'] = $_POST['txt_' . $sectionName . 'Startdate'];
      }
      elseif ($config['decl' . ucfirst($sectionName) . 'Period'] == 'startdateEnddate') {
        $config['decl' . ucfirst($sectionName) . 'Startdate'] = $_POST['txt_' . $sectionName . 'Startdate'];
        $config['decl' . ucfirst($sectionName) . 'Enddate']   = $_POST['txt_' . $sectionName . 'Enddate'];
      }
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Prepares the view data.
   *
   * @return void
   */
  private function prepareViewData() {
    // ... preparing view data for all sections ...
    $this->prepareSectionViewData('absence', $this->allConfig);
    $this->prepareSectionViewData('before', $this->allConfig);
    $this->prepareSectionViewData('period1', $this->allConfig);
    $this->prepareSectionViewData('period2', $this->allConfig);
    $this->prepareSectionViewData('period3', $this->allConfig);

    $roles             = $this->RO->getAll();
    $currentScopeArray = explode(',', $this->allConfig['declScope']);
    foreach ($roles as $role) {
      $this->viewData['roles'][] = ['val' => $role['id'], 'name' => $role['name'], 'selected' => in_array($role['id'], $currentScopeArray)];
    }
    $this->viewData['scope'] = [
      ['prefix' => 'decl', 'name' => 'roles', 'type' => 'listmulti', 'values' => $this->viewData['roles']],
    ];
  }

  //---------------------------------------------------------------------------
  /**
   * Prepares view data for a specific section.
   *
   * @param string               $sectionName Name of the section
   * @param array<string, mixed> $allConfig   Configuration array
   *
   * @return void
   */
  private function prepareSectionViewData(string $sectionName, array $allConfig): void {
    global $inputAlert;
    $this->viewData['decl' . ucfirst($sectionName)]               = ($allConfig['decl' . ucfirst($sectionName)] == '1');
    $this->viewData['decl' . ucfirst($sectionName) . 'Period']    = $allConfig['decl' . ucfirst($sectionName) . 'Period'];
    $this->viewData['decl' . ucfirst($sectionName) . 'Startdate'] = $allConfig['decl' . ucfirst($sectionName) . 'Startdate'];
    $this->viewData['decl' . ucfirst($sectionName) . 'Enddate']   = $allConfig['decl' . ucfirst($sectionName) . 'Enddate'];

    // Status logic
    $status = 'inactive';
    if ($allConfig['decl' . ucfirst($sectionName)] == '1') {
      $today  = date('Y-m-d');
      $start  = $allConfig['decl' . ucfirst($sectionName) . 'Startdate'] ?? '';
      $end    = $allConfig['decl' . ucfirst($sectionName) . 'Enddate'] ?? '';
      $period = $allConfig['decl' . ucfirst($sectionName) . 'Period'] ?? '';

      if ($period == 'nowForever') {
        $status = 'active';
      }
      elseif ($period == 'nowEnddate') {
        $status = ($today <= $end) ? 'active' : 'expired';
      }
      elseif ($period == 'startdateForever') {
        $status = ($today >= $start) ? 'active' : 'scheduled';
      }
      elseif ($period == 'startdateEnddate') {
        if ($today < $start)
          $status = 'scheduled';
        elseif ($today > $end)
          $status = 'expired';
        else
          $status = 'active';
      }
    }
    $this->viewData['decl' . ucfirst($sectionName) . 'Status'] = $status;

    if ($sectionName == 'absence') {
      $this->viewData['declThreshold'] = $allConfig['declThreshold'] ?? '';
      $this->viewData['absence']       = [
        ['prefix' => 'decl', 'name' => 'absence', 'type' => 'check', 'value' => $allConfig['declAbsence'] ?? 0, 'error' => (isset($inputAlert['absence']) ? $inputAlert['absence'] : '')],
        ['prefix' => 'decl', 'name' => 'threshold', 'type' => 'text', 'value' => $allConfig['declThreshold'] ?? '', 'maxlength' => '2', 'error' => (isset($inputAlert['threshold']) ? $inputAlert['threshold'] : '')],
        ['prefix' => 'decl', 'name' => 'absencePeriod', 'type' => 'radio', 'values' => ['nowForever', 'nowEnddate', 'startdateForever', 'startdateEnddate'], 'value' => $allConfig['declAbsencePeriod'] ?? '', 'error' => (isset($inputAlert['absencePeriod']) ? $inputAlert['absencePeriod'] : '')],
        ['prefix' => 'decl', 'name' => 'absenceStartdate', 'type' => 'date', 'value' => $allConfig['declAbsenceStartdate'] ?? '', 'error' => (isset($inputAlert['absenceStartdate']) ? $inputAlert['absenceStartdate'] : '')],
        ['prefix' => 'decl', 'name' => 'absenceEnddate', 'type' => 'date', 'value' => $allConfig['declAbsenceEnddate'] ?? '', 'error' => (isset($inputAlert['absenceEnddate']) ? $inputAlert['absenceEnddate'] : '')],
      ];
    }
    elseif ($sectionName == 'before') {
      $this->viewData['declBeforeoption'] = $allConfig['declBeforeoption'] ?? '';
      $this->viewData['declBeforedate']   = $allConfig['declBeforedate'] ?? '';
      $this->viewData['before']           = [
        ['prefix' => 'decl', 'name' => 'before', 'type' => 'check', 'value' => $allConfig['declBefore'] ?? 0, 'error' => (isset($inputAlert['before']) ? $inputAlert['before'] : '')],
        ['prefix' => 'decl', 'name' => 'beforeoption', 'type' => 'radio', 'values' => ['1day', '1week', '1month', 'date'], 'value' => $allConfig['declBeforeoption'] ?? '', 'error' => (isset($inputAlert['beforeoption']) ? $inputAlert['beforeoption'] : '')],
        ['prefix' => 'decl', 'name' => 'beforedate', 'type' => 'date', 'value' => $allConfig['declBeforedate'] ?? '', 'error' => (isset($inputAlert['beforedate']) ? $inputAlert['beforedate'] : '')],
        ['prefix' => 'decl', 'name' => 'beforePeriod', 'type' => 'radio', 'values' => ['nowForever', 'nowEnddate', 'startdateForever', 'startdateEnddate'], 'value' => $allConfig['declBeforePeriod'] ?? '', 'error' => (isset($inputAlert['beforePeriod']) ? $inputAlert['beforePeriod'] : '')],
        ['prefix' => 'decl', 'name' => 'beforeStartdate', 'type' => 'date', 'value' => $allConfig['declBeforeStartdate'] ?? '', 'error' => (isset($inputAlert['beforeStartdate']) ? $inputAlert['beforeStartdate'] : '')],
        ['prefix' => 'decl', 'name' => 'beforeEnddate', 'type' => 'date', 'value' => $allConfig['declBeforeEnddate'] ?? '', 'error' => (isset($inputAlert['beforeEnddate']) ? $inputAlert['beforeEnddate'] : '')],
      ];
    }
    elseif (in_array($sectionName, ['period1', 'period2', 'period3'])) {
      $this->viewData['decl' . ucfirst($sectionName) . 'Start']   = $allConfig['decl' . ucfirst($sectionName) . 'Start'] ?? '';
      $this->viewData['decl' . ucfirst($sectionName) . 'End']     = $allConfig['decl' . ucfirst($sectionName) . 'End'] ?? '';
      $this->viewData['decl' . ucfirst($sectionName) . 'Message'] = $allConfig['decl' . ucfirst($sectionName) . 'Message'] ?? '';
      $this->viewData['' . $sectionName]                          = [
        ['prefix' => 'decl', 'name' => $sectionName, 'type' => 'check', 'value' => $allConfig['decl' . ucfirst($sectionName)] ?? 0, 'error' => (isset($inputAlert[$sectionName]) ? $inputAlert[$sectionName] : '')],
        ['prefix' => 'decl', 'name' => $sectionName . 'start', 'type' => 'date', 'value' => $allConfig['decl' . ucfirst($sectionName) . 'Start'] ?? '', 'error' => (isset($inputAlert[$sectionName . 'start']) ? $inputAlert[$sectionName . 'start'] : '')],
        ['prefix' => 'decl', 'name' => $sectionName . 'end', 'type' => 'date', 'value' => $allConfig['decl' . ucfirst($sectionName) . 'End'] ?? '', 'error' => (isset($inputAlert[$sectionName . 'end']) ? $inputAlert[$sectionName . 'end'] : '')],
        ['prefix' => 'decl', 'name' => $sectionName . 'Message', 'type' => 'text', 'value' => $allConfig['decl' . ucfirst($sectionName) . 'Message'] ?? '', 'error' => (isset($inputAlert[$sectionName . 'Message']) ? $inputAlert[$sectionName . 'Message'] : '')],
        ['prefix' => 'decl', 'name' => $sectionName . 'Period', 'type' => 'radio', 'values' => ['nowForever', 'nowEnddate', 'startdateForever', 'startdateEnddate'], 'value' => $allConfig['decl' . ucfirst($sectionName) . 'Period'] ?? '', 'error' => (isset($inputAlert[$sectionName . 'Period']) ? $inputAlert[$sectionName . 'Period'] : '')],
        ['prefix' => 'decl', 'name' => $sectionName . 'Startdate', 'type' => 'date', 'value' => $allConfig['decl' . ucfirst($sectionName) . 'Startdate'] ?? '', 'error' => (isset($inputAlert[$sectionName . 'Startdate']) ? $inputAlert[$sectionName . 'Startdate'] : '')],
        ['prefix' => 'decl', 'name' => $sectionName . 'Enddate', 'type' => 'date', 'value' => $allConfig['decl' . ucfirst($sectionName) . 'Enddate'] ?? '', 'error' => (isset($inputAlert[$sectionName . 'Enddate']) ? $inputAlert[$sectionName . 'Enddate'] : '')],
      ];
    }
  }
}
