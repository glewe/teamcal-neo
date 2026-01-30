<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;

/**
 * Groups Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class GroupsController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['groups']->permission) && !$this->UG->isGroupManager($this->UL->username)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $licAlertData     = [];
    $licShowAlert     = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel();
    $LIC->check($licAlertData, $licShowAlert, (int) $licExpiryWarning, $this->LANG);

    $viewData                    = [];
    $viewData['pageHelp']        = $this->allConfig['pageHelp'];
    $viewData['showAlerts']      = $this->allConfig['showAlerts'];
    $viewData['showAlert']       = $licShowAlert;
    $viewData['alertData']       = $licAlertData;
    $viewData['txt_name']        = '';
    $viewData['txt_description'] = '';

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;

      if (isset($_POST['btn_groupCreate'])) {
        if (formInputValid('txt_name', 'required|alpha_numeric_dash') !== true)
          $inputError = true;
        if (formInputValid('txt_description', 'alpha_numeric_dash_blank') !== true)
          $inputError = true;

        $viewData['txt_name']        = $_POST['txt_name'];
        $viewData['txt_description'] = $_POST['txt_description'] ?? '';

        if (!$inputError) {
          $this->G->name         = $viewData['txt_name'];
          $this->G->description  = $viewData['txt_description'];
          $this->G->minpresent   = 0;
          $this->G->maxabsent    = 9999;
          $this->G->minpresentwe = 0;
          $this->G->maxabsentwe  = 9999;
          $this->G->create();

          if ($this->allConfig['emailNotifications']) {
            sendGroupEventNotifications("created", $this->G->name, $this->G->description);
          }

          $this->LOG->logEvent("logGroup", $this->UL->username, "log_group_created", $this->G->name . " " . $this->G->description);

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['btn_create_group'];
          $alertData['text']    = $this->LANG['groups_alert_group_created'];
          $alertData['help']    = '';
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['btn_create_group'];
          $alertData['text']    = $this->LANG['groups_alert_group_created_fail'];
          $alertData['help']    = '';
        }
      }
      elseif (isset($_POST['btn_groupDelete'])) {
        $this->G->delete($_POST['hidden_id']);
        $this->UG->deleteByGroup((string) $_POST['hidden_id']);
        // Need UO here
        $this->UO->deleteOptionByValue('calfilterGroup', $_POST['hidden_id']);

        if ($this->allConfig['emailNotifications']) {
          sendGroupEventNotifications("deleted", $_POST['hidden_name'], $_POST['hidden_description']);
        }

        $this->LOG->logEvent("logGroup", $this->UL->username, "log_group_deleted", $_POST['hidden_name']);

        $showAlert            = true;
        $alertData['type']    = 'success';
        $alertData['title']   = $this->LANG['alert_success_title'];
        $alertData['subject'] = $this->LANG['btn_delete_group'];
        $alertData['text']    = $this->LANG['groups_alert_group_deleted'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['groups']      = $this->G->getAllCached();
    $viewData['searchGroup'] = '';

    if (!isAllowed($this->CONF['controllers']['groups']->permission) && $this->UG->isGroupManager($this->UL->username)) {
      $viewData['groups'] = $this->UG->getAllManagedGroupsForUser($this->UL->username);
    }

    if (isset($_POST['btn_search'])) {
      if (isset($_POST['txt_searchGroup'])) {
        $searchGroup             = sanitize($_POST['txt_searchGroup']);
        $viewData['searchGroup'] = $searchGroup;
        $viewData['groups']      = $this->G->getAllCached();
        $searchGroup             = strtolower($searchGroup);
        $viewData['groups']      = array_filter($viewData['groups'], function ($group) use ($searchGroup) {
          return stripos($group['name'], $searchGroup) !== false || stripos($group['description'], $searchGroup) !== false;
        });
      }
    }

    $this->render('groups', $viewData);
  }
}
