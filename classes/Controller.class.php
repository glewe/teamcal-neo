<?php
/**
 * Controller.class.php
 *
 * @category TeamCal Neo 
 * @version 0.6.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to deal with application controllers
 */
class Controller
{
   var $name = '';
   var $faIcon = 'folder-o';
   var $iconColor = 'default';
   var $panelColor = 'default';
   var $permission = '';
    
   // ---------------------------------------------------------------------
   /**
    * Constructor
    * 
    * @param string $name Controller name
    * @param string $faIcon Fontawesome icon (w/o prefix "fa_")
    * @param string $iconColor Bootstrap color name for icon text color
    * @param string $panelColor Bootstrap color name for panel header background color
    * @param string $permission The permission a user role must have to access this controller. Leave empty for public access.
    * 
    */
   function __construct($name, $faIcon, $iconColor, $panelColor, $permission)
   {
      if (!strlen($name))
      {
         /**
          * No name passed
          */
         $errorData['title'] = 'Application Error';
         $errorData['subject'] = 'Controller Instance';
         $errorData['text'] = 'The controller instance could not be initiated due to a missing controller name.';
         require (WEBSITE_ROOT . '/views/error.php');
         die();
      }
      $this->name = $name;
      $this->faIcon = $faIcon;
      $this->iconColor = $iconColor;
      $this->panelColor = $panelColor;
      $this->permission = $permission;
   }
}
?>
