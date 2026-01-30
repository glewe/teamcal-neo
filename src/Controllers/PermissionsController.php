<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\LicenseModel;

/**
 * Permissions Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class PermissionsController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    if (!isAllowed($this->CONF['controllers']['permissions']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check License
    $alertData        = [];
    $showAlert        = false;
    $licExpiryWarning = (int) $this->allConfig['licExpiryWarning'];
    $LIC              = new LicenseModel();
    $LIC->check($alertData, $showAlert, (int) $licExpiryWarning, $this->LANG);

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    $roles  = $this->RO->getAll();
    $scheme = $this->allConfig['permissionScheme'] ?: "Default";
    if (isset($_GET['scheme']) && $this->P->schemeExists($_GET['scheme'])) {
      $scheme = $_GET['scheme'];
    }

    $modes = ['byperm', 'byrole'];
    $mode  = "byperm";
    if (isset($_GET['mode'])) {
      $mode = $_GET['mode'];
      if (!in_array($mode, $modes)) {
        $mode = 'byperm';
      }
    }

    $perms       = [];
    $permgroups  = [];
    $controllers = $this->CONF['controllers'];
    asort($controllers);
    foreach ($controllers as $contr) {
      if (strlen($contr->permission)) {
        $perms[]                          = $contr->permission;
        $permgroups[$contr->permission][] = $contr->permission;
      }
    }
    asort($perms);

    $fperms = [
      'calendareditown',
      'calendareditgroup',
      'calendareditgroupmanaged',
      'calendareditall',
      'calendarviewgroup',
      'calendarviewall',
      'daynoteglobal',
      'manageronlyabsences',
      'useraccount',
      'userabsences',
      'userallowance',
      'useravatar',
      'usercustom',
      'usergroups',
      'groupmemberships',
      'usernotifications',
      'useroptions',
    ];

    $inputAlert = [];
    $alertData  = [];
    $showAlert  = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
      $_POST = sanitize($_POST);

      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_csrf_invalid_subject'], $this->LANG['alert_csrf_invalid_text'], $this->LANG['alert_csrf_invalid_help']);
        return;
      }

      if (isset($_POST['btn_permActivate'])) {
        $this->C->save("permissionScheme", $_POST['sel_scheme']);
        $this->LOG->logEvent("logPermission", $this->UL->username, "log_perm_activated", $_POST['sel_scheme']);
        header("Location: index.php?action=permissions&scheme=" . $_POST['sel_scheme']);
        die();
      }
      elseif (isset($_POST['btn_permDelete'])) {
        if ($_POST['sel_scheme'] != "Default") {
          $this->P->deleteScheme($_POST['sel_scheme']);
          $this->C->save("permissionScheme", "Default");
          $this->LOG->logEvent("logPermission", $this->UL->username, "log_perm_deleted", $_POST['sel_scheme']);
          header("Location: index.php?action=permissions&scheme=Default");
          die();
        }
      }
      elseif (isset($_POST['btn_permCreate'])) {
        if (!preg_match('/^[a-zA-Z0-9-]*$/', $_POST['txt_newScheme'])) {
          $showAlert            = true;
          $alertData['type']    = 'danger';
          $alertData['title']   = $this->LANG['alert_danger_title'];
          $alertData['subject'] = $this->LANG['alert_input_validation_subject'];
          $alertData['text']    = str_replace('%1%', $_POST['txt_newScheme'], $this->LANG['alert_perm_invalid']);
          $alertData['help']    = '';
        }
        else {
          $scheme = $_POST['txt_newScheme'];
          if ($this->P->schemeExists($scheme)) {
            $showAlert            = true;
            $alertData['type']    = 'danger';
            $alertData['title']   = $this->LANG['alert_danger_title'];
            $alertData['subject'] = $this->LANG['alert_input_validation_subject'];
            $alertData['text']    = str_replace('%1%', $_POST['txt_newScheme'], $this->LANG['alert_perm_exists']);
            $alertData['help']    = '';
          }
        }

        if (!$showAlert) {
          $this->P->deleteScheme($scheme);
          $batchData = [];
          foreach ($perms as $perm) {
            foreach ($roles as $role) {
              $allowed     = ($role['id'] == 1) ? 1 : 0;
              $batchData[] = ['scheme' => $scheme, 'permission' => $perm, 'role' => $role['id'], 'allowed' => $allowed];
            }
          }
          foreach ($fperms as $fperm) {
            foreach ($roles as $role) {
              $allowed     = ($role['id'] == 1) ? 1 : 0;
              $batchData[] = ['scheme' => $scheme, 'permission' => $fperm, 'role' => $role['id'], 'allowed' => $allowed];
            }
          }
          $this->P->setPermissionsBatch($batchData);
          $this->LOG->logEvent("logPermission", $this->UL->username, "log_perm_created", $scheme);
          header("Location: index.php?action=permissions&scheme=" . $scheme);
          die();
        }
      }
      elseif (isset($_POST['btn_permReset'])) {
        if ($scheme != "Default") {
          $defaultPermissions = [];
          foreach ($perms as $perm) {
            foreach ($roles as $role) {
              $defaultPermissions[$perm][$role['id']] = $this->P->isAllowed("Default", $perm, $role['id']);
            }
          }
          foreach ($fperms as $fperm) {
            foreach ($roles as $role) {
              $defaultPermissions[$fperm][$role['id']] = $this->P->isAllowed("Default", $fperm, $role['id']);
            }
          }

          $batchData = [];
          foreach ($perms as $perm) {
            foreach ($roles as $role) {
              $allowed     = ($role['id'] == 1) ? 1 : (int) $defaultPermissions[$perm][$role['id']];
              $batchData[] = ['scheme' => $scheme, 'permission' => $perm, 'role' => $role['id'], 'allowed' => $allowed];
            }
          }
          foreach ($fperms as $fperm) {
            foreach ($roles as $role) {
              $allowed     = ($role['id'] == 1) ? 1 : (int) $defaultPermissions[$fperm][$role['id']];
              $batchData[] = ['scheme' => $scheme, 'permission' => $fperm, 'role' => $role['id'], 'allowed' => $allowed];
            }
          }
          $this->P->setPermissionsBatch($batchData);
          $this->LOG->logEvent("logPermission", $this->UL->username, "log_perm_reset", $scheme);
          header("Location: index.php?action=permissions&scheme=" . $scheme);
          die();
        }
        else {
          $showAlert            = true;
          $alertData['type']    = 'warning';
          $alertData['title']   = $this->LANG['alert_warning_title'];
          $alertData['subject'] = $this->LANG['alert_input_validation_subject'];
          $alertData['text']    = $this->LANG['alert_perm_default'];
          $alertData['help']    = '';
        }
      }
      elseif (isset($_POST['btn_permSave'])) {
        $batchData = [];
        foreach ($permgroups as $permgroup => $permnames) {
          foreach ($permnames as $permname) {
            foreach ($roles as $role) {
              $allowed = (isset($_POST['chk_' . $permgroup . '_' . $role['id']]) && $_POST['chk_' . $permgroup . '_' . $role['id']]) ? 1 : 0;
              if ($role['id'] == 1)
                $allowed = 1;
              $batchData[] = ['scheme' => $scheme, 'permission' => $permname, 'role' => $role['id'], 'allowed' => $allowed];
            }
          }
        }
        foreach ($fperms as $fperm) {
          foreach ($roles as $role) {
            $allowed = (isset($_POST['chk_' . $fperm . '_' . $role['id']]) && $_POST['chk_' . $fperm . '_' . $role['id']]) ? 1 : 0;
            if ($role['id'] == 1)
              $allowed = 1;
            $batchData[] = ['scheme' => $scheme, 'permission' => $fperm, 'role' => $role['id'], 'allowed' => $allowed];
          }
        }
        $this->P->setPermissionsBatch($batchData);
        $this->LOG->logEvent("logPermission", $this->UL->username, "log_perm_changed", $scheme);
        header("Location: index.php?action=permissions&scheme=" . $scheme);
        die();
      }
      elseif (isset($_POST['btn_permSelect'])) {
        header("Location: index.php?action=permissions&scheme=" . $_POST['sel_scheme']);
        die();
      }

      if (isset($_SESSION)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
    }

    if ($showAlert) {
      $viewData['alertData'] = $alertData;
      $viewData['showAlert'] = true;
    }

    $viewData['currentScheme'] = $this->allConfig['permissionScheme'];
    $viewData['mode']          = $mode;
    // Pre-calculate permissions matrix for the view
    $matrix = [];

    // For regular permissions
    foreach ($perms as $perm) {
      foreach ($roles as $role) {
        $matrix[$perm][$role['id']] = $this->P->isAllowed($scheme, $perm, $role['id']);
      }
    }

    // For feature permissions
    foreach ($fperms as $fperm) {
      foreach ($roles as $role) {
        $matrix[$fperm][$role['id']] = $this->P->isAllowed($scheme, $fperm, $role['id']);
      }
    }

    $viewData['matrix'] = $matrix;

    $viewData['perms']      = $perms;
    $viewData['permgroups'] = $permgroups;
    $viewData['fperms']     = $fperms;
    $viewData['roles']      = $roles;
    $viewData['schemes']    = $this->P->getSchemes();
    $viewData['scheme']     = $scheme;

    $this->render('permissions', $viewData);
  }
}
