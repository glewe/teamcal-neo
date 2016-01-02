<?php
/**
 * home.php
 * 
 * Home page view
 *
 * @category TeamCal Neo 
 * @version 0.4.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.home 
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['home_title']?></div>
               <div class="panel-body">
                  <?php echo $C->read("welcomeText"); ?>
               </div>
            </div>
         </div>
         
      </div>
