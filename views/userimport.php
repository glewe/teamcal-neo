<?php
/**
 * userimport.php
 * 
 * The view of the attachments page
 *
 * @category TeamCal Neo 
 * @version 1.8.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.userimport
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
         <?php $tabindex = 1; $colsleft = 6; $colsright = 6;?>
            
            <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['imp_title']?></div>
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="col-lg-6">
                              <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++?>" name="btn_import"><?=$LANG['btn_import']?></button>
                           </div>
                           <div class="col-lg-6 text-right">
                              <?php if (isAllowed("useraccount")) { ?> 
                                 <a href="index.php?action=users" class="btn btn-default pull-right" tabindex="<?=$tabindex++?>"><?=$LANG['btn_user_list']?></a>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  
                     <div class="form-group">
                        <label class="col-lg-<?=$colsleft?> control-label">
                           <?=$LANG['imp_file']?><br>
                           <span class="text-normal"><?=sprintf($LANG['imp_file_comment'],$viewData['upl_maxsize']/1024,$viewData['upl_formats'],APP_UPL_DIR)?></span>
                        </label>
                        <div class="col-lg-<?=$colsright?>">
                           <input type="hidden" name="MAX_FILE_SIZE" value="<?=$viewData['upl_maxsize']?>"><br>
                           <input class="form-control" tabindex="<?=$tabindex++?>" name="file_image" type="file"><br>
                        </div>
                     </div>
                     <div class="divider"><hr></div>
                                 
                     <?php foreach($viewData['import'] as $formObject) {
                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                     } ?>

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="col-lg-6">
                              <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++?>" name="btn_import"><?=$LANG['btn_import']?></button>
                           </div>
                           <div class="col-lg-6 text-right">
                              <?php if (isAllowed("useraccount")) { ?> 
                                 <a href="index.php?action=users" class="btn btn-default pull-right" tabindex="<?=$tabindex++?>"><?=$LANG['btn_user_list']?></a>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                                 
                  </div>
               </div>

            </form>
            
         </div>
         
      </div>      
