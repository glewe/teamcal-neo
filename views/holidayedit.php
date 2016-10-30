<?php
/**
 * holidayedit.php
 * 
 * Holiday edit page view
 *
 * @category TeamCal Neo 
* @version 1.0.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.holidayedit
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <?php 
            if ($showAlert AND $C->read("showAlerts")!="none")
            { 
               if ( $C->read("showAlerts")=="all" OR 
                    $C->read("showAlerts")=="warnings" AND ($alertData['type']=="warning" OR $alertData['type']=="danger")
                  ) 
               {
                  echo createAlertBox($alertData);
               }
            } ?>
            <?php $tabindex = 1; $colsleft = 8; $colsright = 4;?>

            <form class="bs-example form-control-horizontal" name="form_create" action="index.php?action=<?=$CONF['controllers'][$controller]->name?>&amp;id=<?=$viewData['id']?>" method="post" target="_self" accept-charset="utf-8">
            
               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['hol_edit_title'].$viewData['name']?></div>
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_holidayUpdate"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=holidays" class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_holiday_list']?></a>
                        </div>
                     </div>
                     
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <input name="hidden_id" type="hidden" value="<?=$viewData['id']?>">
                           <?php foreach($viewData['holiday'] as $formObject) {
                              echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                           } ?>
                        </div>
                     </div>
                     
                  </div>
               </div>
               
            </form>
                     
         </div>
      </div>
