<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;


/**
 * View Profile Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class ViewProfileController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {

    // Check Permission
    if (!isAllowed($this->CONF['controllers']['viewprofile']->permission)) {
      $this->renderAlert('warning', $this->LANG['alert_alert_title'], $this->LANG['alert_not_allowed_subject'], $this->LANG['alert_not_allowed_text'], $this->LANG['alert_not_allowed_help']);
      return;
    }

    // Check URL Parameter
    $profile = '';
    if (isset($_GET['profile'])) {
      $profile = sanitize($_GET['profile']);
      if (!$this->U->findByName($profile)) {
        $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
        return;
      }
    }
    else {
      $this->renderAlert('danger', $this->LANG['alert_danger_title'], $this->LANG['alert_no_data_subject'], $this->LANG['alert_no_data_text'], $this->LANG['alert_no_data_help']);
      return;
    }

    $viewData               = [];
    $viewData['pageHelp']   = $this->allConfig['pageHelp'];
    $viewData['showAlerts'] = $this->allConfig['showAlerts'];

    $this->U->findByName($profile);
    $viewData['username'] = $profile;
    $viewData['fullname'] = $this->U->getFullname($this->U->username);
    $viewData['avatar']   = ($this->UO->read($this->U->username, 'avatar')) ? $this->UO->read($this->U->username, 'avatar') : 'default_' . $this->UO->read($this->U->username, 'gender') . '.png';
    $viewData['role']     = $this->RO->getNameById((string) $this->U->role);
    $viewData['title']    = $this->UO->read($this->U->username, 'title');
    $viewData['position'] = $this->UO->read($this->U->username, 'position');
    $viewData['email']    = $this->U->email;
    $viewData['phone']    = $this->UO->read($this->U->username, 'phone');
    $viewData['mobile']   = $this->UO->read($this->U->username, 'mobile');
    $viewData['facebook'] = $this->UO->read($this->U->username, 'facebook');
    $viewData['google']   = $this->UO->read($this->U->username, 'google');
    $viewData['linkedin'] = $this->UO->read($this->U->username, 'linkedin');
    $viewData['skype']    = $this->UO->read($this->U->username, 'skype');
    $viewData['twitter']  = $this->UO->read($this->U->username, 'twitter');

    $viewData['allowEdit'] = false;
    if (($this->L->checkLogin() && $this->UL->username == $viewData['username']) || isAllowed($this->CONF['controllers']['useredit']->permission)) {
      $viewData['allowEdit'] = true;
    }

    $viewData['allowAbsum'] = false;
    if (isAllowed($this->CONF['controllers']['absum']->permission)) {
      $viewData['allowAbsum'] = true;
    }

    $this->render('viewprofile', $viewData);
  }
}
