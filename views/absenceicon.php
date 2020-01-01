<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Absence Icon View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo Pro
 * @subpackage Views
 * @since 3.0.0
 */
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
            
            <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;id=<?=$viewData['id']?>" method="post" target="_self" accept-charset="utf-8">

               <input name="hidden_id" type="hidden" class="text" value="<?=$viewData['id']?>">
               <input name="hidden_name" type="hidden" class="text" value="<?=$viewData['name']?>">
                           
               <div class="card">
                  <?php 
                  $pageHelp = '';
                  if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="float-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                  ?>
                  <div class="card-header text-white bg-<?=$CONF['controllers'][$controller]->panelColor?>"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['absico_title'].$viewData['name']?><?=$pageHelp?></div>
                  <div class="card-body">

                     <div class="card">
                        <div class="card-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=absenceedit&amp;id=<?=$viewData['id']?>" class="btn btn-secondary float-right" style="margin-left:8px;" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_edit']?></a>
                        </div>
                     </div>
                     <div style="height:20px;"></div>

                     <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="solid-tab" href="#solid" data-toggle="tab" role="tab" aria-controls="solid" aria-selected="true"><?=$LANG['absico_tab_solid']." (".count($viewData['fasIcons']).")"?></a></li>
                        <li class="nav-item"><a class="nav-link" id="regular-tab" href="#regular" data-toggle="tab" role="tab" aria-controls="regular" aria-selected="false"><?=$LANG['absico_tab_regular']." (".count($viewData['farIcons']).")"?></a></li>
                        <li class="nav-item"><a class="nav-link" id="brand-tab" href="#brand" data-toggle="tab" role="tab" aria-controls="brand" aria-selected="false"><?=$LANG['absico_tab_brand']." (".count($viewData['fabIcons']).")"?></a></li>
                     </ul>

                     <div id="myTabContent" class="tab-content">
                        
                        <!-- Solid Icons -->
                        <div class="tab-pane fade show active" id="solid" role="tabpanel" aria-labelledby="solid-tab">
                           <div class="card">
                              <div class="card-body">
                                 <div class="row">
                                 <?php
                                 $count = 0; 
                                 foreach ( $viewData['fasIcons'] as $fai )
                                 {
                                    if ($count % 12 == 0) echo '</div><div class="row">';
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
                        <div class="tab-pane fade" id="regular" role="tabpanel" aria-labelledby="regular-tab">
                           <div class="card">
                              <div class="card-body">
                                 <div class="row">
                                 <?php
                                 $count = 0; 
                                 foreach ( $viewData['farIcons'] as $fai )
                                 {
                                    if ($count % 12 == 0) echo '</div><div class="row">';
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
                        <div class="tab-pane fade" id="brand" role="tabpanel" aria-labelledby="brand-tab">
                           <div class="card">
                              <div class="card-body">
                                 <div class="row">
                                 <?php
                                 $count = 0; 
                                 foreach ( $viewData['fabIcons'] as $fai )
                                 {
                                    if ($count % 12 == 0) echo '</div><div class="row">';
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

                     <div style="height:20px;"></div>
                     <div class="card">
                        <div class="card-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=absenceedit&amp;id=<?=$viewData['id']?>" class="btn btn-secondary float-right" style="margin-left:8px;" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_edit']?></a>
                        </div>
                     </div>
                  
                  </div>
               </div>
                  
            </form>
         </div>
      </div>
