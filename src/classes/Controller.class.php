<?php

if (!defined('VALID_ROOT')) exit('');
/**
 * Controller
 *
 * This class provides methods and properties for application controllers.
 * 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Calendar Management
 * @since 3.0.0
 */
class Controller
{
    public $name = '';
    public $faIcon = 'folder-o';
    public $iconColor = 'default';
    public $panelColor = 'default';
    public $permission = '';
    public $title = '';
    public $docurl = '';

    // ---------------------------------------------------------------------
    /**
     * Constructor
     * 
     * @param string $name Controller name
     * @param string $faIcon Fontawesome icon (w/o prefix "fa_")
     * @param string $iconColor Bootstrap color name for icon text color
     * @param string $panelColor Bootstrap color name for panel header background color
     * @param string $permission The permission a user role must have to access this controller. Leave empty for public access.
     * @param string $docurl URL to the documentation of this controller
     * 
     */
    public function __construct($name, $faIcon, $iconColor, $panelColor, $permission, $title, $docurl)
    {
        if (!strlen($name)) {
            /**
             * No name passed
             */
            $errorData['title'] = 'Application Error';
            $errorData['subject'] = 'Controller Instance';
            $errorData['text'] = 'The controller instance could not be initiated due to a missing controller name.';
            require(WEBSITE_ROOT . '/views/error.php');
            die();
        }
        $this->name = $name;
        $this->faIcon = $faIcon;
        $this->iconColor = $iconColor;
        $this->panelColor = $panelColor;
        $this->permission = $permission;
        $this->title = $title;
        $this->docurl = $docurl;
    }
}
