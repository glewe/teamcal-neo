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

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
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
    $allUsers                = $this->userModel->getAll();
    $fileMetadata            = [];
    foreach ($files as $file) {
      $fid                 = $this->attachmentModel->getId($file);
      $owner               = $this->attachmentModel->getUploader($file);
      $fileMetadata[$file] = ['id' => $fid, 'owner' => $owner];
    }

    foreach ($files as $file) {
      $fid                  = $fileMetadata[$file]['id'];
      $owner                = $fileMetadata[$file]['owner'];
      $isOwner              = ($this->userLoggedIn->is_system || $this->userLoggedIn->username == $owner);
      $currentUserHasAccess = ($this->userLoggedIn->is_system || $this->userAttachmentModel->hasAccess($this->userLoggedIn->username, $fid));

      if ($currentUserHasAccess) {
        $access = [];
        foreach ($allUsers as $user) {
          $access[$user['username']] = $this->userAttachmentModel->hasAccess($user['username'], $fid);
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

    $viewData['groups'] = $this->groupModel->getAll();
    $viewData['roles']  = $this->roleModel->getAll();
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
    $UPL->max_size          = (int) $this->CONF['uplMaxsize'];
    $UPL->do_filename_check = "y";
    $UPL->replace           = "y";
    $UPL->the_temp_file     = $_FILES['file_image']['tmp_name'] ?? '';
    $safeFileName           = str_replace(' ', '_', $_FILES['file_image']['name'] ?? '');
    $UPL->the_file          = $safeFileName;
    $UPL->http_error        = $_FILES['file_image']['error'] ?? 0;

    if ($UPL->uploadFile()) {
      $this->attachmentModel->create($UPL->the_file, $this->userLoggedIn->username);
      $fileid = $this->attachmentModel->getId($UPL->the_file);
      $this->userAttachmentModel->create($this->userLoggedIn->username, $fileid);

      switch ($_POST['opt_shareWith']) {
        case "admin":
          $this->userAttachmentModel->create('admin', $fileid);
          break;
        case "all":
          $users = $this->userModel->getAll();
          foreach ($users as $user) {
            $this->userAttachmentModel->create($user['username'], $fileid);
          }
          break;
        case "group":
          if (isset($_POST['sel_shareWithGroup'])) {
            foreach ($_POST['sel_shareWithGroup'] as $gto) {
              $groupusers = $this->userGroupModel->getAllForGroup((string) $gto);
              foreach ($groupusers as $groupuser) {
                $this->userAttachmentModel->create($groupuser['username'], $fileid);
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
              $roleusers = $this->userModel->getAllForRole($rto);
              foreach ($roleusers as $roleuser) {
                $this->userAttachmentModel->create($roleuser['username'], $fileid);
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
              $this->userAttachmentModel->create($uto, $fileid);
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
      $this->logModel->logEvent("logUpload", $this->userLoggedIn->username, "log_upload_image", $uploadedFileName);
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
        if (isValidFileName($file) && ($this->userLoggedIn->is_system || $this->userLoggedIn->username == $this->attachmentModel->getUploader($file))) {
          $fileid = $this->attachmentModel->getId($file);
          $this->attachmentModel->delete($file);
          $this->userAttachmentModel->deleteFile($fileid);
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
    $files = $this->attachmentModel->getAll();
    foreach ($files as $file) {
      if (isset($_POST['btn_updateShares' . $file['id']])) {
        $this->userAttachmentModel->deleteFile($file['id']);
        if (isset($_POST['sel_shares' . $file['id']])) {
          foreach ($_POST['sel_shares' . $file['id']] as $uto) {
            $this->userAttachmentModel->create($uto, $file['id']);
          }
        }
        $this->userAttachmentModel->create($this->attachmentModel->getUploaderById($file['id']), $file['id']);
      }
      elseif (isset($_POST['btn_clearShares' . $file['id']])) {
        $this->userAttachmentModel->deleteFile($file['id']);
        $this->userAttachmentModel->create($this->attachmentModel->getUploaderById($file['id']), $file['id']);
      }
    }
  }
}
