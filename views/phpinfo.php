<?php
/**
 * phpinfo.php
 * 
 * Phpinfo page view
 *
 * @category TeamCal Neo 
 * @version 1.9.010
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2018 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.phpinfo
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <?php 
               $pageHelp = '';
               if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fa fa-question-circle fa-lg fa-menu"></i></a>';
               ?>
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['phpinfo_title'].$pageHelp?></div>
               <div class="panel-body">
                  <?=$viewData['phpInfo']?>
               </div>
            </div>
         </div>
         
      </div>
