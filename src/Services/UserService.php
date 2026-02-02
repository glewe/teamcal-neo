<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\AbsenceGroupModel;
use App\Models\AbsenceModel;
use App\Models\AllowanceModel;
use App\Models\AvatarModel;
use App\Models\DaynoteModel;
use App\Models\LogModel;
use App\Models\TemplateModel;
use App\Models\UserGroupModel;
use App\Models\UserMessageModel;
use App\Models\UserModel;
use App\Models\UserOptionModel;

/**
 * User Service
 *
 * @package TeamCal Neo
 */
class UserService
{
  private UserModel         $userModel;
  private UserGroupModel    $userGroupModel;
  private UserOptionModel   $userOptionModel;
  private TemplateModel     $templateModel;
  private DaynoteModel      $daynoteModel;
  private AllowanceModel    $allowanceModel;
  private UserMessageModel  $userMessageModel;
  private LogModel          $logModel;
  private AbsenceGroupModel $absenceGroupModel;
  private AvatarModel       $avatarModel;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   */
  public function __construct(
    UserModel $userModel,
    UserGroupModel $userGroupModel,
    UserOptionModel $userOptionModel,
    TemplateModel $templateModel,
    DaynoteModel $daynoteModel,
    AllowanceModel $allowanceModel,
    UserMessageModel $userMessageModel,
    LogModel $logModel,
    AbsenceGroupModel $absenceGroupModel,
    AvatarModel $avatarModel
  ) {
    $this->userModel         = $userModel;
    $this->userGroupModel    = $userGroupModel;
    $this->userOptionModel   = $userOptionModel;
    $this->templateModel     = $templateModel;
    $this->daynoteModel      = $daynoteModel;
    $this->allowanceModel    = $allowanceModel;
    $this->userMessageModel  = $userMessageModel;
    $this->logModel          = $logModel;
    $this->absenceGroupModel = $absenceGroupModel;
    $this->avatarModel       = $avatarModel;
  }

  //---------------------------------------------------------------------------
  /**
   * Archives a user and all related records.
   *
   * @param string $username Username to archive
   * @param string $loggedInUser Username of the person performing the action
   * @return bool Success code
   */
  public function archiveUser(string $username, string $loggedInUser = 'system'): bool {
    if (
      $this->userModel->exists($username, true) ||
      $this->userGroupModel->exists($username, true) ||
      $this->userOptionModel->exists($username, true) ||
      $this->templateModel->exists($username, true) ||
      $this->daynoteModel->exists($username, true) ||
      $this->allowanceModel->exists($username, true) ||
      $this->userMessageModel->exists($username, true)
    ) {
      return false;
    }

    $this->userModel->findByName($username);
    $fullname = trim($this->userModel->firstname . " " . $this->userModel->lastname);

    $this->userModel->archive($username);
    $this->userGroupModel->archive($username);
    $this->userOptionModel->archive($username);
    $this->templateModel->archive($username);
    $this->daynoteModel->archive($username);
    $this->allowanceModel->archive($username);
    $this->userMessageModel->archive($username);

    $this->deleteUser($username, false, false, $loggedInUser);

    $this->logModel->logEvent("logUser", $loggedInUser, "log_user_archived", $fullname . " (" . $username . ")");
    return true;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a user and all related records.
   *
   * @param string $username User to delete
   * @param bool $fromArchive Flag whether to delete from archive tables
   * @param bool $sendNotifications Flag whether to send notifications
   * @param string $loggedInUser Username of the person performing the action
   */
  public function deleteUser(string $username, bool $fromArchive = false, bool $sendNotifications = true, string $loggedInUser = 'system'): void {
    $this->userModel->findByName($username);
    $fullname = trim($this->userModel->firstname . " " . $this->userModel->lastname);

    $this->userModel->deleteByName($username, $fromArchive);
    $this->userGroupModel->deleteByUser($username, $fromArchive);
    $this->userOptionModel->deleteByUser($username, $fromArchive);
    $this->userMessageModel->deleteByUser($username, $fromArchive);

    if ($fromArchive) {
      $this->avatarModel->delete($username, $this->userOptionModel->read($username, 'avatar'));
    }

    $this->templateModel->deleteByUser($username, $fromArchive);
    $this->daynoteModel->deleteByUser($username, $fromArchive);
    $this->allowanceModel->deleteByUser($username, $fromArchive);

    if ($sendNotifications && function_exists('sendUserEventNotifications')) {
      sendUserEventNotifications("deleted", $username, $this->userModel->firstname, $this->userModel->lastname);
    }

    if ($fromArchive) {
      $this->logModel->logEvent("logUser", $loggedInUser, "log_user_archived_deleted", $fullname . " (" . $username . ")");
    }
    else {
      $this->logModel->logEvent("logUser", $loggedInUser, "log_user_deleted", $fullname . " (" . $username . ")");
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Restores a user and all related records from archive.
   *
   * @param string $username Username to restore
   * @param string $loggedInUser Username of the person performing the action
   * @return bool Success code
   */
  public function restoreUser(string $username, string $loggedInUser = 'system'): bool {
    if (
      $this->userModel->exists($username) ||
      $this->userGroupModel->exists($username) ||
      $this->userOptionModel->exists($username) ||
      $this->templateModel->exists($username) ||
      $this->daynoteModel->exists($username) ||
      $this->allowanceModel->exists($username) ||
      $this->userMessageModel->exists($username)
    ) {
      return false;
    }

    $this->userModel->findByName($username);
    $fullname = trim($this->userModel->firstname . " " . $this->userModel->lastname);

    $this->userModel->restore($username);
    $this->userGroupModel->restore($username);
    $this->userOptionModel->restore($username);
    $this->templateModel->restore($username);
    $this->daynoteModel->restore($username);
    $this->allowanceModel->restore($username);
    $this->userMessageModel->restore($username);

    $this->deleteUser($username, true, false, $loggedInUser);

    $this->logModel->logEvent("logUser", $loggedInUser, "log_user_restored", $fullname . " (" . $username . ")");
    return true;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether an absence type is valid for a given user based on group memberships.
   *
   * @param string $absid Absence ID
   * @param string $username Username
   * @return bool
   */
  public function absenceIsValidForUser(string $absid, string $username): bool {
    if (empty($absid)) {
      return false;
    }

    if (empty($username) && function_exists('isAllowed') && isAllowed('calendaredit')) {
      return true;
    }

    $userGroups = $this->userGroupModel->getAllForUser($username);

    foreach ($userGroups as $group) {
      if ($this->absenceGroupModel->isAssigned($absid, (string) $group['groupid'])) {
        return true;
      }
    }

    return false;
  }
}
