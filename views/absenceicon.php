<?php
/**
 * absenceicon.php
 * 
 * Absence icon page view
 *
 * @category TeamCal Neo 
 * @version 2.2.1
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
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
                  <div class="panel-heading"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['absico_title'].$viewData['name']?></div>
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=absenceedit&amp;id=<?=$viewData['id']?>" class="btn btn-default pull-right" style="margin-left:8px;" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_edit']?></a>
                        </div>
                     </div>

                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#solid" data-toggle="tab"><?=$LANG['absico_tab_solid']." (".count($viewData['fasIcons']).")"?></a></li>
                        <li><a href="#regular" data-toggle="tab"><?=$LANG['absico_tab_regular']." (".count($viewData['farIcons']).")"?></a></li>
                        <li><a href="#brand" data-toggle="tab"><?=$LANG['absico_tab_brand']." (".count($viewData['fabIcons']).")"?></a></li>
                     </ul>

                     <div id="myTabContent" class="tab-content">
                        
                        <!-- Solid Icons -->
                        <div class="tab-pane fade active in" id="solid">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <div class="col-lg-12">
                                 <?php
                                 $count = 0; 
                                 foreach ( $viewData['fasIcons'] as $fai )
                                 {
                                    if ($count % 12 == 0) echo '</div><div class="col-lg-12">';
                                    $iconTooltip = '<span class=\''.$fai['val'].' fa-5x text-info\' title=\''.$fai['val'].'\'></span>';
                                    echo '<div class="col-lg-1" style="border: '.(($fai['val'] == $viewData['icon']) ? "1" : "0").'px solid #CC0000;"><div class="radio">';
                                    echo '<label><input name="opt_absIcon" value="'.$fai['val'].'" tabindex="'.$tabindex++.'" type="radio"'.(($fai['val'] == $viewData['icon']) ? " checked" : "").'><i data-position="tooltip-top" class="tooltip-info" data-toggle="tooltip" data-title="'.$iconTooltip.'"><span class="'.$fai['val'].' fa-lg text-info" title="'.$fai['val'].'"></span></i></label>';
                                    echo '</div></div>';
                                    $count++;
                                 } ?>
                                 </div>
                              </div>
                           </div>
                        </div>
                        
                        <!-- Regular Icons -->
                        <div class="tab-pane fade" id="regular">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <div class="col-lg-12">
                                 <?php
                                 $count = 0; 
                                 foreach ( $viewData['farIcons'] as $fai )
                                 {
                                    if ($count % 12 == 0) echo '</div><div class="col-lg-12">';
                                    $iconTooltip = '<span class=\''.$fai['val'].' fa-5x text-info\' title=\''.$fai['val'].'\'></span>';
                                    echo '<div class="col-lg-1" style="border: '.(($fai['val'] == $viewData['icon']) ? "1" : "0").'px solid #CC0000;"><div class="radio">';
                                    echo '<label><input name="opt_absIcon" value="'.$fai['val'].'" tabindex="'.$tabindex++.'" type="radio"'.(($fai['val'] == $viewData['icon']) ? " checked" : "").'><i data-position="tooltip-top" class="tooltip-info" data-toggle="tooltip" data-title="'.$iconTooltip.'"><span class="'.$fai['val'].' fa-lg text-info" title="'.$fai['val'].'"></span></i></label>';
                                    echo '</div></div>';
                                    $count++;
                                 } ?>
                                 </div>
                              </div>
                           </div>
                        </div>
                        
                        <!-- Brand Icons -->
                        <div class="tab-pane fade" id="brand">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <div class="col-lg-12">
                                 <?php
                                 $count = 0; 
                                 foreach ( $viewData['fabIcons'] as $fai )
                                 {
                                    if ($count % 12 == 0) echo '</div><div class="col-lg-12">';
                                    $iconTooltip = '<span class=\''.$fai['val'].' fa-5x text-info\' title=\''.$fai['val'].'\'></span>';
                                    echo '<div class="col-lg-1" style="border: '.(($fai['val'] == $viewData['icon']) ? "1" : "0").'px solid #CC0000;"><div class="radio">';
                                    echo '<label><input name="opt_absIcon" value="'.$fai['val'].'" tabindex="'.$tabindex++.'" type="radio"'.(($fai['val'] == $viewData['icon']) ? " checked" : "").'><i data-position="tooltip-top" class="tooltip-info" data-toggle="tooltip" data-title="'.$iconTooltip.'"><span class="'.$fai['val'].' fa-lg text-info" title="'.$fai['val'].'"></span></i></label>';
                                    echo '</div></div>';
                                    $count++;
                                 } ?>
                                 </div>
                              </div>
                           </div>
                        </div>

                     </div>

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=absenceedit&amp;id=<?=$viewData['id']?>" class="btn btn-default pull-right" style="margin-left:8px;" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_edit']?></a>
                        </div>
                     </div>
                  
                  </div>
               </div>
                  
            </form>
         </div>
      </div>
