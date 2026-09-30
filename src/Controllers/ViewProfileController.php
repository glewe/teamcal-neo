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
      if (!$this->userModel->findByName($profile)) {
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

    $this->userModel->findByName($profile);
    $viewData['username'] = $profile;
    $viewData['fullname'] = $this->userModel->getFullname($this->userModel->username);
    $viewData['avatar']   = ($this->userOptionModel->read($this->userModel->username, 'avatar')) ? $this->userOptionModel->read($this->userModel->username, 'avatar') : 'default_' . $this->userOptionModel->read($this->userModel->username, 'gender') . '.png';
    $viewData['role']     = $this->roleModel->getNameById((string) $this->userModel->role);
    $viewData['title']    = $this->userOptionModel->read($this->userModel->username, 'title');
    $viewData['position'] = $this->userOptionModel->read($this->userModel->username, 'position');
    $viewData['email']    = $this->userModel->email;
    $viewData['phone']    = $this->userOptionModel->read($this->userModel->username, 'phone');
    $viewData['mobile']   = $this->userOptionModel->read($this->userModel->username, 'mobile');
    $viewData['facebook'] = $this->userOptionModel->read($this->userModel->username, 'facebook');
    $viewData['google']   = $this->userOptionModel->read($this->userModel->username, 'google');
    $viewData['linkedin'] = $this->userOptionModel->read($this->userModel->username, 'linkedin');
    $viewData['skype']    = $this->userOptionModel->read($this->userModel->username, 'skype');
    $viewData['twitter']  = $this->userOptionModel->read($this->userModel->username, 'twitter');

    $viewData['allowEdit'] = false;
    if (($this->loginModel->checkLogin() && $this->userLoggedIn->username == $viewData['username']) || isAllowed($this->CONF['controllers']['useredit']->permission)) {
      $viewData['allowEdit'] = true;
    }

    $viewData['allowAbsum'] = false;
    if (isAllowed($this->CONF['controllers']['absum']->permission)) {
      $viewData['allowAbsum'] = true;
    }

    $this->render('viewprofile', $viewData);
  }
}
