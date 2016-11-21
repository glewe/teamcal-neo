<?php
/**
 * absenceicon.php
 * 
 * Absence icon page view
 *
 * @category TeamCal Neo 
 * @version 1.2.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.absenceicon
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
            
            <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;id=<?=$viewData['id']?>" method="post" target="_self" accept-charset="utf-8">

               <input name="hidden_id" type="hidden" class="text" value="<?=$viewData['id']?>">
               <input name="hidden_name" type="hidden" class="text" value="<?=$viewData['name']?>">
                           
               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['abs_icon_title'].$viewData['name']?></div>
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=absenceedit&amp;id=<?=$viewData['id']?>" class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_edit']?></a>
                        </div>
                     </div>

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="col-lg-12">
                           <?php
                           $count = 0; 
                           foreach ( $viewData['faIcons'] as $fai )
                           {
                              if ($count % 12 == 0) echo '</div><div class="col-lg-12">';
                              echo '<div class="col-lg-1" style="border: ' . (($fai['val'] == $viewData['icon']) ? "1" : "0") . 'px solid #CC0000;"><div class="radio">';
                              echo '<label><input name="opt_absIcon" value="' . $fai['val'] . '" tabindex="' . $tabindex++ . '" type="radio"' . (($fai['val'] == $viewData['icon']) ? " checked" : "") . '><span class="fa fa-' . $fai['val'] . ' fa-lg text-info"></span></label>';
                              echo '</div></div>';
                              $count++;
                           } ?>
                           </div>
                        </div>
                     </div>
                     
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=absence&amp;id=<?=$viewData['id']?>" class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_edit']?></a>
                        </div>
                     </div>
                  
                  </div>
               </div>
                  
            </form>
            
         </div>
         
      </div>      
