<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\AbsenceDayModel;
use App\Models\AbsenceGroupModel;
use App\Models\AbsenceModel;
use App\Models\AllowanceModel;
use App\Models\AttachmentModel;
use App\Models\ConfigModel;
use App\Models\DbModel;
use App\Models\AvatarModel;
use App\Models\CalendarDayModel;
use App\Models\DaynoteModel;
use App\Models\GroupModel;
use App\Models\HolidayModel;
use App\Models\LogModel;
use App\Models\LoginModel;
use App\Models\MessageModel;
use App\Models\PermissionModel;
use App\Models\RegionModel;
use App\Models\RoleModel;
use App\Models\UserAttachmentModel;
use App\Models\UserGroupModel;
use App\Models\UserMessageModel;
use App\Models\UserModel;
use App\Models\UserOptionModel;
use App\Services\AbsenceService;
use App\Services\CalendarMonthBuilderService;
use App\Services\UserService;

/**
 * Base Controller
 *
 * Abstract base class for all controllers.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 *
 * @property AbsenceDayModel $absenceDayModel
 * @property AbsenceGroupModel $absenceGroupModel
 * @property AbsenceModel $absenceModel
 * @property AllowanceModel $allowanceModel
 * @property AttachmentModel $attachmentModel
 * @property AvatarModel $avatarModel
 * @property CalendarDayModel $calendarDayModel
 * @property ConfigModel $configModel
 * @property DaynoteModel $daynoteModel
 * @property DbModel $dbModel
 * @property GroupModel $groupModel
 * @property HolidayModel $holidayModel
 * @property LogModel $logModel
 * @property LoginModel $loginModel
 * @property MessageModel $messageModel
 * @property PermissionModel $permissionModel
 * @property RegionModel $regionModel
 * @property Request $request
 * @property RoleModel $roleModel
 * @property UserAttachmentModel $userAttachmentModel
 * @property UserGroupModel $userGroupModel
 * @property UserMessageModel $userMessageModel
 * @property UserModel $userModel
 * @property UserModel $userLoggedIn
 * @property UserOptionModel $userOptionModel
 * @property AbsenceService $absenceService
 * @property CalendarMonthBuilderService $calendarMonthBuilderService
 * @property UserService $userService
 *
 * @property array<string, mixed>  $alertData
 * @property array<string, mixed>  $allConfig
 * @property string[]              $appJqueryUIThemes
 * @property array<string, string> $appLanguages
 * @property string[]              $bsColors
 * @property array<string, mixed>  $CONF
 * @property array<string, string> $faIcons
 * @property array<string, mixed>  $htmlData
 * @property array<string, string> $LANG
 * @property array<string, string> $logLanguages
 * @property string[]              $timezones
 */
abstract class BaseController
{
  protected Container      $container;
  protected TemplateEngine $view;

  /**
   * Cached instances of lazy-loaded properties
   *
   * @var array<string, mixed>
   */
  protected array $_instances = [];

  /**
   * Map of property names to Container keys
   *
   * @var array<string, string>
   */
  private const DEPENDENCY_MAP = [
    'absenceService'              => 'AbsenceService',
    'calendarMonthBuilderService' => 'CalendarMonthBuilderService',
    'userService'                 => 'UserService',
    'absenceModel'                => 'AbsenceModel',
    'absenceDayModel'             => 'AbsenceDayModel',
    'absenceGroupModel'           => 'AbsenceGroupModel',
    'allowanceModel'              => 'AllowanceModel',
    'attachmentModel'             => 'AttachmentModel',
    'avatarModel'                 => 'AvatarModel',
    'calendarDayModel'            => 'CalendarDayModel',
    'configModel'                 => 'ConfigModel',
    'daynoteModel'                => 'DaynoteModel',
    'dbModel'                     => 'DbModel',
    'groupModel'                  => 'GroupModel',
    'holidayModel'                => 'HolidayModel',
    'loginModel'                  => 'LoginModel',
    'logModel'                    => 'LogModel',
    'messageModel'                => 'MessageModel',
    'permissionModel'             => 'PermissionModel',
    'regionModel'                 => 'RegionModel',
    'roleModel'                   => 'RoleModel',
    'userAttachmentModel'         => 'UserAttachmentModel',
    'userGroupModel'              => 'UserGroupModel',
    'userLoggedIn'                => 'UserLoggedIn',
    'userMessageModel'            => 'UserMessageModel',
    'userModel'                   => 'UserModel',
    'userOptionModel'             => 'UserOptionModel',
    'CONF'                        => 'CONF',
    'LANG'                        => 'LANG',
    'alertData'                   => 'alertData',
    'allConfig'                   => 'allConfig',
    'appJqueryUIThemes'           => 'appJqueryUIThemes',
    'appLanguages'                => 'appLanguages',
    'bsColors'                    => 'bsColors',
    'faIcons'                     => 'faIcons',
    'htmlData'                    => 'htmlData',
    'logLanguages'                => 'logLanguages',
    'request'                     => 'Request',
    'timezones'                   => 'timezones',
  ];

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param Container $container Dependency Injection Container
   */
  public function __construct(Container $container) {
    $this->container = $container;

    // Initialize Template Engine if available
    if ($container->has('TemplateEngine')) {
      $this->view = $container->get('TemplateEngine');
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Lazy load dependencies.
   *
   * @param string $name Property name
   * @return mixed
   */
  public function __get(string $name) {
    // Check if already instantiated
    if (isset($this->_instances[$name])) {
      return $this->_instances[$name];
    }

    // Check if it's a mapped dependency
    if (isset(self::DEPENDENCY_MAP[$name])) {
      $serviceName = self::DEPENDENCY_MAP[$name];
      // Special handling for htmlData which might be null/empty in container
      // but we want it initialized as array
      if ($name === 'htmlData') {
        $this->_instances[$name] = $this->container->get($serviceName) ?? [];
      }
      else {
        $this->_instances[$name] = $this->container->get($serviceName);
      }
      return $this->_instances[$name];
    }

    // Property not found
    $trace = debug_backtrace();
    trigger_error(
      'Undefined property via __get(): ' . $name .
      ' in ' . $trace[0]['file'] .
      ' on line ' . $trace[0]['line'],
      E_USER_NOTICE
    );
    return null;
  }

  //---------------------------------------------------------------------------
  /**
   * Check if a property is set.
   *
   * @param string $name Property name
   * @return bool
   */
  public function __isset(string $name): bool {
    return isset($this->_instances[$name]) || isset(self::DEPENDENCY_MAP[$name]);
  }

  //---------------------------------------------------------------------------
  /**
   * Main execution method for the controller.
   *
   * @return void
   */
  abstract public function execute(): void;

  //---------------------------------------------------------------------------
  /**
   * Check if a user is logged in.
   *
   * @return bool
   */
  public function isLoggedIn(): bool {
    // Access property via __get
    return (isset($this->userLoggedIn->username) && $this->userLoggedIn->username !== "");
  }

  //---------------------------------------------------------------------------
  /**
   * Renders a view file.
   *
   * @param string               $view Name of the view file (without extension)
   * @param array<string, mixed> $data Associative array of data to pass to the view
   *
   * @return void
   */
  protected function render(string $view, array $data = []): void {
    // Check for Twig template first
    if (isset($this->view) && file_exists(WEBSITE_ROOT . '/views/' . $view . '.twig')) {

      // Automatically determine controller name from class name
      if (!isset($data['controller'])) {
        $className          = (new \ReflectionClass($this))->getShortName();
        $data['controller'] = strtolower(str_replace('Controller', '', $className));
      }

      // Inject global data
      // Access properties via __get (this->Pname) to trigger lazy load
      global $language, $appLanguages, $userData;
      $data['allConfig']       = array_merge($this->allConfig, $data['allConfig'] ?? []);
      $data['htmlData']        = array_merge($this->htmlData, $data['htmlData'] ?? []);
      $data['userData']        = $userData;
      $data['CONF']            = $this->CONF;
      $data['LANG']            = array_merge($this->LANG, $data['LANG'] ?? []);
      $data['configModel']     = $this->configModel;
      $data['userLoggedIn']    = $this->userLoggedIn;
      $data['userOptionModel'] = $this->userOptionModel;
      $data['userGroupModel']  = $this->userGroupModel;
      $data['groupModel']      = $this->groupModel;
      $data['regionModel']     = $this->regionModel;
      $data['absenceModel']    = $this->absenceModel;
      $data['holidayModel']    = $this->holidayModel;
      $data['appLanguages']    = $appLanguages;
      $data['language']        = $language;
      $data['session']         = ['query_string' => $_SERVER['QUERY_STRING'] ?? ''];
      $data['csrf_token']      = $_SESSION['csrf_token'] ?? '';

      $this->view->getTwig()->addGlobal('LANG', $data['LANG']);

      echo $this->view->render($view, $data);
      return;
    }

    // Handle missing view error
    echo "View not found: " . $view;
  }

  //---------------------------------------------------------------------------
  /**
   * Renders the alert view.
   *
   * @param string $type    Alert type (success, warning, danger, info)
   * @param string $title   Alert title
   * @param string $subject Alert subject
   * @param string $text    Alert text
   * @param string $help    Alert help text
   *
   * @return void
   */
  protected function renderAlert(string $type, string $title, string $subject, string $text, string $help = ''): void {
    $alertData = [
      'type'    => $type,
      'title'   => $title,
      'subject' => $subject,
      'text'    => $text,
      'help'    => $help
    ];
    $this->render('alert', ['alertData' => $alertData]);
  }

  //---------------------------------------------------------------------------
  /**
   * Renders a Twig template as an HTML fragment (no layout wrapper) and exits.
   *
   * Used by fragment endpoints like CalendarViewMonthController so the browser
   * can receive a bare HTML chunk for lazy-loading injection.
   *
   * @param string               $view Template name (without .twig extension)
   * @param array<string, mixed> $data View data
   *
   * @return never
   */
  protected function renderFragment(string $view, array $data): void {
    $this->render($view, $data);
    exit;
  }
}
