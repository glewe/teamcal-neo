<?php
/**
 * imprint.php
 * 
 * The view of the imprint page
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.imprint
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['mnu_help_imprint']?></div>
               <div class="panel-body">
                  <div class="col-lg-12">
                     <?php foreach ($LANG['imprint'] as $imprint) { ?>
                        <h3><?=$imprint['title']?></h3>
                        <?=$imprint['text']?>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>

      </div>
