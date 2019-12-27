<?php
/**
 * imprint.php
 * 
 * Imprint page view
 *
 * @category TeamCal Neo 
 * @version 2.2.3
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.imprint
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <?php 
               $pageHelp = '';
               if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
               ?>
               <div class="panel-heading"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['mnu_help_imprint'].$pageHelp?></div>
               <div class="panel-body">
                  <div class="col-lg-12">
                     <?php foreach ($LANG['imprint'] as $imprint) { ?>
                        <h4 style="margin-top: 40px"><?=$imprint['title']?></h4>
                        <?=$imprint['text']?>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>

      </div>
