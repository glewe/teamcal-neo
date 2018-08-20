<?php
/**
 * passwordreset.php
 * 
 * Password reset page view
 *
 * @category TeamCal Neo 
 * @version 1.9.008
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2018 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.passwordreset
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
            
            <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;token=<?=$token?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <?php 
                  $pageHelp = '';
                  if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fa fa-question-circle fa-lg fa-menu"></i></a>';
                  ?>
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['pwdreq_title'].$pageHelp?></div>
                  <div class="panel-body">

                     <!-- Personal tab -->
                     <div class="tab-pane fade active in" id="personal">
                        <div class="panel panel-default">
                           <div class="panel-body">
                              <?php foreach($viewData['personal'] as $formObject) {
                                 echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                              } ?>
                           </div>
                        </div>
                     </div>

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" name="btn_update"><?=$LANG['btn_update']?></button>
                        </div>
                     </div>
                     
                  </div>
               </div>
                  
            </form>
         </div>
      </div>      
            