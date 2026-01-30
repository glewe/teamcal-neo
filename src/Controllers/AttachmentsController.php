<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UploadModel;

/**
 * Attachments Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AttachmentsController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    if (!isAllowed($this->CONF['controllers']['attachments']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    $UPL = new UploadModel($this->LANG);

    $uplDir                     = WEBSITE_ROOT . '/' . APP_UPL_DIR;
    $viewData                   = [];
    $viewData['pageHelp']       = $this->allConfig['pageHelp'];
    $viewData['showAlerts']     = $this->allConfig['showAlerts'];
    $viewData['shareWith']      = 'all';
    $viewData['shareWithGroup'] = [];
    $viewData['shareWithRole']  = [];
    $viewData['shareWithUser']  = [];
    $viewData['uplFiles']       = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_uploadFile'])) {
        $this->handleUpload($UPL, $uplDir);
      }
      elseif (isset($_POST['btn_deleteFile'])) {
        $this->handleDelete($uplDir);
      }
      else {
        $this->handleShareUpdates();
      }
    }

    // Prepare View Data
    $viewData['upl_maxsize'] = $this->CONF['uplMaxsize'];
    $viewData['upl_formats'] = implode(', ', $this->CONF['uplExtensions']);
    $files                   = getFiles(APP_UPL_DIR, $this->CONF['uplExtensions'], '');
    $allUsers                = $this->U->getAll();
    $fileMetadata            = [];
    foreach ($files as $file) {
      $fid                 = $this->AT->getId($file);
      $owner               = $this->AT->getUploader($file);
      $fileMetadata[$file] = ['id' => $fid, 'owner' => $owner];
    }

    foreach ($files as $file) {
      $fid                  = $fileMetadata[$file]['id'];
      $owner                = $fileMetadata[$file]['owner'];
      $isOwner              = ($this->UL->username == 'admin' || $this->UL->username == $owner);
      $currentUserHasAccess = ($this->UL->username == 'admin' || $this->UAT->hasAccess($this->UL->username, $fid));

      if ($currentUserHasAccess) {
        $access = [];
        foreach ($allUsers as $user) {
          $access[$user['username']] = $this->UAT->hasAccess($user['username'], $fid);
        }
        $ext                    = getFileExtension($file);
        $viewData['uplFiles'][] = [
          'fid'     => $fid,
          'fname'   => $file,
          'owner'   => $owner,
          'isOwner' => $isOwner,
          'access'  => $access,
          'ext'     => $ext
        ];
      }
    }

    $viewData['groups'] = $this->G->getAll();
    $viewData['roles']  = $this->RO->getAll();
    $viewData['users']  = $allUsers;

    $this->render('attachments', $viewData);
  }

  //---------------------------------------------------------------------------
  /**
   * Handles the file upload process.
   *
   * @param UploadModel $UPL    Upload model instance
   * @param string      $uplDir Upload directory path
   *
   * @return void
   */
  private function handleUpload($UPL, $uplDir) {
    $UPL->upload_dir        = $uplDir;
    $UPL->extensions        = $this->CONF['uplExtensions'];
    $UPL->do_filename_check = "y";
    $UPL->replace           = "y";
    $UPL->the_temp_file     = $_FILES['file_image']['tmp_name'] ?? '';
    $safeFileName           = str_replace(' ', '_', $_FILES['file_image']['name'] ?? '');
    $UPL->the_file          = $safeFileName;
    $UPL->http_error        = $_FILES['file_image']['error'] ?? 0;

    if ($UPL->uploadFile()) {
      $this->AT->create($UPL->the_file, $this->UL->username);
      $fileid = $this->AT->getId($UPL->the_file);
      $this->UAT->create($this->UL->username, $fileid);

      switch ($_POST['opt_shareWith']) {
        case "admin":
          $this->UAT->create('admin', $fileid);
          break;
        case "all":
          $users = $this->U->getAll();
          foreach ($users as $user) {
            $this->UAT->create($user['username'], $fileid);
          }
          break;
        case "group":
          if (isset($_POST['sel_shareWithGroup'])) {
            foreach ($_POST['sel_shareWithGroup'] as $gto) {
              $groupusers = $this->UG->getAllForGroup((string) $gto);
              foreach ($groupusers as $groupuser) {
                $this->UAT->create($groupuser['username'], $fileid);
              }
            }
          }
          else {
            $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['msg_no_group_subject'], $this->LANG['msg_no_group_text']);
            return;
          }
          break;
        case "role":
          if (isset($_POST['sel_shareWithRole'])) {
            foreach ($_POST['sel_shareWithRole'] as $rto) {
              $roleusers = $this->U->getAllForRole($rto);
              foreach ($roleusers as $roleuser) {
                $this->UAT->create($roleuser['username'], $fileid);
              }
            }
          }
          else {
            $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['msg_no_group_subject'], $this->LANG['msg_no_group_text']);
            return;
          }
          break;
        case "user":
          if (isset($_POST['sel_shareWithUser'])) {
            foreach ($_POST['sel_shareWithUser'] as $uto) {
              $this->UAT->create($uto, $fileid);
            }
          }
          else {
            $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['msg_no_user_subject'], $this->LANG['msg_no_user_text']);
            return;
          }
          break;
      }

      if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
      $uploadedFileName = $UPL->uploaded_file['name'] ?? $UPL->the_file;
      $this->LOG->logEvent("logUpload", $this->UL->username, "log_upload_image", $uploadedFileName);
      header("Location: index.php?action=attachments");
      die();
    }
    else {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_upl_img_subject'], $UPL->getErrors(), sprintf($this->LANG['att_file_comment'], $this->CONF['uplMaxsize'] / 1024, implode(', ', $this->CONF['uplExtensions']), APP_UPL_DIR));
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Handles the file deletion process.
   *
   * @param string $uplDir Upload directory path
   *
   * @return void
   */
  private function handleDelete($uplDir) {
    if (isset($_POST['chk_file'])) {
      foreach ($_POST['chk_file'] as $file) {
        if (isValidFileName($file) && ($this->UL->username == 'admin' || $this->UL->username == $this->AT->getUploader($file))) {
          $fileid = $this->AT->getId($file);
          $this->AT->delete($file);
          $this->UAT->deleteFile($fileid);
          @unlink($uplDir . $file);
        }
      }
    }
    else {
      $this->renderAlert('warning', $this->LANG['alert_warning_title'], $this->LANG['msg_no_file_subject'], $this->LANG['msg_no_file_text']);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Handles updates to file sharing permissions.
   *
   * @return void
   */
  private function handleShareUpdates() {
    $files = $this->AT->getAll();
    foreach ($files as $file) {
      if (isset($_POST['btn_updateShares' . $file['id']])) {
        $this->UAT->deleteFile($file['id']);
        if (isset($_POST['sel_shares' . $file['id']])) {
          foreach ($_POST['sel_shares' . $file['id']] as $uto) {
            $this->UAT->create($uto, $file['id']);
          }
        }
        $this->UAT->create($this->AT->getUploaderById($file['id']), $file['id']);
      }
      elseif (isset($_POST['btn_clearShares' . $file['id']])) {
        $this->UAT->deleteFile($file['id']);
        $this->UAT->create($this->AT->getUploaderById($file['id']), $file['id']);
      }
    }
  }
}
