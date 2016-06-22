<?php
/**
 * attachments.php
 * 
 * The view of the attachments page
 *
 * @category TeamCal Neo 
 * @version 0.8.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.attachments
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
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['upload_title']?></div>
                  <div class="panel-body">
                  
                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#tab_files" data-toggle="tab"><?=$LANG['upload_tab_files']?></a></li>
                        <li><a href="#tab_upload" data-toggle="tab"><?=$LANG['upload_tab_upload']?></a></li>
                     </ul>

                     <div id="myTabContent" class="tab-content">
                     
                        <!-- Files -->
                        <div class="tab-pane fade active in" id="tab_files">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                                    <div class="col-lg-10"><?=$LANG['upload_col_file']?></div>
                                    <div class="col-lg-2 text-right"><?=$LANG['action']?></div>
                                 </div>
                              
                                 <?php foreach($viewData['uplFiles'] as $file) { ?>
                                 <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                    <div class="col-lg-10">
                                       <input name="chk_file[]" value="<?=$file?>" tabindex="<?=$tabindex++?>" type="checkbox" <?php if ($UL->username != 'admin' AND $UL->username != $UPF->getUploader($file)) { ?>disabled<?php } ?>>
                                       <?php if (in_array(getFileExtension($file),$CONF['imgExtensions'])) { ?>
                                          <a class="image-popup" href="<?=$CONF['app_upl_dir'].$file?>" title="<?=$file?>">
                                             <img src="<?=$CONF['app_upl_dir'].$file?>" alt="" style="width: 24px; height: 24px;">
                                          </a>
                                       <?php } else { ?>
                                          <a href="<?=$CONF['app_upl_dir'].$file?>"><img src="images/icons/mimetypes/<?=getFileExtension($file)?>.png" alt="" style="width: 24px; height: 24px;"></a>
                                       <?php } ?>
                                       <?=$file?>
                                    </div>
                                    <div class="col-lg-2 text-right">
                                       <?php if (in_array(getFileExtension($file),$CONF['imgExtensions'])) { ?>
                                          <a href="<?=$CONF['app_upl_dir'].$file?>" class="image-popup btn btn-default btn-xs" tabindex="<?=$tabindex++;?>" title="<?=$file?>"><img src="#" alt=""><?=$LANG['btn_download_view']?></a>
                                       <?php } else { ?>
                                          <a href="<?=$CONF['app_upl_dir'].$file?>" class="btn btn-default btn-xs" tabindex="<?=$tabindex++;?>" title="<?=$file?>"><?=$LANG['btn_download_view']?></a>
                                       <?php } ?>
                                    </div>
                                 </div>
                                 <?php } ?>
                                 
                                 <div style="clear: both; padding: 16px 0 0 16px;">
                                    <button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteFiles"><?=$LANG['btn_delete_selected']?></button>
                                 </div>
                                    
                              </div>
                           </div>
                        </div>

                        <!-- Upload File -->
                        <div class="tab-pane fade" id="tab_upload">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['upload_file']?><br>
                                       <span class="text-normal"><?=sprintf($LANG['upload_file_comment'],$viewData['upl_maxsize']/1024,$viewData['upl_formats'],$CONF['app_upl_dir'])?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <input type="hidden" name="MAX_FILE_SIZE" value="<?=$viewData['upl_maxsize']?>"><br>
                                       <input class="form-control" tabindex="<?=$tabindex++?>" name="file_image" type="file"><br>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                 
                                 <!-- Share with -->
                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['upload_shareWith']?><br>
                                       <span class="text-normal"><?=$LANG['upload_shareWith_comment']?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <div class="radio"><label><input name="opt_shareWith" value="all" tabindex="<?=$tabindex++?>" <?=($viewData['shareWith']=='all')?"checked":"";?> type="radio"><?=$LANG['upload_shareWith_all']?></label></div>
                                       <div class="radio"><label><input name="opt_shareWith" value="group" tabindex="<?=$tabindex++?>" <?=($viewData['shareWith']=='group')?"checked":"";?> type="radio"><?=$LANG['upload_shareWith_group']?></label></div>
                                       <select class="form-control" name="sel_shareWithGroup[]" multiple="multiple" size="5" tabindex="<?=$tabindex++?>">
                                          <?php foreach ($viewData['groups'] as $group) { ?>
                                             <option value="<?=$group['id']?>" <?=(in_array($group,$viewData['shareWithGroup']))?"selected":"";?>><?=$group['name']?></option>
                                          <?php } ?>
                                       </select>
                                       
                                       <div class="radio"><label><input name="opt_shareWith" value="role" tabindex="<?=$tabindex++?>" <?=($viewData['shareWith']=='role')?"checked":"";?> type="radio"><?=$LANG['upload_shareWith_role']?></label></div>
                                       <select class="form-control" name="sel_shareWithRole[]" multiple="multiple" size="5" tabindex="<?=$tabindex++?>">
                                          <?php foreach ($viewData['roles'] as $role) { ?>
                                             <option value="<?=$role['id']?>" <?=(in_array($group,$viewData['shareWithRole']))?"selected":"";?>><?=$role['name']?></option>
                                          <?php } ?>
                                       </select>
            
                                       <div class="radio"><label><input name="opt_shareWith" value="user" tabindex="<?=$tabindex++?>" <?=($viewData['shareWith']=='user')?"checked":"";?> type="radio"><?=$LANG['upload_shareWith_user']?></label></div>
                                       <select class="form-control" name="sel_shareWithUser[]" multiple="multiple" size="5" tabindex="<?=$tabindex++?>">
                                       <?php foreach ($viewData['users'] as $user) {
                                          if ( $user['firstname']!="" ) $showname = $user['lastname'].", ".$user['firstname'];
                                          else $showname = $user['lastname']; ?>
                                          <option class="option" value="<?=$user['username']?>" <?=(in_array($user['username'],$viewData['shareWithUser']))?"selected":"";?>><?=$showname?></option>
                                       <?php } ?>
                                       </select>
                                    </div>
                                 </div>

                                 <div class="divider"><hr></div>
                                 <div style="clear: both; padding: 16px 0 0 16px;">
                                    <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++?>" name="btn_uploadFile"><?=$LANG['btn_upload']?></button>
                                 </div>
                                 
                              </div>
                           </div>
                        </div>
                        
                        
                     </div>
                  </div>
               </div>

               <!-- Modal: Delete selected files -->
               <?=createModalTop('modalDeleteFiles', $LANG['modal_confirm'])?>
                  <?=$LANG['upload_confirm_delete']?>
               <?=createModalBottom('btn_deleteFile', 'danger', $LANG['btn_delete_selected'])?>
                                       
            </form>
            
         </div>
         
      </div>      
