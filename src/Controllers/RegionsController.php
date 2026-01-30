<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;
use App\Models\MonthModel;

/**
 * Regions Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class RegionsController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['regions']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel();
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);



    $viewData                    = [];
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['currentYearOnly'] = $this->allConfig['currentYearOnly'];
    $viewData['txt_name']        = '';
    $viewData['txt_description'] = '';
    $viewData['csrf_token']      = $_SESSION['csrf_token'] ?? '';

    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!empty($_FILES)) {
        foreach ($_FILES as $key => $file) {
          if (isset($file['name']))
            $_FILES[$key]['name'] = htmlspecialchars($file['name'], ENT_QUOTES, 'UTF-8');
          if (isset($file['type']))
            $_FILES[$key]['type'] = htmlspecialchars($file['type'], ENT_QUOTES, 'UTF-8');
          if (isset($file['tmp_name']))
            $_FILES[$key]['tmp_name'] = htmlspecialchars($file['tmp_name'], ENT_QUOTES, 'UTF-8');
          if (isset($file['error']))
            $_FILES[$key]['error'] = htmlspecialchars((string) $file['error'], ENT_QUOTES, 'UTF-8');
          if (isset($file['size']))
            $_FILES[$key]['size'] = htmlspecialchars((string) $file['size'], ENT_QUOTES, 'UTF-8');
        }
      }

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_regionCreate'])) {
        $inputError = false;
        if (!formInputValid('txt_name', 'required|alpha_numeric_dash'))
          $inputError = true;
        if (!formInputValid('txt_description', 'alpha_numeric_dash_blank'))
          $inputError = true;

        $viewData['txt_name']        = $_POST['txt_name'] ?? '';
        $viewData['txt_description'] = $_POST['txt_description'] ?? '';

        if (!$inputError) {
          $this->R->name        = $viewData['txt_name'];
          $this->R->description = $viewData['txt_description'];
          $this->R->create();

          $this->LOG->logEvent("logRegion", $this->UL->username, "log_region_created", $this->R->name . " " . $this->R->description);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['btn_create_region'];
          $alertData['text']    = $this->LANG['regions_alert_region_created'];
          $alertData['help']    = '';

          if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $viewData['csrf_token'] = $_SESSION['csrf_token'];
          }
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['btn_create_region'];
          $alertData['text']    = $this->LANG['regions_alert_region_created_fail'];
          $alertData['help']    = '';
        }
      }
      elseif (isset($_POST['btn_regionDelete'])) {
        $hiddenId   = $_POST['hidden_id'] ?? null;
        $hiddenName = $_POST['hidden_name'] ?? '';
        if ($hiddenId !== null) {
          $this->R->delete($hiddenId);
          $this->R->deleteAccess($hiddenId);
          $this->M->deleteRegion($hiddenId);
          $this->UO->deleteOptionByValue('calfilterRegion', $hiddenId);
          $this->LOG->logEvent("logRegion", $this->UL->username, "log_region_deleted", $hiddenName);
        }

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['btn_delete_region'];
        $alertData['text']    = $this->LANG['regions_alert_region_deleted'];
        $alertData['help']    = '';

        if (session_status() === PHP_SESSION_ACTIVE) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          $viewData['csrf_token'] = $_SESSION['csrf_token'];
        }
      }
      elseif (isset($_POST['btn_uploadIcal'])) {
        $fileIcal = $_FILES['file_ical']['tmp_name'] ?? '';
        if (trim($fileIcal) === '') {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_input'];
          $alertData['text']    = $this->LANG['regions_alert_no_file'];
          $alertData['help']    = '';
          if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $viewData['csrf_token'] = $_SESSION['csrf_token'];
          }
        }
        else {
          $viewData['icalRegionID']   = $_POST['sel_ical_region'] ?? '';
          $viewData['icalRegionName'] = $this->R->getNameById($viewData['icalRegionID']);

          $iCalEvents = [];
          preg_match_all("#(?sU)BEGIN:VEVENT.*END:VEVENT#", file_get_contents($fileIcal), $events);

          foreach ($events[0] as $event) {
            preg_match("#(?sU)DTSTART;.*DATE:(\\d{8})#", $event, $start);
            preg_match("#(?sU)DTEND;.*DATE:(\\d{8})#", $event, $end);
            if (!isset($start[1]))
              continue;

            $startTs = mktime(0, 0, 0, (int) substr($start[1], 4, 2), (int) substr($start[1], 6, 2), (int) substr($start[1], 0, 4));

            if (isset($end[1])) {
              $endTs = mktime(0, 0, 0, (int) substr($end[1], 4, 2), (int) substr($end[1], 6, 2), (int) substr($end[1], 0, 4));
              $endTs = $endTs - 86400;
            }
            else {
              $endTs = $startTs;
            }

            for ($i = $startTs; $i <= $endTs; $i += 86400) {
              $eventDate    = date("Ymd", $i);
              $iCalEvents[] = $eventDate;
            }
          }

          $lastCachedMonth = null;
          $lastCachedYear  = null;
          foreach ($iCalEvents as $i) {
            $eventYear  = substr($i, 0, 4);
            $eventMonth = substr($i, 4, 2);
            $eventDay   = intval(substr($i, 6, 2));

            if ($lastCachedYear !== $eventYear || $lastCachedMonth !== $eventMonth) {
              if (!$this->M->getMonth($eventYear, $eventMonth, $viewData['icalRegionID'])) {
                createMonth($eventYear, $eventMonth, 'region', $viewData['icalRegionID']);
              }
              $lastCachedYear  = $eventYear;
              $lastCachedMonth = $eventMonth;
            }

            $holidayId = $_POST['sel_ical_holiday'] ?? null;
            if ($holidayId === null)
              continue;

            if ($this->M->getHoliday($eventYear, $eventMonth, (string) $eventDay, $viewData['icalRegionID']) === 0) {
              $this->M->setHoliday($eventYear, $eventMonth, (string) $eventDay, $viewData['icalRegionID'], $holidayId);
            }
            else {
              if (isset($_POST['chk_ical_overwrite'])) {
                $this->M->setHoliday($eventYear, $eventMonth, (string) $eventDay, $viewData['icalRegionID'], $holidayId);
              }
            }
          }

          $fileName = $_FILES['file_ical']['name'] ?? '';
          $this->LOG->logEvent("logRegion", $this->UL->username, "log_region_ical", $fileName . ' => ' . $viewData['icalRegionName']);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['regions_tab_ical'];
          $alertData['text']    = sprintf($this->LANG['regions_ical_imported'], $fileName, $viewData['icalRegionName']);
          $alertData['help']    = '';

          if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          }
        }
      }
      elseif (isset($_POST['btn_regionTransfer'])) {
        $sregion = $_POST['sel_region_a'] ?? '';
        $tregion = $_POST['sel_region_b'] ?? '';
        if ($sregion === $tregion) {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_input'];
          $alertData['text']    = $this->LANG['regions_alert_transfer_same'];
          $alertData['help']    = '';
        }
        else {
          $sourceRegionName = $this->R->getNameById($sregion);
          $targetRegionName = $this->R->getNameById($tregion);
          $stemplates       = $this->M->getRegion($sregion);

          foreach ($stemplates as $stpl) {
            if (!$this->M->getMonth($stpl['year'], $stpl['month'], $tregion)) {
              createMonth($stpl['year'], $stpl['month'], 'region', $tregion);
            }
            else {
              $this->M->getMonth($stpl['year'], $stpl['month'], $tregion);
            }
            for ($i = 1; $i <= 31; $i++) {
              $prop = 'hol' . $i;
              if (($stpl[$prop] ?? 0) > 3) {
                if (($this->M->$prop ?? 0) <= 3) {
                  $this->M->$prop = $stpl[$prop];
                }
                else {
                  if (isset($_POST['chk_overwrite'])) {
                    $this->M->$prop = $stpl[$prop];
                  }
                }
              }
            }
            $this->M->update($stpl['year'], $stpl['month'], $tregion);
          }

          $this->LOG->logEvent("logRegion", $this->UL->username, "log_region_transferred", $sourceRegionName . " => " . $targetRegionName);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['regions_tab_transfer'];
          $alertData['text']    = sprintf($this->LANG['regions_transferred'], $sourceRegionName, $targetRegionName);
          $alertData['help']    = '';
        }
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['regions'] = $this->R->getAll();
    foreach ($viewData['regions'] as $region) {
      $viewData['regionList'][] = ['val' => $region['id'], 'name' => $region['name'], 'selected' => false];
    }

    $holidays = $this->H->getAll();
    foreach ($holidays as $holiday) {
      $viewData['holidayList'][] = ['val' => $holiday['id'], 'name' => $holiday['name'], 'selected' => false];
    }
    $viewData['ical'] = [
      ['prefix' => 'regions', 'name' => 'ical_region', 'type' => 'list', 'values' => $viewData['regionList']],
      ['prefix' => 'regions', 'name' => 'ical_holiday', 'type' => 'list', 'values' => $viewData['holidayList']],
      ['prefix' => 'regions', 'name' => 'ical_overwrite', 'type' => 'check', 'value' => false],
    ];

    $viewData['merge'] = [
      ['prefix' => 'regions', 'name' => 'region_a', 'type' => 'list', 'values' => $viewData['regionList']],
      ['prefix' => 'regions', 'name' => 'region_b', 'type' => 'list', 'values' => $viewData['regionList']],
      ['prefix' => 'regions', 'name' => 'region_overwrite', 'type' => 'check', 'value' => false],
    ];

    $this->render('regions', $viewData);
  }
}
