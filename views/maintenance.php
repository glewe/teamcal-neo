<?php
/**
 * maintenance.php
 * 
 * Maintenance page view
 *
 * @category TeamCal Neo 
 * @version 2.0.0
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.maintenance
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <?php 
               $pageHelp = '';
               if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
               ?>
               <div class="panel-heading"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['mtce_title'].$pageHelp?></div>
               <div class="panel-body">
                  <div class="col-lg-3"><img src="images/maintenance.gif" alt="" class="img_floatleft">
                  </div>
                  <div class="col-lg-9">
                     <h2><?=$LANG['mtce_title']?></h2>
                     <h3><?=$LANG['mtce_text']?></h3>
                  </div>
               </div>
            </div>
         </div>
         
      </div>
