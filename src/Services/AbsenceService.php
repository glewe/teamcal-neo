<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\AbsenceModel;
use App\Models\AllowanceModel;
use App\Models\ConfigModel;
use App\Models\DaynoteModel;
use App\Models\GroupModel;
use App\Models\HolidayModel;
use App\Models\MonthModel;
use App\Models\TemplateModel;
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
class AbsenceService
{
  private AbsenceModel   $A;
  private AllowanceModel $AL;
  private ConfigModel    $C;
  private DaynoteModel   $D;
  private GroupModel     $G;
  private HolidayModel   $H;
  private MonthModel     $M;
  private TemplateModel  $T;
  private UserGroupModel $UG;
  private UserModel      $U;
  private UserModel      $UL;
  private array          $LANG;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param AbsenceModel $A Absence model
   * @param AllowanceModel $AL Allowance model
   * @param ConfigModel $C Config model
   * @param DaynoteModel $D Daynote model
   * @param GroupModel $G Group model
   * @param HolidayModel $H Holiday model
   * @param MonthModel $M Month model
   * @param TemplateModel $T Template model
   * @param UserGroupModel $UG User group model
   * @param UserModel $U User model
   * @param UserModel $UL Logged in user model
   * @param array $LANG Language array
   */
  public function __construct(
    AbsenceModel $A,
    AllowanceModel $AL,
    ConfigModel $C,
    DaynoteModel $D,
    GroupModel $G,
    HolidayModel $H,
    MonthModel $M,
    TemplateModel $T,
    UserGroupModel $UG,
    UserModel $U,
    UserModel $UL,
    array $LANG
  ) {
    $this->A    = $A;
    $this->AL   = $AL;
    $this->C    = $C;
    $this->D    = $D;
    $this->G    = $G;
    $this->H    = $H;
    $this->M    = $M;
    $this->T    = $T;
    $this->UG   = $UG;
    $this->U    = $U;
    $this->UL   = $UL;
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
        $members   = $this->UG->getAllForGroup((string) $group);
        $usercount = $this->UG->countMembers((string) $group);
        if ($usercount === 0)
          return false;

        $absences = 0;
        foreach ($members as $member) {
          $absences += $this->T->countAllAbsences($member['username'], $year, $month, (int) $day, (int) $day);
        }
      }
      else {
        $usercount = $this->U->countUsers();
        if ($usercount === 0)
          return false;
        $absences = $this->T->countAllAbsences('%', $year, $month, (int) $day);
      }

      $absences++;
      $absencerate = (100 * $absences) / $usercount;
      $threshold   = (int) $this->C->read("declThreshold");

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
   * @param string $username Username
   * @param string $year Year
   * @param string $month Month
   * @param array $currentAbsences Current absences array
   * @param array $requestedAbsences Requested absences array
   * @param string $regionId Region ID
   *
   * @return array Approval result with approved and declined absences
   */
  public function approveAbsences(string $username, string $year, string $month, array $currentAbsences, array $requestedAbsences, string $regionId): array {
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
    $userGroups = $this->UG->getAllforUser((string) $username);

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

    if (($this->UL->username == 'admin' || isAllowed("calendareditall")) && !$takeoverRequested) {
      $approvalResult['approvalResult'] = 'all';
      return $approvalResult;
    }

    if ($arraysDiffer) {
      for ($i = 1; $i <= $monthInfo['daysInMonth']; $i++) {
        if ($currentAbsences[$i] != $requestedAbsences[$i]) {
          $requestedDate        = $year . '-' . $month . '-' . sprintf("%02d", ($i));
          $approvedAbsences[$i] = $requestedAbsences[$i];

          if ($requestedAbsences[$i] == 'takeover') {
            if ($this->A->isTakeover($currentAbsences[$i])) {
              $requestedAbsences[$i] = '0';
              $approvedAbsences[$i]  = '0';
              $this->T->setAbsence($username, $year, $month, (string) $i, '0');
              $this->T->setAbsence($this->UL->username, $year, $month, (string) $i, $currentAbsences[$i]);
            }
            else {
              $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . sprintf($this->LANG['alert_decl_takeover'], $this->A->getName($currentAbsences[$i]));
              $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . sprintf($this->LANG['alert_decl_takeover'], $this->A->getName($currentAbsences[$i]));
              $declinedAbsences[$i]   = $currentAbsences[$i];
              $approvedAbsences[$i]   = $currentAbsences[$i];
            }
          }
          else {
            $declScopeRoles = array();
            $declInScope    = true;
            if ($declScope = $this->C->read("declScope")) {
              $declScopeRoles = explode(',', $declScope);
              $ulRole         = $this->UL->getRole($this->UL->username);
              if (!in_array($ulRole, $declScopeRoles)) {
                $declInScope = false;
              }
            }

            if ($declInScope) {
              $groups = "";
              foreach ($userGroups as $row) {
                if (
                  $requestedAbsences[$i] && !$this->A->getCountsAsPresent($requestedAbsences[$i]) && ($this->presenceMinimumReached($year, $month, (string) $i, (string) $row['groupid']) || $this->presenceMinimumWeReached($year, $month, (string) $i, (string) $row['groupid'])) &&
                  (!isAllowed("calendareditgroup") || (!$this->UG->isGroupManagerOfGroup($this->UL->username, (string) $row['id']) && !$this->UG->isMemberOrManagerOfGroup($this->UL->username, (string) $row['groupid'])))
                ) {
                  $affectedgroups[] = $row['groupid'];
                  $minimum          = ''; // Initialize to prevent undefined variable
                  if ($this->presenceMinimumReached($year, $month, (string) $i, (string) $row['groupid'])) {
                    $minimum = $this->LANG['weekdays'] . ": " . $this->G->getMinPresent($row['groupid']);
                  }
                  elseif ($this->presenceMinimumWeReached($year, $month, (string) $i, (string) $row['groupid'])) {
                    $minimum = $this->LANG['weekends'] . ": " . $this->G->getMinPresentWe($row['groupid']);
                  }
                  $groups .= $this->G->getNameById($row['groupid']) . " (" . $minimum . "), ";
                }
              }

              if (strlen($groups)) {
                $groups                 = substr($groups, 0, strlen($groups) - 2);
                $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_group_minpresent'] . $groups;
                $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_group_minpresent'] . $groups;
                $declinedAbsences[$i]   = $requestedAbsences[$i];
                $approvedAbsences[$i]   = $currentAbsences[$i];
                $thresholdReached       = true;
              }

              $groups = "";
              foreach ($userGroups as $row) {
                if (
                  $requestedAbsences[$i] && !$this->A->getCountsAsPresent($requestedAbsences[$i]) && ($this->absenceMaximumReached($year, $month, (string) $i, $row['groupid']) || $this->absenceMaximumWeReached($year, $month, (string) $i, $row['groupid'])) &&
                  (!isAllowed("calendareditgroup") || (!$this->UG->isGroupManagerOfGroup($this->UL->username, (string) $row['id']) && !$this->UG->isMemberOrManagerOfGroup($this->UL->username, (string) $row['groupid'])))
                ) {
                  $affectedgroups[] = $row['groupid'];
                  $maximum          = ''; // Initialize to prevent undefined variable
                  if ($this->absenceMaximumReached($year, $month, (string) $i, $row['groupid'])) {
                    $maximum = $this->LANG['weekdays'] . ": " . $this->G->getMaxAbsent($row['groupid']);
                  }
                  elseif ($this->absenceMaximumWeReached($year, $month, (string) $i, $row['groupid'])) {
                    $maximum = $this->LANG['weekends'] . ": " . $this->G->getMaxAbsentWe($row['groupid']);
                  }
                  $groups .= $this->G->getNameById($row['groupid']) . " (" . $maximum . "), ";
                }
              }

              if (strlen($groups)) {
                $groups                 = substr($groups, 0, strlen($groups) - 2);
                $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_group_maxabsent'] . $groups;
                $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_group_maxabsent'] . $groups;
                $declinedAbsences[$i]   = $requestedAbsences[$i];
                $approvedAbsences[$i]   = $currentAbsences[$i];
                $thresholdReached       = true;
              }

              if ($this->C->read("declAbsence") && $requestedAbsences[$i] != '0' && !$this->A->getCountsAsPresent($requestedAbsences[$i])) {
                $today         = date('Ymd');
                $declStartdate = str_replace('-', '', $this->C->read('declAbsenceStartdate'));
                $declEnddate   = str_replace('-', '', $this->C->read('declAbsenceEnddate'));
                $applyRule     = true;
                switch ($this->C->read('declAbsencePeriod')) {
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
                  if ($this->C->read("declBase") == "group") {
                    $groups = "";
                    foreach ($userGroups as $row) {
                      if (
                        $requestedAbsences[$i] && $this->absenceThresholdReached($year, $month, (string) $i, "group", (string) $row['groupid']) &&
                        (!isAllowed("calendareditgroup") || (!$this->UG->isGroupManagerOfGroup($this->UL->username, (string) $row['id']) && !$this->UG->isMemberOrManagerOfGroup($this->UL->username, (string) $row['groupid'])))
                      ) {
                        $affectedgroups[]  = $row['groupid'];
                        $groups           .= $this->G->getNameById($row['groupid']) . ", ";
                      }
                    }
                    if (strlen($groups)) {
                      $groups                 = substr($groups, 0, strlen($groups) - 2);
                      $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_group_threshold'] . $groups;
                      $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_group_threshold'] . $groups;
                      $declinedAbsences[$i]   = $requestedAbsences[$i];
                      $approvedAbsences[$i]   = $currentAbsences[$i];
                      $thresholdReached       = true;
                    }
                  }
                  elseif ($requestedAbsences[$i] && $this->absenceThresholdReached($year, $month, (string) $i, "all")) {
                    $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_total_threshold'];
                    $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_total_threshold'];
                    $declinedAbsences[$i]   = $requestedAbsences[$i];
                    $approvedAbsences[$i]   = $currentAbsences[$i];
                    $thresholdReached       = true;
                  }
                }
              }

              if ($this->C->read("declBefore")) {
                $today         = date('Ymd');
                $declStartdate = str_replace('-', '', $this->C->read('declBeforeStartdate'));
                $declEnddate   = str_replace('-', '', $this->C->read('declBeforeEnddate'));
                $applyRule     = true;
                switch ($this->C->read('declBeforePeriod')) {
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
                  $beforeDate = $this->C->read("declBeforeDate");
                  if (!strlen($beforeDate))
                    $beforeDate = getISOToday();
                  if ($requestedDate < $beforeDate) {
                    $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $this->LANG['alert_decl_before_date'] . $beforeDate;
                    $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_before_date'] . $beforeDate;
                    $declinedAbsences[$i]   = $requestedAbsences[$i];
                    $approvedAbsences[$i]   = $currentAbsences[$i];
                    $thresholdReached       = true;
                  }
                }
              }

              $periods = 3;
              for ($p = 1; $p <= $periods; $p++) {
                if ($this->C->read("declPeriod" . $p)) {
                  $today         = date('Ymd');
                  $declStartdate = str_replace('-', '', $this->C->read('declPeriod' . $p . 'Startdate'));
                  $declEnddate   = str_replace('-', '', $this->C->read('declPeriod' . $p . 'Enddate'));
                  $applyRule     = true;
                  switch ($this->C->read('declPeriod' . $p . 'Period')) {
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
                    $startDate = $this->C->read("declPeriod" . $p . "Start");
                    $endDate   = $this->C->read("declPeriod" . $p . "End");
                    if ($requestedDate >= $startDate && $requestedDate <= $endDate) {
                      $declMessage = $this->C->read("declPeriod" . $p . "Message");
                      if (!strlen($declMessage)) {
                        $declMessage = $this->LANG['alert_decl_period'] . $startDate . " - " . $endDate;
                      }
                      $declReasons[$i]      = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong>: " . $declMessage;
                      $declReasonsLog[$i]   = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . $declMessage;
                      $declinedAbsences[$i] = $requestedAbsences[$i];
                      $approvedAbsences[$i] = $currentAbsences[$i];
                      $thresholdReached     = true;
                    }
                  }
                }
              }
            }

            if (
              $this->A->getApprovalRequired($requestedAbsences[$i]) && !$thresholdReached &&
              !isAllowed("calendareditgroup")
            ) {
              $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->A->getName($requestedAbsences[$i]) . "): " . $this->LANG['alert_decl_approval_required'];
              $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . $this->LANG['approval_required'];
              $approvalDays[]         = $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . " (" . $this->A->getName($requestedAbsences[$i]) . ")";
              $declinedAbsences[$i]   = $requestedAbsences[$i];
              $approvedAbsences[$i]   = $requestedAbsences[$i];
              $this->D->yyyymmdd      = $this->T->year . $this->T->month . sprintf("%02d", ($i));
              $this->D->username      = $username;
              $this->D->region        = $regionId;
              $this->D->daynote       = $this->LANG['alert_decl_approval_required_daynote'];
              $this->D->color         = 'warning';
              $this->D->confidential  = '0';
              $this->D->create();
            }

            $isHoliday = $this->M->getHoliday($year, $month, (string) $i, $regionId);
            if ($isHoliday && $this->H->noAbsenceAllowed((string) $isHoliday)) {
              $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->A->getName($requestedAbsences[$i]) . "): " . $this->LANG['alert_decl_holiday_noabsence'];
              $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . $this->LANG['alert_decl_holiday_noabsence'];
              $declinedAbsences[$i]   = $requestedAbsences[$i];
              $approvedAbsences[$i]   = $currentAbsences[$i];
            }
          }
        }
        else {
          $approvedAbsences[$i] = $currentAbsences[$i];
        }
      }

      if (!empty($approvalDays)) {
        $this->sendAbsenceApprovalNotifications($username, $approvalDays);
      }

      for ($i = 1; $i <= $monthInfo['daysInMonth']; $i++) {
        if ($allow = $this->A->getAllowWeek($requestedAbsences[$i])) {
          $firstDayOfWeek = $this->C->read("firstDayOfWeek");
          $date           = new DateTime($this->T->year . '-' . $this->T->month . '-' . sprintf("%02d", ($i)));
          if ($firstDayOfWeek == 1)
            $date->modify('monday this week');
          else
            $date->modify('sunday last week');
          $myts      = $date->getTimestamp();
          $fromyear  = date("Y", $myts);
          $frommonth = date("m", $myts);
          $fromday   = date("d", $myts);
          $countFrom = $fromyear . $frommonth . $fromday;

          $date = new DateTime($this->T->year . '-' . $this->T->month . '-' . sprintf("%02d", ($i)));
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
            $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->A->getName($requestedAbsences[$i]) . "): " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowweek_reached']);
            $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowweek_reached']);
            $declinedAbsences[$i]   = $requestedAbsences[$i];
            $approvedAbsences[$i]   = $currentAbsences[$i];
          }
        }

        if ($allow = $this->A->getAllowMonth($requestedAbsences[$i])) {
          $myts        = strtotime($this->T->year . '-' . $this->T->month . '-01');
          $daysInMonth = date("t", $myts);
          $countFrom   = $this->T->year . $this->T->month . '01';
          $countTo     = $this->T->year . $this->T->month . $daysInMonth;
          $taken       = $this->countAbsence($username, (string) $requestedAbsences[$i], $countFrom, $countTo, true, false);

          if ((($taken + 1) > $allow && $requestedAbsences[$i] != $currentAbsences[$i]) || $this->countAbsenceRequestedMonth($requestedAbsences, (string) $requestedAbsences[$i]) > $allow) {
            $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->A->getName($requestedAbsences[$i]) . "): " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowmonth_reached']);
            $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowmonth_reached']);
            $declinedAbsences[$i]   = $requestedAbsences[$i];
            $approvedAbsences[$i]   = $currentAbsences[$i];
          }
        }

        if ($this->AL->find($username, $requestedAbsences[$i]) && $this->AL->getAllowance($username, $requestedAbsences[$i])) {
          $allow          = $this->AL->getAllowance($username, $requestedAbsences[$i]);
          $checkAllowance = true;
        }
        elseif ($this->A->getAllowance($requestedAbsences[$i])) {
          $allow          = $this->A->getAllowance($requestedAbsences[$i]);
          $checkAllowance = true;
        }
        else {
          $checkAllowance = false;
        }

        if ($checkAllowance) {
          $countFrom = $this->T->year . '0101';
          $countTo   = $this->T->year . '1231';
          $taken     = $this->countAbsence($username, (string) $requestedAbsences[$i], $countFrom, $countTo, true, false);

          if ((($taken + 1) > $allow && $requestedAbsences[$i] != $currentAbsences[$i]) || $this->countAbsenceRequestedMonth($requestedAbsences, (string) $requestedAbsences[$i]) > $allow) {
            $declinedReasons[$i]    = "<strong>" . $this->T->year . "-" . $this->T->month . "-" . sprintf("%02d", ($i)) . "</strong> (" . $this->A->getName($requestedAbsences[$i]) . "): " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowyear_reached']);
            $declinedReasonsLog[$i] = "- " . $this->T->year . $this->T->month . sprintf("%02d", ($i)) . ": " . str_replace('%1%', $allow, $this->LANG['alert_decl_allowyear_reached']);
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
        }
        else {
          $approved = true;
        }

        if ($approved && !$declined) {
          $approvalResult['approvalResult'] = 'all';
        }
        elseif ($approved) {
          $approvalResult['approvalResult'] = 'partial';
        }
        elseif ($declined) {
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
    $members  = $this->UG->getAllForGroup((string) $group);
    foreach ($members as $member) {
      $abss      = $this->T->countAllAbsences($member['username'], $year, $month, (int) $day, (int) $day);
      $absences += $abss;
    }
    $absences++;
    $threshold = $this->G->getMaxAbsent((string) $group);
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
    $members  = $this->UG->getAllForGroup((string) $group);
    foreach ($members as $member) {
      $abss      = $this->T->countAllAbsencesWe($member['username'], $year, $month, (int) $day, (int) $day);
      $absences += $abss;
    }
    $absences++;
    $threshold = $this->G->getMaxAbsentWe((string) $group);
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
    $usercount = $this->UG->countMembers((string) $group);
    $absences  = 0;
    $members   = $this->UG->getAllForGroup((string) $group);
    foreach ($members as $member) {
      $abss      = $this->T->countAllAbsences($member['username'], $year, $month, (int) $day, (int) $day);
      $absences += $abss;
    }
    $absences++;
    $presences = $usercount - $absences;
    $threshold = $this->G->getMinPresent((string) $group);
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
    $usercount = $this->UG->countMembers((string) $group);
    $absences  = 0;
    $members   = $this->UG->getAllForGroup((string) $group);
    foreach ($members as $member) {
      $abss      = $this->T->countAllAbsencesWe($member['username'], $year, $month, (int) $day, (int) $day);
      $absences += $abss;
    }
    $absences++;
    $presences = $usercount - $absences;
    $threshold = $this->G->getMinPresentWe((string) $group);
    return $presences < $threshold;
  }

  //---------------------------------------------------------------------------
  /**
   * Sends an email to all users that subscribed to a user calendar change event.
   *
   * @param string $username Username
   * @param array $absences Array of absences
   *
   * @return void
   */
  public function sendAbsenceApprovalNotifications(string $username, array $absences): void {
    $language = $this->C->read('defaultLanguage');
    $appTitle = $this->C->read('appTitle');
    $appURL   = $this->C->read('appURL');
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
    $message = str_replace('%fullname%', $this->U->getFullname($username), $message);
    $message = str_replace('%username%', $username, $message);
    $message = str_replace('%absences%', $absList, $message);

    $users = $this->U->getAll('lastname', 'firstname', 'ASC', false, true);
    foreach ($users as $profile) {
      if ($this->UG->isGroupManagerOfUser($profile['username'], $username)) {
        sendEmail($profile['email'], $subject, $message);
      }
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets absence summary for a given user, absence type and month.
   *
   * @param string $username Username
   * @param string|int $absid Absence ID
   * @param string $year Year
   *
   * @return array Summary array with allowance, carryover, taken, etc.
   */
  public function getAbsenceSummary(string $username, string|int $absid, string $year): array {
    $summary = array(
      'allowance'      => 0,
      'carryover'      => 0,
      'totalallowance' => 0,
      'taken'          => 0,
      'remainder'      => 0
    );

    if ($this->A->get($absid)) {
      if ($this->AL->find($username, (string) $this->A->id)) {
        $summary['carryover'] = $this->AL->carryover;
        $summary['allowance'] = $this->AL->allowance;
      }
      else {
        $summary['carryover'] = 0;
        $summary['allowance'] = $this->A->allowance;
      }
      $summary['totalallowance'] = $summary['allowance'] + $summary['carryover'];
      $summary['taken']          = 0;
      $countFrom         = $year . '01' . '01';
      $countTo           = $year . '12' . '31';
      $summary['taken'] += $this->countAbsence($username, (string)$this->A->id, $countFrom, $countTo, true, false);
      if ($countsAsArray = $this->A->getAllSub($absid)) {
        foreach ($countsAsArray as $countsAs) {
          $A2 = new AbsenceModel();
          if ($A2->get($countsAs['id'])) {
            $summary['taken'] += $this->countAbsence($username, (string)$A2->id, $countFrom, $countTo, true, false);
          }
        }
      }
      if ($this->A->allowance || $summary['totalallowance']) {
        $summary['remainder'] = $summary['totalallowance'] - $summary['taken'];
      }
      else {
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
    $absences = $this->A->getAll(); // Uses $this->A instead of global $A
    //
    // Figure out starting month and ending month
    //
    $startyear  = intval(substr($from, 0, 4));
    $startmonth = intval(substr($from, 4, 2));
    $startday   = intval(substr($from, 6, 2));
    $endyear    = intval(substr($to, 0, 4));
    $endmonth   = intval(substr($to, 4, 2));
    $endday     = intval(substr($to, 6, 2));
    //
    // Get the count for this absence type
    //
    $factor   = $this->A->getFactor((string) $absid); // Uses $this->A
    $count    = 0;
    $firstday = $startday;
    if ($firstday < 1 || $firstday > 31) {
      $firstday = 1;
    }
    $year    = $startyear;
    $month   = $startmonth;
    $ymstart = intval($year . sprintf("%02d", $month));
    $ymend   = intval($endyear . sprintf("%02d", $endmonth));
    //
    // Loop through every month of the requested period
    //
    while ($ymstart <= $ymend) {
      if ($year == $startyear && $month == $startmonth) {
        $lastday = 0;
        if ($startmonth == $endmonth) {
          //
          // We only have one month. Make sure to only count until the requested end day.
          //
          $lastday = $endday;
        }
        $count += $this->T->countAbsence($user, (string) $year, (string) $month, (string) $absid, (int) $startday, (int) $lastday); // Uses $this->T
      }
      elseif ($year == $endyear && $month == $endmonth) {
        $count += $this->T->countAbsence($user, (string) $year, (string) $month, (string) $absid, 1, (int) $endday);
      }
      else {
        $count += $this->T->countAbsence($user, (string) $year, (string) $month, (string) $absid);
      }

      if ($month == 12) {
        $year++;
        $month = 1;
      }
      else {
        $month++;
      }
      $ymstart = intval($year . sprintf("%02d", $month));
    }

    if ($useFactor) {
      $count *= $factor;
    }

    //
    // If requested, count all those absence types that count as this one
    //
    $otherTotal = 0;
    if ($combined) {
      foreach ($absences as $otherAbs) {
        if (($otherId = $otherAbs['counts_as']) && $otherId == $absid) {
          $otherCount  = 0;
          $otherFactor = $otherAbs['factor'];
          $year        = $startyear;
          $month       = $startmonth;
          $ymstart     = intval($year . sprintf("%02d", $month));
          $ymend       = intval($endyear . sprintf("%02d", $endmonth));
          while ($ymstart <= $ymend) {
            if ($year == $startyear && $month == $startmonth) {
              $otherCount += $this->T->countAbsence($user, (string) $year, (string) $month, (string) $otherAbs['id'], (int) $startday);
            }
            elseif ($year == $endyear && $month == $endmonth) {
              $otherCount += $this->T->countAbsence($user, (string) $year, (string) $month, (string) $otherAbs['id'], 1, (int) $endday);
            }
            else {
              $otherCount += $this->T->countAbsence($user, (string) $year, (string) $month, (string) $otherAbs['id']);
            }
            if ($month == 12) {
              $year++;
              $month = 1;
            }
            else {
              $month++;
            }
            $ymstart = intval($year . sprintf("%02d", $month));
          }
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
      $this->M->getMonth((string) $year, sprintf("%02d", $month), $region);
      $monthInfo = dateInfo((string) $year, sprintf("%02d", $month), '1');
      $lastday   = $monthInfo['daysInMonth'];
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
        $weekday = 'wday' . $i;
        $holiday = 'hol' . $i;
        if ($this->M->$weekday < 6) {
          //
          // This is a weekday. Check if Holiday before counting it.
          //
          if ($this->M->$holiday) {
            //
            // This is a weekday but a Holiday. Only count this if this Holiday counts as business day.
            //
            if ($this->H->isBusinessDay($this->M->$holiday)) {
              $count++;
            }
          }
          else {
            $count++;
          }
        }
        elseif ($this->M->$weekday == 6) {
          //
          // This is a Saturday. Check if counts as business day.
          //
          if ($this->H->isBusinessDay('2')) {
            $count++;
          }
        }
        elseif ($this->M->$weekday == 7) {
          //
          // This is a Sunday. Check if counts as business day.
          //
          if ($this->H->isBusinessDay('3')) {
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
      }
      else {
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
      return $count * $this->U->countUsers();
    }
    else {
      return $count;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Counts all requested absences for a month.
   *
   * @param array $requestedAbsences Requested absences array
   * @param string|int $absence Absence ID
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
   * @param array $requestedAbsences Requested absences array
   * @param string|int $absence Absence ID
   * @param int $startAt Starting index in the array
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
