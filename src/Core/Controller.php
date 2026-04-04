<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Controller
 *
 * This class provides methods and properties for application controllers.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Controller
{
  public string $name       = '';
  public string $faIcon     = 'folder-o';
  public string $iconColor  = 'default';
  public string $panelColor = 'default';
  public string $permission = '';
  public string $title      = '';
  public string $docurl     = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param string $name Controller name
   * @param string $faIcon Fontawesome icon (w/o prefix "fa_")
   * @param string $iconColor Bootstrap color name for icon text color
   * @param string $panelColor Bootstrap color name for panel header background color
   * @param string $permission The permission a user role must have to access this controller. Leave empty for public access.
   * @param string $title Controller title
   * @param string $docurl URL to the documentation of this controller
   */
  public function __construct(
    string $name,
    string $faIcon,
    string $iconColor,
    string $panelColor,
    string $permission,
    string $title,
    string $docurl
  ) {
    if (trim($name) === '') {
      throw new \InvalidArgumentException('Controller name cannot be empty');
    }
    $this->name       = $name;
    $this->faIcon     = $faIcon;
    $this->iconColor  = $iconColor;
    $this->panelColor = $panelColor;
    $this->permission = $permission;
    $this->title      = $title;
    $this->docurl     = $docurl;
  }
}
