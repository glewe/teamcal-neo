<?php
/**
 * register.php
 * 
 * Register page view
 *
 * @category TeamCal Neo 
 * @version 0.7.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.register
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
            
            <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['register_title']?></div>
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
                           <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" name="btn_register"><?=$LANG['btn_register']?></button>
                        </div>
                     </div>
                     
                  </div>
               </div>
                  
            </form>
         </div>
      </div>      
            