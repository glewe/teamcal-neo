<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Calendar Helper Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//-----------------------------------------------------------------------------
/**
 * Creates an empty month template marking Saturdays and Sundays as weekend.
 *
 * @param string $year Four character string representing the year
 * @param string $month Two character string representing the month
 * @param string $target Month template (month) or user template (user)
 * @param string $owner Template owner. Either region name for month template or user ID for user template
 *
 * @return bool Success code
 */
function createMonth(string $year, string $month, string $target, string $owner): bool {
  $dateInfo   = dateInfo($year, $month, '1');
  $dayofweek  = $dateInfo['wday'];
  $weeknumber = $dateInfo['week'];

  if ($target == 'region') {
    $M         = new App\Models\MonthModel();
    $M->region = $owner;

    for ($i = 1; $i <= $dateInfo['daysInMonth']; $i++) {
      $prop       = 'wday' . $i;
      $M->$prop   = $dayofweek;
      $prop       = 'week' . $i;
      $myts       = strtotime($year . '-' . $month . '-' . $i);
      $M->$prop   = date("W", $myts);
      $dayofweek += 1;
      if ($dayofweek == 8) {
        $dayofweek = 1;
        $weeknumber++;
      }
    }
    $M->year  = $year;
    $M->month = sprintf("%02d", $month);
    $M->create();
  }
  elseif ($target == 'user') {
    global $DB, $CONF;
    $T           = new App\Models\TemplateModel($DB->db, $CONF);
    $T->username = $owner;
    for ($i = 1; $i <= $dateInfo['daysInMonth']; $i++) {
      $prop     = 'abs' . $i;
      $T->$prop = '0';
    }
    $T->year  = $year;
    $T->month = sprintf("%02d", $month);
    $T->create();
  }
  else {
    return false;
  }
  return true;
}

//-----------------------------------------------------------------------------
/**
 * Checks whether a user is authorized in the active permission scheme.
 *
 * @param string|null $permission The permission to check.
 *
 * @return boolean True if the user is allowed, false otherwise.
 * @global object $UL User login object.
 * @global object $UO User options object.
 * @global array $permissions Array of permissions.
 *
 * @global bool True if allowed, false if not
 */
function isAllowed(?string $permission = ''): bool {
  if ($permission === null) {
    return false;
  }
  if ($permission === '') {
    return true;
  }
  global $C, $UL, $UO, $permissions;
  // @phpstan-ignore-next-line
  if (L_USER) {
    //
    // Someone is logged in.
    // First, check if 2FA required and user hasn't done it yet.
    //
    if (L_USER != 'admin' && $C->read('forceTfa') && !$UO->read(L_USER, 'secret')) {
      return false;
    }
    //
    // Check permission by role.
    //
    $UL->findByName(L_USER);
    return in_array(['permission' => $permission, 'role' => $UL->role], $permissions);
  }
  else {
    //
    // It's a public user.
    //
    return in_array(['permission' => $permission, 'role' => 3], $permissions);
  }
}
