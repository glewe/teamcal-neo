<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AbsenceDayModel;
use App\Models\AbsenceModel;
use App\Models\AllowanceModel;
use App\Models\CalendarDayModel;
use App\Models\ConfigModel;
use App\Models\DaynoteModel;
use App\Models\GroupModel;
use App\Models\HolidayModel;
use App\Models\UserGroupModel;
use App\Models\UserModel;
use App\Models\UserOptionModel;
use App\Models\LoginModel;
use DateTime;
use Exception;

/**
 * AbsenceService
 *
 * This service handles complex business logic related to absences,
 * thresholds, and approvals.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     5.0.0
 */
class AbsenceService {
  private AbsenceModel     $absenceModel;
  private AbsenceDayModel  $absenceDayModel;
  private AllowanceModel   $allowanceModel;
  private ConfigModel      $configModel;
  private DaynoteModel     $daynoteModel;
  private GroupModel       $groupModel;
  private HolidayModel     $holidayModel;
  private CalendarDayModel $calendarDayModel;
  private UserGroupModel   $userGroupModel;
  private UserModel        $userModel;
  private UserModel        $userLoggedIn;
  /** @var array<string, string> */
  private array            $LANG;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param AbsenceModel $absenceModel Absence model
   * @param AbsenceDayModel $absenceDayModel Absence day model
   * @param AllowanceModel $allowanceModel Allowance model
   * @param ConfigModel $configModel Config model
   * @param DaynoteModel $daynoteModel Daynote model
   * @param GroupModel $groupModel Group model
   * @param HolidayModel $holidayModel Holiday model
   * @param CalendarDayModel $calendarDayModel Calendar day model
   * @param UserGroupModel $userGroupModel User group model
   * @param UserModel $userModel User model
   * @param UserModel            $userLoggedIn   Logged in user model
   * @param array<string, string> $LANG Language array
   */
  public function __construct(
    AbsenceModel $absenceModel,
    AbsenceDayModel $absenceDayModel,
    AllowanceModel $allowanceModel,
    ConfigModel $configModel,
    DaynoteModel $daynoteModel,
    GroupModel $groupModel,
    HolidayModel $holidayModel,
    CalendarDayModel $calendarDayModel,
    UserGroupModel $userGroupModel,
    UserModel $userModel,
    UserModel $userLoggedIn,
    array $LANG
  ) {
    $this->absenceModel    = $absenceModel;
    $this->absenceDayModel   = $absenceDayModel;
    $this->allowanceModel   = $allowanceModel;
    $this->configModel    = $configModel;
    $this->daynoteModel    = $daynoteModel;
    $this->groupModel    = $groupModel;
    $this->holidayModel    = $holidayModel;
    $this->calendarDayModel   = $calendarDayModel;
    $this->userGroupModel   = $userGroupModel;
    $this->userModel    = $userModel;
    $this->userLoggedIn   = $userLoggedIn;
    $this->LANG = $LANG;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether the maximum absences threshold is reached.
   *
   * @param string $year Year
   * @param string $month Month
   * @param string $day Day
   * @param string $base Base type (group or user)
   * @param string $group Group ID (optional)
   *
   * @return bool True if threshold reached, false otherwise
   */
  public function absenceThresholdReached(string $year, string $month, string $day, string $base, string $group = ''): bool {
    try {
      if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) {
        return false;
      }

      if ($base === "group") {
        $members   = $this->userGroupModel->getAllForGroup((string) $group);
        $usercount = $this->userGroupModel->countMembers((string) $group);
        if ($usercount === 0)
          return false;

        $ymd      = $year . $month . sprintf('%02d', (int) $day);
        $absences = 0;
        foreach ($members as $member) {
          $absences += $this->absenceDayModel->countAllAbsences($member['username'], $ymd, $ymd);
        }
      } else {
        $usercount = $this->userModel->countUsers();
        if ($usercount === 0)
          return false;
        $lastDayOfMonth = date('t', strtotime($year . '-' . $month . '-01'));
        $absences       = $this->absenceDayModel->countAllAbsences('%', $year . $month . sprintf('%02d', (int) $day), $year . $month . sprintf('%02d', $lastDayOfMonth));
      }

      $absences++;
      $absencerate = (100 * $absences) / $usercount;
      $threshold   = (int) $this->configModel->read("declThreshold");

      return $absencerate >= $threshold;
    } catch (Exception $e) {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks an array of requested absences against the declination rules and
   * other possible restrictions.
   *
   * @param string                     $username          Username
   * @param string                     $year              Year
   * @param string                     $month             Month
   * @param array<int, bool|int|string> $currentAbsences   Current absences array
   * @param array<int, bool|int|string> $requestedAbsences Requested absences array
   * @param string                     $regionId          Region ID
   * @param string                     $errorMessage      Error message reference
   *
   * @return array<string, mixed> Approval result with approved and declined absences
   */
  public function approveAbsences(string $username, string $year, string $month, array $currentAbsences, array $requestedAbsences, string $regionId, string &$errorMessage = ''): array {
    $approvalResult = array(
      'approvalResult'   => 'all',
      'approvedAbsences' => array(),
      'currentAbsences'  => $currentAbsences,
      'declinedAbsences' => array(),
      'declinedReasons'  => array(),
      'allChangesInPast' => false
    );

    $approvedAbsences   = array();
    $declinedAbsences   = array();
    $declinedReasons    = array();
    $declinedReasonsLog = array();
    $thresholdReached   = false;
    $takeoverRequested  = false;
    $approvalDays       = array();

    $monthInfo  = dateInfo($year, $month);
    $userGroups = $this->userGroupModel->getAllforUser((string) $username);

    for ($i = 1; $i <= $monthInfo['daysInMonth']; $i++) {
      $approvedAbsences[$i]   = '0';
      $declinedAbsences[$i]   = '0';
      $declinedReasons[$i]    = '';
      $declinedReasonsLog[$i] = '';
      if (!isset($currentAbsences[$i]))
        $currentAbsences[$i] = '0';
      if (!isset($requestedAbsences[$i]))
        $requestedAbsences[$i] = '0';
    }

    $arraysDiffer                       = false;
    $approvalResult['allChangesInPast'] = true;
    $todayDate                          = date("Ymd", time());
    for ($i = 1; $i <= $monthInfo['daysInMonth']; $i++) {
      if ($currentAbsences[$i] != $requestedAbsences[$i]) {
        $arraysDiffer = true;
        $iDate        = intval($year . $month . sprintf("%02d", $i));
        if ($iDate >= $todayDate) {
          $approvalResult['allChangesInPast'] = false;
        }
        if ($requestedAbsences[$i] == 'takeover') {
          $takeoverRequested = true;
        }
      }
    }

    if (($this->userLoggedIn->is_system || isAllowed("calendareditall")) && !$takeoverRequested) {
      $approvalResult['approvalResult'] = 'all';
      return $approvalResult;
    }

    $holidayMap = $this->calendarDayModel->getMonthMap($year, $month, $regionId);

    if ($arraysDiffer) {
      for ($i = 1; $i <= $monthInfo['daysInMonth']; $i++) {
        if ($currentAbsences[$i] != $requestedAbsences[$i]) {
          $requestedDate        = $year . '-' . $month . '-' . sprintf("%02d", ($i));
          $approvedAbsences[$i] = $requestedAbsences[$i];

          if ($requestedAbsences[$i] == 'takeover') {
            if ($this->absenceModel->isTakeover($currentAbsences[$i])) {
              $requestedAbsences[$i] = '0';
              $approvedAbsences[$i]  = '0';
              $this->absenceDayModel->setAbsence($username, $year, $month, (string) $i, '0');
              $this->absenceDayModel->setAbsence($this->userLoggedIn->username, $year, $month, (string) $i, $currentAbsences[$i]);
            } else {
              $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong>: " . sprintf($this->LANG['alert_decl_takeover'], $this->absenceModel->getName($currentAbsences[$i]));
              $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . sprintf($this->LANG['alert_decl_takeover'], $this->absenceModel->getName($currentAbsences[$i]));
              $declinedAbsences[$i]   = $currentAbsences[$i];
              $approvedAbsences[$i]   = $currentAbsences[$i];
            }
          } else {
            $declScopeRoles = array();
            $declInScope    = true;
            if ($declScope = $this->configModel->read("declScope")) {
              $declScopeRoles = explode(',', $declScope);
              $ulRole         = $this->userLoggedIn->getRole($this->userLoggedIn->username);
              if (!in_array($ulRole, $declScopeRoles)) {
                $declInScope = false;
              }
            }

            if ($declInScope) {
              $groups = "";
              foreach ($userGroups as $row) {
                if (
                  $requestedAbsences[$i] && !$this->absenceModel->getCountsAsPresent($requestedAbsences[$i]) && ($this->presenceMinimumReached($year, $month, (string) $i, (string) $row['groupid']) || $this->presenceMinimumWeReached($year, $month, (string) $i, (string) $row['groupid'])) &&
                  (!isAllowed("calendareditgroup") || (!$this->userGroupModel->isGroupManagerOfGroup($this->userLoggedIn->username, (string) $row['id']) && !$this->userGroupModel->isMemberOrManagerOfGroup($this->userLoggedIn->username, (string) $row['groupid'])))
                ) {
                  $affectedgroups[] = $row['groupid'];
                  $minimum          = ''; // Initialize to prevent undefined variable
                  if ($this->presenceMinimumReached($year, $month, (string) $i, (string) $row['groupid'])) {
                    $minimum = $this->LANG['weekdays'] . ": " . $this->groupModel->getMinPresent($row['groupid']);
                  } elseif ($this->presenceMinimumWeReached($year, $month, (string) $i, (string) $row['groupid'])) {
                    $minimum = $this->LANG['weekends'] . ": " . $this->groupModel->getMinPresentWe($row['groupid']);
                  }
                  $groups .= $this->groupModel->getNameById($row['groupid']) . " (" . $minimum . "), ";
                }
              }

              if (strlen($groups)) {
                $groups                 = substr($groups, 0, strlen($groups) - 2);
                $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_group_minpresent'] . $groups;
                $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_group_minpresent'] . $groups;
                $declinedAbsences[$i]   = $requestedAbsences[$i];
                $approvedAbsences[$i]   = $currentAbsences[$i];
                $thresholdReached       = true;
              }

              $groups = "";
              foreach ($userGroups as $row) {
                if (
                  $requestedAbsences[$i] && !$this->absenceModel->getCountsAsPresent($requestedAbsences[$i]) && ($this->absenceMaximumReached($year, $month, (string) $i, $row['groupid']) || $this->absenceMaximumWeReached($year, $month, (string) $i, $row['groupid'])) &&
                  (!isAllowed("calendareditgroup") || (!$this->userGroupModel->isGroupManagerOfGroup($this->userLoggedIn->username, (string) $row['id']) && !$this->userGroupModel->isMemberOrManagerOfGroup($this->userLoggedIn->username, (string) $row['groupid'])))
                ) {
                  $affectedgroups[] = $row['groupid'];
                  $maximum          = ''; // Initialize to prevent undefined variable
                  if ($this->absenceMaximumReached($year, $month, (string) $i, $row['groupid'])) {
                    $maximum = $this->LANG['weekdays'] . ": " . $this->groupModel->getMaxAbsent($row['groupid']);
                  } elseif ($this->absenceMaximumWeReached($year, $month, (string) $i, $row['groupid'])) {
                    $maximum = $this->LANG['weekends'] . ": " . $this->groupModel->getMaxAbsentWe($row['groupid']);
                  }
                  $groups .= $this->groupModel->getNameById($row['groupid']) . " (" . $maximum . "), ";
                }
              }

              if (strlen($groups)) {
                $groups                 = substr($groups, 0, strlen($groups) - 2);
                $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_group_maxabsent'] . $groups;
                $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_group_maxabsent'] . $groups;
                $declinedAbsences[$i]   = $requestedAbsences[$i];
                $approvedAbsences[$i]   = $currentAbsences[$i];
                $thresholdReached       = true;
              }

              if ($this->configModel->read("declAbsence") && $requestedAbsences[$i] != '0' && !$this->absenceModel->getCountsAsPresent($requestedAbsences[$i])) {
                $today         = date('Ymd');
                $declStartdate = str_replace('-', '', $this->configModel->read('declAbsenceStartdate'));
                $declEnddate   = str_replace('-', '', $this->configModel->read('declAbsenceEnddate'));
                $applyRule     = true;
                switch ($this->configModel->read('declAbsencePeriod')) {
                  case 'nowEnddate':
                    if ($today > $declEnddate)
                      $applyRule = false;
                    break;
                  case 'startdateForever':
                    if ($today < $declStartdate)
                      $applyRule = false;
                    break;
                  case 'startdateEnddate':
                    if ($today < $declStartdate || $today > $declEnddate)
                      $applyRule = false;
                    break;
                }

                if ($applyRule) {
                  if ($this->configModel->read("declBase") == "group") {
                    $groups = "";
                    foreach ($userGroups as $row) {
                      if (
                        $requestedAbsences[$i] && $this->absenceThresholdReached($year, $month, (string) $i, "group", (string) $row['groupid']) &&
                        (!isAllowed("calendareditgroup") || (!$this->userGroupModel->isGroupManagerOfGroup($this->userLoggedIn->username, (string) $row['id']) && !$this->userGroupModel->isMemberOrManagerOfGroup($this->userLoggedIn->username, (string) $row['groupid'])))
                      ) {
                        $affectedgroups[]  = $row['groupid'];
                        $groups           .= $this->groupModel->getNameById($row['groupid']) . ", ";
                      }
                    }
                    if (strlen($groups)) {
                      $groups                 = substr($groups, 0, strlen($groups) - 2);
                      $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_group_threshold'] . $groups;
                      $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_group_threshold'] . $groups;
                      $declinedAbsences[$i]   = $requestedAbsences[$i];
                      $approvedAbsences[$i]   = $currentAbsences[$i];
                      $thresholdReached       = true;
                    }
                  } elseif ($requestedAbsences[$i] && $this->absenceThresholdReached($year, $month, (string) $i, "all")) {
                    $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_total_threshold'];
                    $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_total_threshold'];
                    $declinedAbsences[$i]   = $requestedAbsences[$i];
                    $approvedAbsences[$i]   = $currentAbsences[$i];
                    $thresholdReached       = true;
                  }
                }
              }

              if ($this->configModel->read("declBefore")) {
                $today         = date('Ymd');
                $declStartdate = str_replace('-', '', $this->configModel->read('declBeforeStartdate'));
                $declEnddate   = str_replace('-', '', $this->configModel->read('declBeforeEnddate'));
                $applyRule     = true;
                switch ($this->configModel->read('declBeforePeriod')) {
                  case 'nowEnddate':
                    if ($today > $declEnddate)
                      $applyRule = false;
                    break;
                  case 'startdateForever':
                    if ($today < $declStartdate)
                      $applyRule = false;
                    break;
                  case 'startdateEnddate':
                    if ($today < $declStartdate || $today > $declEnddate)
                      $applyRule = false;
                    break;
                }

                if ($applyRule) {
                  $beforeDate = $this->configModel->read("declBeforeDate");
                  if (!strlen($beforeDate))
                    $beforeDate = getISOToday();
                  if ($requestedDate < $beforeDate) {
                    $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_before_date'] . $beforeDate;
                    $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_before_date'] . $beforeDate;
                    $declinedAbsences[$i]   = $requestedAbsences[$i];
                    $approvedAbsences[$i]   = $currentAbsences[$i];
                    $thresholdReached       = true;
                  }
                }
              }

              $periods = 3;
              for ($p = 1; $p <= $periods; $p++) {
                if ($this->configModel->read("declPeriod" . $p)) {
                  $today         = date('Ymd');
                  $declStartdate = str_replace('-', '', $this->configModel->read('declPeriod' . $p . 'Startdate'));
                  $declEnddate   = str_replace('-', '', $this->configModel->read('declPeriod' . $p . 'Enddate'));
                  $applyRule     = true;
                  switch ($this->configModel->read('declPeriod' . $p . 'Period')) {
                    case 'nowEnddate':
                      if ($today > $declEnddate)
                        $applyRule = false;
                      break;
                    case 'startdateForever':
                      if ($today < $declStartdate)
                        $applyRule = false;
                      break;
                    case 'startdateEnddate':
                      if ($today < $declStartdate || $today > $declEnddate)
                        $applyRule = false;
                      break;
                  }

                  if ($applyRule) {
                    $startDate = $this->configModel->read("declPeriod" . $p . "Start");
                    $endDate   = $this->configModel->read("declPeriod" . $p . "End");
                    if ($requestedDate >= $startDate && $requestedDate <= $endDate) {
                      $declMessage = $this->configModel->read("declPeriod" . $p . "Message");
                      if (!strlen($declMessage)) {
                        $declMessage = $this->LANG['alert_decl_period'] . $startDate . " - " . $endDate;
                      }
                      $declReasons[$i]      = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $declMessage;
                      $declReasonsLog[$i]   = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . $declMessage;
                      $declinedAbsences[$i] = $requestedAbsences[$i];
                      $approvedAbsences[$i] = $currentAbsences[$i];
                      $thresholdReached     = true;
                    }
                  }
                }
              }
            }

            if (
              $this->absenceModel->getApprovalRequired($requestedAbsences[$i]) && !$thresholdReached &&
              !isAllowed("calendareditgroup")
            ) {
              $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->absenceModel->getName($requestedAbsences[$i]) . "): " . $this->LANG['alert_decl_approval_required'];
              $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . $this->LANG['approval_required'];
              $approvalDays[]         = $year . "-" . $month . "-" . sprintf("%02d", ($i)) . " (" . $this->absenceModel->getName($requestedAbsences[$i]) . ")";
              $declinedAbsences[$i]   = $requestedAbsences[$i];
              $approvedAbsences[$i]   = $requestedAbsences[$i];
              $this->daynoteModel->yyyymmdd      = $year . $month . sprintf("%02d", ($i));
              $this->daynoteModel->username      = $username;
              $this->daynoteModel->region        = $regionId;
              $this->daynoteModel->daynote       = $this->LANG['alert_decl_approval_required_daynote'];
              $this->daynoteModel->color         = 'warning';
              $this->daynoteModel->confidential  = '0';
              $this->daynoteModel->create();
            }

            $isHoliday = $holidayMap[$i] ?? 1;
            if ($isHoliday !== 1 && $this->holidayModel->noAbsenceAllowed((string) $isHoliday)) {
              $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->absenceModel->getName($requestedAbsences[$i]) . "): " . $this->LANG['alert_decl_holiday_noabsence'];
              $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_holiday_noabsence'];
              $declinedAbsences[$i]   = $requestedAbsences[$i];
              $approvedAbsences[$i]   = $currentAbsences[$i];
            }
          }
        } else {
          $approvedAbsences[$i] = $currentAbsences[$i];
        }
      }

      if (!empty($approvalDays)) {
        $this->sendAbsenceApprovalNotifications($username, $approvalDays, $errorMessage);
      }

      for ($i = 1; $i <= $monthInfo['daysInMonth']; $i++) {
        if ($allow = $this->absenceModel->getAllowWeek($requestedAbsences[$i])) {
          $firstDayOfWeek = $this->configModel->read("firstDayOfWeek");
          $date           = new DateTime($year . '-' . $month . '-' . sprintf("%02d", ($i)));
          if ($firstDayOfWeek == 1)
            $date->modify('monday this week');
          else
            $date->modify('sunday last week');
          $myts      = $date->getTimestamp();
          $fromyear  = date("Y", $myts);
          $frommonth = date("m", $myts);
          $fromday   = date("d", $myts);
          $countFrom = $fromyear . $frommonth . $fromday;

          $date = new DateTime($year . '-' . $month . '-' . sprintf("%02d", ($i)));
          if ($firstDayOfWeek == 1)
            $date->modify('monday this week +6 days');
          else
            $date->modify('sunday last week +6 days');
          $myts    = $date->getTimestamp();
          $toyear  = date("Y", $myts);
          $tomonth = date("m", $myts);
          $today   = date("d", $myts);
          $countTo = $toyear . $tomonth . $today;

          $taken = $this->countAbsence($username, (string) $requestedAbsences[$i], $countFrom, $countTo, true, false);
          if ((($taken + 1) > $allow && $requestedAbsences[$i] != $currentAbsences[$i]) || $this->countAbsenceRequestedWeek($requestedAbsences, (string) $requestedAbsences[$i], intval($fromday)) > $allow) {
            $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->absenceModel->getName($requestedAbsences[$i]) . "): " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowweek_reached']);
            $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowweek_reached']);
            $declinedAbsences[$i]   = $requestedAbsences[$i];
            $approvedAbsences[$i]   = $currentAbsences[$i];
          }
        }

        if ($allow = $this->absenceModel->getAllowMonth($requestedAbsences[$i])) {
          $myts        = strtotime($year . '-' . $month . '-01');
          $daysInMonth = date("t", $myts);
          $countFrom   = $year . $month . '01';
          $countTo     = $year . $month . $daysInMonth;
          $taken       = $this->countAbsence($username, (string) $requestedAbsences[$i], $countFrom, $countTo, true, false);

          if ((($taken + 1) > $allow && $requestedAbsences[$i] != $currentAbsences[$i]) || $this->countAbsenceRequestedMonth($requestedAbsences, (string) $requestedAbsences[$i]) > $allow) {
            $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->absenceModel->getName($requestedAbsences[$i]) . "): " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowmonth_reached']);
            $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowmonth_reached']);
            $declinedAbsences[$i]   = $requestedAbsences[$i];
            $approvedAbsences[$i]   = $currentAbsences[$i];
          }
        }

        if ($this->allowanceModel->find($username, $requestedAbsences[$i]) && $this->allowanceModel->getAllowance($username, $requestedAbsences[$i])) {
          $allow          = $this->allowanceModel->getAllowance($username, $requestedAbsences[$i]);
          $checkAllowance = true;
        } elseif ($this->absenceModel->getAllowance($requestedAbsences[$i])) {
          $allow          = $this->absenceModel->getAllowance($requestedAbsences[$i]);
          $checkAllowance = true;
        } else {
          $checkAllowance = false;
        }

        if ($checkAllowance) {
          $countFrom = $year . '0101';
          $countTo   = $year . '1231';
          $taken     = $this->countAbsence($username, (string) $requestedAbsences[$i], $countFrom, $countTo, true, false);

          if ((($taken + 1) > $allow && $requestedAbsences[$i] != $currentAbsences[$i]) || $this->countAbsenceRequestedMonth($requestedAbsences, (string) $requestedAbsences[$i]) > $allow) {
            $declinedReasons[$i]    = "<strong>" . $year . "-" . $month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->absenceModel->getName($requestedAbsences[$i]) . "): " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowyear_reached']);
            $declinedReasonsLog[$i] = "- " . $year . $month . sprintf("%02d", ($i)) . ": " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowyear_reached']);
            $declinedAbsences[$i]   = $requestedAbsences[$i];
            $approvedAbsences[$i]   = $currentAbsences[$i];
          }
        }
      }

      $approved = false;
      $declined = false;
      for ($i = 1; $i <= $monthInfo['daysInMonth']; $i++) {
        if (($approvedAbsences[$i] != $requestedAbsences[$i]) || $declinedAbsences[$i] != '0') {
          $declined = true;
        } else {
          $approved = true;
        }

        if ($approved && !$declined) {
          $approvalResult['approvalResult'] = 'all';
        } elseif ($approved) {
          $approvalResult['approvalResult'] = 'partial';
        } elseif ($declined) {
          $approvalResult['approvalResult'] = 'none';
        }
      }
    }

    $approvalResult['approvedAbsences']   = $approvedAbsences;
    $approvalResult['declinedAbsences']   = $declinedAbsences;
    $approvalResult['declinedReasons']    = $declinedReasons;
    $approvalResult['declinedReasonsLog'] = $declinedReasonsLog;

    return $approvalResult;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks wether the maximum absence threshold is reached for weekdays.
   *
   * @param string $year Year
   * @param string $month Month
   * @param string $day Day
   * @param string|int $group Group ID (optional)
   *
   * @return bool True if maximum reached, false otherwise
   */
  public function absenceMaximumReached(string $year, string $month, string $day, string|int $group = ''): bool {
    $absences = 0;
    $members  = $this->userGroupModel->getAllForGroup((string) $group);
    $ymd      = $year . $month . sprintf('%02d', (int) $day);
    foreach ($members as $member) {
      $abss      = $this->absenceDayModel->countAllAbsences($member['username'], $ymd, $ymd);
      $absences += $abss;
    }
    $absences++;
    $threshold = $this->groupModel->getMaxAbsent((string) $group);
    return $absences > $threshold;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks wether the maximum absence threshold is reached for weekends.
   *
   * @param string $year Year
   * @param string $month Month
   * @param string $day Day
   * @param string|int $group Group ID (optional)
   *
   * @return bool True if maximum reached, false otherwise
   */
  public function absenceMaximumWeReached(string $year, string $month, string $day, string|int $group = ''): bool {
    $absences = 0;
    $members  = $this->userGroupModel->getAllForGroup((string) $group);
    $ymd      = $year . $month . sprintf('%02d', (int) $day);
    foreach ($members as $member) {
      $abss      = $this->absenceDayModel->countAllAbsencesWe($member['username'], $ymd, $ymd);
      $absences += $abss;
    }
    $absences++;
    $threshold = $this->groupModel->getMaxAbsentWe((string) $group);
    return $absences > $threshold;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks wether the minimum presence threshold is reached.
   *
   * @param string $year Year
   * @param string $month Month
   * @param string $day Day
   * @param string|int $group Group ID (optional)
   *
   * @return bool True if minimum reached, false otherwise
   */
  public function presenceMinimumReached(string $year, string $month, string $day, string|int $group = ''): bool {
    $usercount = $this->userGroupModel->countMembers((string) $group);
    $absences  = 0;
    $members   = $this->userGroupModel->getAllForGroup((string) $group);
    $ymd       = $year . $month . sprintf('%02d', (int) $day);
    foreach ($members as $member) {
      $abss      = $this->absenceDayModel->countAllAbsences($member['username'], $ymd, $ymd);
      $absences += $abss;
    }
    $absences++;
    $presences = $usercount - $absences;
    $threshold = $this->groupModel->getMinPresent((string) $group);
    return $presences < $threshold;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether the minimum presence threshold is reached.
   *
   * @param string $year Year
   * @param string $month Month
   * @param string $day Day
   * @param string|int $group Group ID (optional)
   *
   * @return bool True if minimum reached, false otherwise
   */
  public function presenceMinimumWeReached(string $year, string $month, string $day, string|int $group = ''): bool {
    $usercount = $this->userGroupModel->countMembers((string) $group);
    $absences  = 0;
    $members   = $this->userGroupModel->getAllForGroup((string) $group);
    $ymd       = $year . $month . sprintf('%02d', (int) $day);
    foreach ($members as $member) {
      $abss      = $this->absenceDayModel->countAllAbsencesWe($member['username'], $ymd, $ymd);
      $absences += $abss;
    }
    $absences++;
    $presences = $usercount - $absences;
    $threshold = $this->groupModel->getMinPresentWe((string) $group);
    return $presences < $threshold;
  }

  //---------------------------------------------------------------------------
  /**
   * Sends an email to all users that subscribed to a user calendar change event.
   *
   * @param string   $username     Username
   * @param string[] $absences     Array of absences
   * @param string   $errorMessage Error message reference
   *
   * @return void
   */
  public function sendAbsenceApprovalNotifications(string $username, array $absences, string &$errorMessage = ''): void {
    $language = $this->configModel->read('defaultLanguage');
    $appTitle = $this->configModel->read('appTitle');
    $appURL   = $this->configModel->read('appURL');
    $absList  = "<ul>";
    foreach ($absences as $abs) {
      $absList .= "<li>" . $abs . "</li>";
    }
    $absList .= "</ul>";

    $subject = str_replace('%app_name%', $appTitle, $this->LANG['email_subject_absence_approval']);
    $message = file_get_contents(WEBSITE_ROOT . '/resources/templates/email_html.html');
    $intro   = file_get_contents(WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html');
    $body    = file_get_contents(WEBSITE_ROOT . '/resources/templates/' . $language . '/body_absence_approval.html');
    $outro   = file_get_contents(WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html');

    $message = str_replace('%intro%', $intro, $message);
    $message = str_replace('%body%', $body, $message);
    $message = str_replace('%outro%', $outro, $message);
    $message = str_replace('%app_name%', $appTitle, $message);
    $message = str_replace('%app_url%', $appURL, $message);
    $message = str_replace('%fullname%', $this->userModel->getFullname($username), $message);
    $message = str_replace('%username%', $username, $message);
    $message = str_replace('%absences%', $absList, $message);

    $users = $this->userModel->getAll('lastname', 'firstname', 'ASC', false, true);
    foreach ($users as $profile) {
      if ($this->userGroupModel->isGroupManagerOfUser($profile['username'], $username)) {
        $mailError = '';
        if (!sendEmail($profile['email'], $subject, $message, '', $mailError)) {
          if (!empty($errorMessage)) {
            $errorMessage .= '<br>';
          }
          $errorMessage .= $mailError;
        }
      }
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets absence summary for a given user, absence type and month.
   *
   * @param string|int $absid    Absence ID
   * @param string     $year     Year
   *
   * @return array<string, mixed> Summary array with allowance, carryover, taken, etc.
   */
  public function getAbsenceSummary(string $username, string|int $absid, string $year): array {
    $summary = array(
      'allowance'      => 0,
      'carryover'      => 0,
      'totalallowance' => 0,
      'taken'          => 0,
      'remainder'      => 0
    );

    if ($this->absenceModel->get($absid)) {
      if ($this->allowanceModel->find($username, (string) $this->absenceModel->id)) {
        $summary['carryover'] = $this->allowanceModel->carryover;
        $summary['allowance'] = $this->allowanceModel->allowance;
      } else {
        $summary['carryover'] = 0;
        $summary['allowance'] = $this->absenceModel->allowance;
      }
      $summary['totalallowance']  = $summary['allowance'] + $summary['carryover'];
      $summary['taken']           = 0;
      if (!$this->absenceModel->counts_as_present) {
        $countFrom = $year . '01' . '01';
        $countTo   = $year . '12' . '31';
        $summary['taken'] += $this->countAbsence($username, (string) $this->absenceModel->id, $countFrom, $countTo, true, false);
        if ($countsAsArray = $this->absenceModel->getAllSub($absid)) {
          foreach ($countsAsArray as $countsAs) {
            // getAllSub() returns full rows, so no per-item AbsenceModel load is needed
            if (!$countsAs['counts_as_present']) {
              $summary['taken'] += $this->countAbsence($username, (string) $countsAs['id'], $countFrom, $countTo, true, false);
            }
          }
        }
      }
      if ($this->absenceModel->allowance || $summary['totalallowance']) {
        $summary['remainder'] = $summary['totalallowance'] - $summary['taken'];
      } else {
        $summary['remainder'] = $this->LANG['absum_unlimited'];
      }
    }
    return $summary;
  }

  //---------------------------------------------------------------------------
  /**
   * Counts all occurences of a given absence type for a given user in a given
   * time period.
   *
   * @param string $user User to count for
   * @param string|int $absid Absence type ID to count
   * @param string $from Date to count from (including)
   * @param string $to Date to count to (including)
   * @param boolean $useFactor Multiply count by factor
   * @param boolean $combined Count other absences that count as this one
   *
   * @return int Result of the count
   */
  public function countAbsence(string $user = '%', string|int $absid = '', string $from = '', string $to = '', bool $useFactor = false, bool $combined = false): int {
    // tcneo_absence_days stores one row per real day, so a range query
    // spans the whole period directly - no need to split by month the way
    // the old month-row-per-user tcneo_templates table required.
    $count = $this->absenceDayModel->countAbsence($user, $absid, $from, $to);

    if ($useFactor) {
      $count *= $this->absenceModel->getFactor((string) $absid);
    }

    //
    // If requested, count all those absence types that count as this one
    //
    $otherTotal = 0;
    if ($combined) {
      foreach ($this->absenceModel->getAll() as $otherAbs) {
        if (($otherId = $otherAbs['counts_as']) && $otherId == $absid) {
          $otherCount  = $this->absenceDayModel->countAbsence($user, (string) $otherAbs['id'], $from, $to);
          $otherFactor = $otherAbs['factor'];
          //
          // A combined count always uses the factor. Doesn't make sense otherwise.
          //
          $otherTotal += $otherCount * $otherFactor;
        }
      }
    }
    $count += $otherTotal;
    return (int) $count;
  }

  //---------------------------------------------------------------------------
  /**
   * Counts all business days or man days in a given time period.
   *
   * @param string $cntfrom Date to count from (including)
   * @param string $cntto Date to count to (including)
   * @param string $region Region to count for
   * @param boolean $cntManDays Switch whether to multiply the business days by the amount of users and return that value instead
   *
   * @return int Result of the count
   */
  public function countBusinessDays(string $cntfrom, string $cntto, string $region = '1', bool $cntManDays = false): int {
    $startyear      = intval(substr($cntfrom, 0, 4));
    $startmonth     = intval(substr($cntfrom, 4, 2));
    $startday       = intval(substr($cntfrom, 6, 2));
    $endday         = intval(substr($cntto, 6, 2));
    $startyearmonth = intval(substr($cntfrom, 0, 6));
    $endyearmonth   = intval(substr($cntto, 0, 6));

    $count    = 0;
    $year     = $startyear;
    $month    = $startmonth;
    $firstday = $startday;
    if ($firstday < 1 || $firstday > 31) {
      $firstday = 1;
    }

    $yearmonth = $startyearmonth;
    while ($yearmonth <= $endyearmonth) {
      $holidayMap = $this->calendarDayModel->getMonthMap((string) $year, sprintf("%02d", $month), $region);
      $monthInfo  = dateInfo((string) $year, sprintf("%02d", $month), '1');
      $lastday    = $monthInfo['daysInMonth'];
      if ($yearmonth == $endyearmonth && $endday < $monthInfo['daysInMonth']) {
        //
        // This is the last month. Make sure we just read it up to the specified endday.
        //
        $lastday = $endday;
      }
      //
      // Now loop through each day of the month
      //
      for ($i = $firstday; $i <= $lastday; $i++) {
        $weekday = (int) date('N', mktime(0, 0, 0, $month, $i, $year));
        $holiday = $holidayMap[$i] ?? 1;
        if ($weekday < 6) {
          //
          // This is a weekday. Check if Holiday before counting it.
          //
          if ($holiday !== 1) {
            //
            // This is a weekday but a Holiday. Only count this if this Holiday counts as business day.
            //
            if ($this->holidayModel->isBusinessDay((string) $holiday)) {
              $count++;
            }
          } else {
            $count++;
          }
        } elseif ($weekday == 6) {
          //
          // This is a Saturday. Check if counts as business day.
          //
          if ($this->holidayModel->isBusinessDay('2')) {
            $count++;
          }
        } elseif ($weekday == 7) {
          //
          // This is a Sunday. Check if counts as business day.
          //
          if ($this->holidayModel->isBusinessDay('3')) {
            $count++;
          }
        }
      }
      //
      // Now set the next month for the loop
      //
      if (intval(substr((string) $yearmonth, 4, 2)) == 12) {
        $year = intval(substr((string) $yearmonth, 0, 4));
        $year++;
        $yearmonth = strval($year) . "01";
      } else {
        $year  = intval(substr((string) $yearmonth, 0, 4));
        $month = intval(substr((string) $yearmonth, 4, 2));
        $month++;
        $yearmonth = strval($year) . sprintf("%02d", strval($month));
      }
      $firstday = 1;
    }

    if ($cntManDays) {
      //
      // Now we have the amount of business days in this period.
      // In order to get the remaining man days we need to multiply that amount
      // with all users (not hidden and not admin).
      //
      return $count * $this->userModel->countUsers();
    } else {
      return $count;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Counts all requested absences for a month.
   *
   * @param array<int, string> $requestedAbsences Requested absences array
   * @param string|int        $absence           Absence ID
   *
   * @return int Count of requested absences
   */
  private function countAbsenceRequestedMonth(array $requestedAbsences, string|int $absence): int {
    $count = 0;
    foreach ($requestedAbsences as $abs) {
      if ((string) $abs === (string) $absence) {
        $count++;
      }
    }
    return $count;
  }

  //---------------------------------------------------------------------------
  /**
   * Counts all requested absences for a week (7 sequential values in the array).
   *
   * @param array<int, string> $requestedAbsences Requested absences array
   * @param string|int        $absence           Absence ID
   * @param int               $startAt           Starting index in the array
   *
   * @return int Count of requested absences
   */
  private function countAbsenceRequestedWeek(array $requestedAbsences, string|int $absence, int $startAt): int {
    $count = 0;
    for ($i = $startAt; $i <= $startAt + 6; $i++) {
      if (isset($requestedAbsences[$i]) && (string) $requestedAbsences[$i] === (string) $absence) {
        $count++;
      }
    }
    return $count;
  }
}
