<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UploadModel;

/**
 * User Import Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class UserImportController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['userimport']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $UPL                    = new UploadModel($this->LANG);
    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];
    $uplDir                 = WEBSITE_ROOT . '/' . APP_IMP_DIR;

    $alertData = [];
    $showAlert = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_import'])) {
        $UPL->upload_dir        = $uplDir;
        $UPL->extensions        = $this->CONF['impExtensions'];
        $UPL->do_filename_check = "y";
        $UPL->replace           = "y";
        $UPL->the_temp_file     = $_FILES['file_image']['tmp_name'];
        $UPL->the_file          = $_FILES['file_image']['name'];
        $UPL->http_error        = $_FILES['file_image']['error'];

        if ($UPL->uploadFile()) {
          $viewData['defaultGroup'] = $_POST['sel_group'];
          $viewData['defaultRole']  = $_POST['sel_role'];

          if (($handle = fopen($uplDir . $UPL->the_file, "r")) !== false) {
            $line        = 0;
            $errorCount  = 0;
            $errorText   = '';
            $importCount = 0;
            $numCols     = 5;

            while (($arr = fgetcsv($handle, 1000, ";", "\"", "\\")) !== false) {
              $line++;
              if (count($arr) <> $numCols) {
                $errorCount++;
                $errorText .= '<li>' . sprintf($this->LANG['alert_imp_columns'], $line, $numCols, $arr[0], count($arr), $numCols) . '</li>';
                continue;
              }

              $CSVusername  = trim($arr[0]);
              $CSVfirstname = trim($arr[1]);
              $CSVlastname  = trim($arr[2]);
              $CSVemail     = trim($arr[3]);
              $CSVgender    = trim($arr[4]);

              if ($CSVusername == "admin") {
                $errorCount++;
                $errorText .= '<li>' . sprintf($this->LANG['alert_imp_admin'], $line) . '</li>';
                continue;
              }

              if ($this->U->findByName($CSVusername)) {
                $errorCount++;
                $errorText .= '<li>' . sprintf($this->LANG['alert_imp_exists'], $line, $CSVusername) . '</li>';
                continue;
              }

              if (!preg_match("/^([\pL\w.@])+$/u", $CSVusername)) {
                $errorCount++;
                $errorText .= '<li>' . sprintf($this->LANG['alert_imp_username'], $line, $CSVusername) . '</li>';
                continue;
              }

              if (!preg_match("/^[ \pL\w._-]+$/u", $CSVfirstname)) {
                $errorCount++;
                $errorText .= '<li>' . sprintf($this->LANG['alert_imp_firstname'], $line, $CSVfirstname) . '</li>';
                continue;
              }

              if (!preg_match("/^[ \pL\w._-]+$/u", $CSVlastname)) {
                $errorCount++;
                $errorText .= '<li>' . sprintf($this->LANG['alert_imp_lastname'], $line, $CSVlastname) . '</li>';
                continue;
              }

              if (!validEmail($CSVemail)) {
                $errorCount++;
                $errorText .= '<li>' . sprintf($this->LANG['alert_imp_email'], $line, $CSVemail) . '</li>';
                continue;
              }

              if ($CSVgender != "male" && $CSVgender != "female") {
                $errorCount++;
                $errorText .= '<li>' . sprintf($this->LANG['alert_imp_gender'], $line, $CSVgender) . '</li>';
                continue;
              }

              $this->U->username  = $CSVusername;
              $this->U->password  = password_hash("password", PASSWORD_DEFAULT);
              $this->U->firstname = $CSVfirstname;
              $this->U->lastname  = $CSVlastname;
              $this->U->email     = $CSVemail;

              if (isset($_POST['sel_role'])) {
                $this->U->role = (int) $_POST['sel_role'];
              }
              else {
                $this->U->role = 2;
              }

              $this->U->hidden         = (isset($_POST['chk_hidden']) && $_POST['chk_hidden']) ? 1 : 0;
              $this->U->locked         = (isset($_POST['chk_locked']) && $_POST['chk_locked']) ? 1 : 0;
              $this->U->onhold         = 0;
              $this->U->verify         = 0;
              $this->U->bad_logins     = 0;
              $this->U->grace_start    = DEFAULT_TIMESTAMP;
              $this->U->last_login     = DEFAULT_TIMESTAMP;
              $this->U->created        = date('YmdHis');
              $this->U->last_pw_change = date('YmdHis');
              $this->U->create();

              if (isset($_POST['sel_group']) && $this->G->getById($_POST['sel_group']) && $this->U->findByName($CSVusername)) {
                $this->UG->save($CSVusername, $_POST['sel_group'], 'member');
              }

              $this->UO->save($CSVusername, 'gender', $CSVgender);
              $this->UO->save($CSVusername, 'avatar', 'default_' . $CSVgender . '.png');
              $this->UO->save($CSVusername, 'language', 'default');
              $importCount++;
            }
            fclose($handle);

            if ($errorCount) {
              $showAlert            = true;
              $alertData['type']    = 'warning';
              $alertData['title']   = $this->LANG['alert_warning_title'];
              $alertData['subject'] = $this->LANG['alert_imp_subject'];
              $alertData['text']    = '<ul>' . $errorText . '</ul><p><br>' . sprintf($this->LANG['imp_alert_success_text'], $importCount) . '</p>';
              $alertData['help']    = $this->LANG['imp_alert_help'];
            }
            else {
              $showAlert            = true;
              $alertData['type']    = 'success';
              $alertData['title']   = $this->LANG['alert_success_title'];
              $alertData['subject'] = $this->LANG['imp_alert_success'];
              $alertData['text']    = sprintf($this->LANG['imp_alert_success_text'], $importCount);
              $alertData['help']    = '';
            }

            $this->LOG->logEvent("logImport", $this->UL->username, "log_imp_success", $UPL->the_file . " (" . $importCount . " " . $this->LANG['user'] . ")");
          }
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_upl_csv_subject'];
          $alertData['text']    = $UPL->getErrors();
          $alertData['help']    = '';
        }
      }

      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['upl_maxsize'] = $this->CONF['uplMaxsize'];
    $viewData['upl_formats'] = 'csv';
    $groups                  = $this->G->getAll();
    $roles                   = $this->RO->getAll();

    foreach ($groups as $group) {
      $viewData['groups'][] = ['val' => $group['id'], 'name' => $group['name'], 'selected' => false];
    }
    foreach ($roles as $role) {
      $viewData['roles'][] = ['val' => $role['id'], 'name' => $role['name'], 'selected' => ($role['id'] == 2)];
    }
    $viewData['import'] = [
      ['prefix' => 'imp', 'name' => 'group', 'type' => 'list', 'values' => $viewData['groups']],
      ['prefix' => 'imp', 'name' => 'role', 'type' => 'list', 'values' => $viewData['roles']],
      ['prefix' => 'imp', 'name' => 'hidden', 'type' => 'check', 'values' => '', 'value' => 1],
      ['prefix' => 'imp', 'name' => 'locked', 'type' => 'check', 'values' => '', 'value' => 1],
    ];

    $viewData['allowUserAccount'] = isAllowed('useraccount');

    $this->render('userimport', $viewData);
  }
}
