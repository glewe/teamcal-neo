<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;

/**
 * Database Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class DatabaseController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['database']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel();
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);

    $viewData                = [];
    $viewData['pageHelp']    = $this->allConfig['pageHelp'];
    $viewData['showAlerts']  = $this->allConfig['showAlerts'];
    $viewData['cleanBefore'] = '';

    global $inputAlert;
    /** @var array<string, string> $inputAlert */
    $inputAlert = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      $inputError = false;
      if (isset($_POST['btn_delete']) && !formInputValid('txt_deleteConfirm', 'required|alpha|equals_string', 'DELETE')) {
        $inputError = true;
      }
      if (isset($_POST['btn_cleanup'])) {
        if (!formInputValid('txt_cleanBefore', 'required|date')) {
          $inputError = true;
        }
        if (!formInputValid('txt_cleanConfirm', 'required|alpha|equals_string', 'CLEANUP')) {
          $inputError = true;
        }
        if (!isValidDate($_POST['txt_cleanBefore'])) {
          $inputError = true;
        }
        $viewData['cleanBefore'] = $_POST['txt_cleanBefore'];
      }
      if (isset($_POST['btn_repair']) && !formInputValid('txt_repairConfirm', 'required|alpha|equals_string', 'REPAIR')) {
        $inputError = true;
      }

      if (!$inputError) {

        if (isset($_POST['btn_repair'])) {
          // ,--------,
          // | Repair |
          // '--------'
          if (isset($_POST['chk_daynoteRegions'])) {
            $daynotes = $this->D->getAllRegionless();
            foreach ($daynotes as $daynote) {
              $this->D->setRegion($daynote['id'], '1');
            }
          }
          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_repair'];
          $alertData['text']    = $this->LANG['db_alert_repair_success'];
          $alertData['help']    = '';
        }
        elseif (isset($_POST['btn_cleanup'])) {
          // ,----------,
          // | Cleanup  |
          // '----------'
          $cleanBeforeDate          = $_POST['txt_cleanBefore'];
          $cleanBeforeDateNoHyphens = str_replace('-', '', $cleanBeforeDate);
          $cleanBeforeYear          = substr($cleanBeforeDate, 0, 4);
          $cleanBeforeMonth         = substr($cleanBeforeDate, 5, 2);

          if (isset($_POST['chk_cleanDaynotes'])) {
            $this->D->deleteAllBefore($cleanBeforeDateNoHyphens);
          }
          if (isset($_POST['chk_cleanMonths'])) {
            $this->M->deleteBefore($cleanBeforeYear, $cleanBeforeMonth);
          }
          if (isset($_POST['chk_cleanTemplates'])) {
            $this->T->deleteBefore($cleanBeforeYear, $cleanBeforeMonth);
          }

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_cleanup'];
          $alertData['text']    = $this->LANG['db_alert_cleanup_success'];
          $alertData['help']    = '';
        }
        elseif (isset($_POST['btn_delete'])) {
          // ,--------,
          // | Delete |
          // '--------'
          if (isset($_POST['chk_delUsers'])) {
            $this->U->deleteAll();
            $this->UO->deleteAll();
            $this->D->deleteAll();
            $this->T->deleteAll();
            $this->AL->deleteAll();
            $this->LOG->logEvent("logDatabase", $this->UL->username, "log_db_delete_users");
          }
          if (isset($_POST['chk_delGroups'])) {
            $this->G->deleteAll();
            $this->UG->deleteAll();
            $this->LOG->logEvent("logDatabase", $this->UL->username, "log_db_delete_groups");
          }
          if (isset($_POST['chk_delMessages'])) {
            $this->MSG->deleteAll();
            $this->UMSG->deleteAll();
            $this->LOG->logEvent("logDatabase", $this->UL->username, "log_db_delete_msg");
          }
          if (isset($_POST['chk_delOrphMessages'])) {
            $this->deleteOrphanedMessages();
            $this->LOG->logEvent("logMessage", $this->UL->username, "log_db_delete_msg_orph");
          }
          if (isset($_POST['chk_delPermissions'])) {
            $this->P->deleteAll();
            $this->LOG->logEvent("logDatabase", $this->UL->username, "log_db_delete_perm");
          }
          if (isset($_POST['chk_delLog'])) {
            $this->LOG->deleteAll();
            $this->LOG->logEvent("logDatabase", $this->UL->username, "log_db_delete_log");
          }
          if (isset($_POST['chkDBDeleteArchive'])) {
            $this->U->deleteAll(true);
            $this->UG->deleteAll(true);
            $this->UO->deleteAll(true);
            $this->UMSG->deleteAll(true);
            $this->LOG->logEvent("logDatabase", $this->UL->username, "log_db_delete_archive");
          }

          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_delete'];
          $alertData['text']    = $this->LANG['db_alert_delete_success'];
          $alertData['help']    = '';
        }
        elseif (isset($_POST['btn_optimize'])) {
          // ,-----------,
          // | Optimize  |
          // '-----------'
          $this->DB->optimizeTables();
          $this->LOG->logEvent("logDatabase", $this->UL->username, "log_db_optimized");
          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_optimize'];
          $alertData['text']    = $this->LANG['db_alert_optimize_success'];
          $alertData['help']    = '';
        }
        elseif (isset($_POST['btn_saveURL'])) {
          // ,----------,
          // | Save URL |
          // '----------'
          if (filter_var($_POST['txt_dbURL'], FILTER_VALIDATE_URL)) {
            $this->C->save("dbURL", $_POST['txt_dbURL']);
            $showAlert            = true;
            $alertData['type']    = 'success';
            $alertData['title']   = $this->LANG['alert_success_title'];
            $alertData['subject'] = $this->LANG['db_alert_url'];
            $alertData['text']    = $this->LANG['db_alert_url_success'];
            $alertData['help']    = '';
          }
          else {
            $showAlert            = true;
            $alertData['type']    = 'warning';
            $alertData['title']   = $this->LANG['alert_warning_title'];
            $alertData['subject'] = $this->LANG['db_alert_url'];
            $alertData['text']    = $this->LANG['db_alert_url_fail'];
            $alertData['help']    = '';
            $this->C->save("dbURL", "#");
          }
        }
        elseif (isset($_POST['btn_reset']) && $_POST['txt_dbResetString'] == "YesIAmSure") {
          // ,--------,
          // | Reset  |
          // '--------'
          $sqlFile = "sql/basic.sql";
          if (isset($_POST['opt_dataset']) && $_POST['opt_dataset'] === 'sample') {
            $sqlFile = "sql/sample.sql";
          }
          $query = file_get_contents($sqlFile);
          $this->DB->db->exec($query);
          $this->LOG->logEvent("logDatabase", $this->UL->username, "log_db_reset");
          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $this->LANG['alert_success_title'];
          $alertData['subject'] = $this->LANG['db_alert_reset'];
          $alertData['text']    = $this->LANG['db_alert_reset_success'];
          $alertData['help']    = '';
        }

        if (isset($_SESSION)) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
      }
      else {
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $this->LANG['alert_danger_title'];
        $alertData['subject'] = $this->LANG['alert_input'];
        $alertData['text']    = $this->LANG['db_alert_failed'];
        $alertData['help']    = '';
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['inputAlert'] = $inputAlert;
    $viewData['dbURL']      = $this->allConfig['dbURL'];
    $viewData['dbInfo']     = $this->DB->getAttributes();

    $this->render('database', $viewData);
  }

  /**
   * Deletes all orphaned announcements, meaning those announcements that are
   * not assigned to any user.
   */
  private function deleteOrphanedMessages(): void {
    $messages = $this->MSG->getAll();
    foreach ($messages as $msg) {
      if (!count($this->UMSG->getAllByMsgId($msg['id']))) {
        $this->MSG->delete($msg['id']);
      }
    }
  }
}
