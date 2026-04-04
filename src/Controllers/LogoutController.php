<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Logout Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class LogoutController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    $username = $this->UL->username;
    $this->L->logout();
    $this->LOG->logEvent("logLogin", $username, "log_logout");

    header("Location: index.php");
    exit();
  }
}
