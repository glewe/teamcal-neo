<?php
/**
 * database.php
 * 
 * Database page view
 *
 * @category TeamCal Neo 
 * @version 1.3.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
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
                        <li><a href="#tab_cleanup" data-toggle="tab"><?=$LANG['db_tab_cleanup']?></a></li>
                        <li><a href="#tab_delete" data-toggle="tab"><?=$LANG['db_tab_delete']?></a></li>
                        <li><a href="#tab_admin" data-toggle="tab"><?=$LANG['db_tab_admin']?></a></li>
                        <li><a href="#tab_reset" data-toggle="tab"><?=$LANG['db_tab_reset']?></a></li>
                        <li><a href="#tab_tcpimp" data-toggle="tab"><?=$LANG['db_tab_tcpimp']?></a></li>
                     </ul>
                     
                     <div id="myTabContent" class="tab-content">
                     
                        <!-- Optimize Tables tab -->
                        <div class="tab-pane fade active in" id="tab_optimize">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <div class="form-group">
                                    <div class="col-lg-12">
                                       <h4><?=$LANG['db_optimize']?></h4>
                                       <div class="text-normal"><?=$LANG['db_optimize_comment']?></div>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                 <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" name="btn_optimize"><?=$LANG['btn_optimize_tables']?></button>
                                 
                              </div>
                           </div>
                        </div>
                     
                        <!-- Cleanup tab -->
                        <div class="tab-pane fade" id="tab_cleanup">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <div class="form-group">
                                    <div class="col-lg-<?=$colsleft?>">
                                       <div class="text-bold"><?=$LANG['db_clean_what']?></div>
                                       <div class="text-normal"><?=$LANG['db_clean_what_comment']?></div>
                                    </div>
                                    <div class="col-lg-<?=$colsright?>">
                                       <div class="checkbox">
                                          <label><input name="chk_cleanDaynotes" value="chk_cleanDaynotes" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_clean_daynotes']?></label>
                                       </div>
                                       <div class="checkbox">
                                          <label><input name="chk_cleanMonths" value="chk_cleanMonths" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_clean_months']?></label>
                                       </div>
                                       <div class="checkbox">
                                          <label><input name="chk_cleanTemplates" value="chk_cleanTemplates" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['db_clean_templates']?></label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>

                                 <div class="form-group">
                                    <div class="col-lg-<?=$colsleft?>">
                                       <div class="text-bold"><?=$LANG['db_clean_before']?></div>
                                       <div class="text-normal"><?=$LANG['db_clean_before_comment']?></div>
                                    </div>
                                    <div class="col-lg-<?=$colsright?>">
                                       <input id="cleanBefore" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_cleanBefore" maxlength="10" value="<?=$viewData['cleanBefore']?>" type="text">
                                       <?php if ( isset($inputAlert["cleanBefore"]) AND strlen($inputAlert["cleanBefore"]) ) { ?> 
                                          <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['cleanBefore']?></div>
                                       <?php } ?> 
                                       <script type="text/javascript">$(function() { $( "#cleanBefore" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>

                                 <div class="form-group">
                                    <div class="col-lg-<?=$colsleft?>">
                                       <div class="text-bold"><?=$LANG['db_clean_confirm']?></div>
                                       <div class="text-normal"><?=$LANG['db_clean_confirm_comment']?></div>
                                    </div>
                                    <div class="col-lg-<?=$colsright?>">
                                       <input class="form-control" tabindex="<?=$tabindex++?>" name="txt_cleanConfirm" maxlength="7" value="" type="text">
                                       <?php if ( isset($inputAlert["cleanConfirm"]) AND strlen($inputAlert["cleanConfirm"]) ) { ?> 
                                          <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['cleanConfirm']?></div>
                                       <?php } ?> 
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                 
                                 <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" name="btn_cleanup"><?=$LANG['btn_cleanup']?></button>
                                 
                              </div>
                           </div>
                        </div>
      
                        <!-- Delete tab -->
                        <div class="tab-pane fade" id="tab_delete">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <div class="form-group">
                                    <div class="col-lg-<?=$colsleft?>">
                                       <div class="text-bold"><?=$LANG['db_del_what']?></div>
                                       <div class="text-normal"><?=$LANG['db_del_what_comment']?></div>
                                    </div>
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
                                    <div class="col-lg-<?=$colsleft?>">
                                       <div class="text-bold"><?=$LANG['db_confirm']?></div>
                                       <div class="text-normal"><?=$LANG['db_del_confirm_comment']?></div>
                                    </div>
                                    <div class="col-lg-<?=$colsright?>">
                                       <input class="form-control" tabindex="<?=$tabindex++?>" name="txt_deleteConfirm" maxlength="6" value="" type="text">
                                       <?php if ( isset($inputAlert["deleteConfirm"]) AND strlen($inputAlert["deleteConfirm"]) ) { ?> 
                                          <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['deleteConfirm']?></div>
                                       <?php } ?> 
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                 
                                 <button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++;?>" name="btn_delete"><?=$LANG['btn_delete_records']?></button>
                                 
                              </div>
                           </div>
                        </div>
      
                        <!-- Administration tab -->
                        <div class="tab-pane fade" id="tab_admin">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <div class="form-group">
                                    <div class="col-lg-12">
                                       <h4><?=$LANG['db_dbURL']?></h4>
                                       <div class="text-normal"><?=$LANG['db_dbURL_comment']?></div>
                                    </div>
                                    <div class="col-lg-12 control-label">
                                       <input id="dbURL" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_dbURL" maxlength="160" value="<?=$viewData['dbURL']?>" type="text"><br>
                                       <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_saveURL"><?=$LANG['btn_save']?></button>
                                    </div>
                                 </div>
                                 <?php if (strlen($viewData['dbURL']) AND $viewData['dbURL'] != "#") { ?>                                 
                                 <div class="divider"><hr></div>
                                 <a href="<?=$C->read('dbURL')?>" class="btn btn-info" tabindex="<?=$tabindex++;?>" target="_blank"><?=$LANG['db_application']?></a>
                                 <?php } ?>
                              </div>
                           </div>
                        </div>

                        <!-- Reset tab -->
                        <div class="tab-pane fade" id="tab_reset">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <div class="alert alert-danger"><?=$LANG['db_reset_danger']?></div>
                                 <div class="form-group">
                                    <div class="col-lg-8">
                                       <div class="text-bold"><?=$LANG['db_resetString']?></div>
                                       <div class="text-normal"><?=$LANG['db_resetString_comment']?></div>
                                    </div>
                                    <div class="col-lg-4">
                                       <input id="dbResetString" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_dbResetString" maxlength="40" value="" type="text"><br>
                                    </div>
                                 </div>                                 
                                 <div class="divider"><hr></div>
                                 <button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++;?>" name="btn_reset"><?=$LANG['btn_reset_database']?></button>
                              </div>
                           </div>
                        </div>

                        <!-- TeamCal Pro Import tab -->
                        <div class="tab-pane fade" id="tab_tcpimp">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <div class="form-group">
                                    <div class="col-lg-12">
                                       <h4><?=$LANG['db_tcpimp']?></h4>
                                       <div class="text-normal"><?=$LANG['db_tcpimp_comment']?></div>
                                       <div class="text-normal">&nbsp;</div>
                                       <h4><?=$LANG['db_tcpimp2']?></h4>
                                       <div class="text-normal"><?=$LANG['tcpimp_info']?></div>
                                    </div>
                                 </div>                                 
                                 <div class="divider"><hr></div>
                                 <a href="index.php?action=tcpimport" class="btn btn-primary" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_import']?></a>
                              </div>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
                  
            </form>
            
         </div>
      </div>      
            