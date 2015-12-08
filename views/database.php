<?php
/**
 * database.php
 * 
 * The view of the database page
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.database
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
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['db_title']?></div>
                  <div class="panel-body">

                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#tab_optimize" data-toggle="tab"><?=$LANG['db_tab_optimize']?></a></li>
                        <li><a href="#tab_delete" data-toggle="tab"><?=$LANG['db_tab_delete']?></a></li>
                        <li><a href="#tab_export" data-toggle="tab"><?=$LANG['db_tab_export']?></a></li>
                     </ul>
                     
                     <div id="myTabContent" class="tab-content">
                     
                        <!-- Optimize Tables tab -->
                        <div class="tab-pane fade active in" id="tab_optimize">
                           <div class="panel panel-default">
                              <div class="panel-body">

                                 <div class="form-group">
                                    <label class="col-lg-12 control-label">
                                       <?=$LANG['db_optimize']?><br>
                                       <span class="text-normal"><?=$LANG['db_optimize_comment']?></span>
                                    </label>
                                 </div>
                                 <div class="divider"><hr></div>
                                 <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" name="btn_optimize"><?=$LANG['btn_optimize_tables']?></button>
                                 
                              </div>
                           </div>
                        </div>
                     
                        <!-- Delete tab -->
                        <div class="tab-pane fade" id="tab_delete">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['db_del_what']?><br>
                                       <span class="text-normal"><?=$LANG['db_del_what_comment']?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <div class="checkbox">
                                          <label><input name="chk_delUsers" value="chk_delUsers" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_del_users']?></label>
                                       </div>
                                       <div class="checkbox">
                                          <label><input name="chk_delGroups" value="chk_delGroups" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_del_groups']?></label>
                                       </div>
                                       <div class="checkbox">
                                          <label><input name="chk_delMessages" value="chk_delMessages" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_del_messages']?></label>
                                       </div>
                                       <div class="checkbox">
                                          <label><input name="chk_delOrphMessages" value="chk_delOrphMessages" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_del_orphMessages']?></label>
                                       </div>
                                       <div class="checkbox">
                                          <label><input name="chk_delPermissions" value="chk_delPermissions" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_del_permissions']?></label>
                                       </div>
                                       <div class="checkbox">
                                          <label><input name="chk_delLog" value="chk_delLog" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_del_log']?></label>
                                       </div>
                                       <div class="checkbox">
                                          <label><input name="chk_delArchive" value="chk_delArchive" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_del_archive']?></label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>

                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['db_confirm']?><br>
                                       <span class="text-normal"><?=$LANG['db_del_confirm_comment']?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <input class="form-control" tabindex="<?=$tabindex++?>" name="txt_deleteConfirm" maxlength="6" value="" type="text">
                                       <?php if ( isset($inputAlert["deleteConfirm"]) AND strlen($inputAlert["deleteConfirm"]) ) { ?> 
                                          <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['deleteConfirm']?></div>
                                       <?php } ?> 
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                 
                                 <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_delete"><?=$LANG['btn_delete_records']?></button>
                                 
                              </div>
                           </div>
                        </div>
      
                        <!-- Export/Import tab -->
                        <div class="tab-pane fade" id="tab_export">
                           <div class="panel panel-default">
                              <div class="panel-body">

                                 <div class="form-group">
                                    <label class="col-lg-12 control-label">
                                       <?=$LANG['db_export']?><br>
                                       <span class="text-normal"><?=$LANG['db_export_comment']?></span>
                                    </label>
                                 </div>
                                 
                              </div>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
                  
            </form>
            
         </div>
      </div>      
            