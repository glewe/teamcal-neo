<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router Class
 *
 * Handles request routing to controllers.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class Router
{
  private array     $routes    = [];
  private Container $container;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param Container $container Dependency Injection Container
   */
  public function __construct(Container $container) {
    $this->container = $container;
  }

  //---------------------------------------------------------------------------
  /**
   * Registers a route.
   *
   * @param string $action          Action name (e.g., 'login')
   * @param string $controllerClass Controller class name (e.g., 'LoginController')
   *
   * @return void
   */
  public function add(string $action, string $controllerClass): void {
    $this->routes[$action] = $controllerClass;
  }

  //---------------------------------------------------------------------------
  /**
   * Dispatches the request to the appropriate controller.
   *
   * @param string $action Action name
   *
   * @return void
   * @throws \Exception If controller class is invalid
   */
  public function dispatch(string $action): void {
    if (isset($this->routes[$action])) {
      $controllerClass = $this->routes[$action];

      // Instantiate controller with container
      $controller = new $controllerClass($this->container);

      if ($controller instanceof BaseController) {
        $controller->execute();
      }
      else {
        throw new \Exception("Controller class '$controllerClass' must extend BaseController");
      }
    }
    else {
      // Fallback to legacy controller file
      $this->dispatchLegacy($action);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Loads a legacy controller file.
   *
   * @param string $action Action name
   *
   * @return void
   */
  private function dispatchLegacy(string $action): void {
    // Make necessary variables global for legacy scripts
    global $allConfig, $C, $CONF, $LANG, $LOG, $U, $UMSG, $UO, $L, $G, $P, $RO, $UG, $UL, $A, $AG, $AL, $D, $H, $M, $R, $T, $AV, $appStatus, $userData, $htmlData, $showAlert, $alertData, $appTitle, $language, $controller;

    $controller = $action; // Ensure global $controller is set for legacy scripts

    if (file_exists(WEBSITE_ROOT . '/controller/' . $action . '.php')) {
      require_once WEBSITE_ROOT . '/controller/' . $action . '.php';
    }
    else {
      // Controller not found
      $this->container->set('alertData', function ($c) use ($action, $LANG) {
        return [
          'type'    => 'danger',
          'title'   => $LANG['alert_danger_title'],
          'subject' => $LANG['alert_controller_not_found_subject'],
          'text'    => str_replace('%1%', $action, $LANG['alert_controller_not_found_text']),
          'help'    => $LANG['alert_controller_not_found_help']
        ];
      });

      $controller = new \App\Controllers\AlertController($this->container);
      $controller->execute();
    }
  }
}
