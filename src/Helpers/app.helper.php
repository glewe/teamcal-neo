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
 * Checks whether a user is authorized in the active permission scheme.
 *
 * @param string|null $permission The permission to check.
 *
 * @return boolean True if the user is allowed, false otherwise.
 * @global object $userLoggedIn User login object.
 * @global object $userOptionModel User options object.
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
  global $configModel, $userLoggedIn, $userOptionModel, $permissions;
  $user = currentUser();
  if ($user) {
    //
    // Someone is logged in.
    // First, check if 2FA required and user hasn't done it yet.
    //
    if (!($userLoggedIn->username === $user && $userLoggedIn->is_system) && $configModel->read('forceTfa') && !$userOptionModel->read($user, 'secret')) {
      return false;
    }
    //
    // Check permission by role.
    //
    $userLoggedIn->findByName($user);
    return in_array(['permission' => $permission, 'role' => $userLoggedIn->role], $permissions);
  }
  else {
    //
    // It's a public user.
    //
    return in_array(['permission' => $permission, 'role' => 3], $permissions);
  }
}

//-----------------------------------------------------------------------------
/**
 * Returns the current logged-in user's username, or "0" (falsy) if nobody is logged in.
 *
 * Routed through a function with a declared `string` return type (rather than referencing
 * the L_USER constant directly) so PHPStan trusts that declared type instead of narrowing to
 * whichever literal value a given analysis/bootstrap context happens to define it as — the
 * real value is set by index.php from the session once a user logs in.
 *
 * @return string The current username, or "0" when nobody is logged in
 */
function currentUser(): string {
  return (string) L_USER;
}
