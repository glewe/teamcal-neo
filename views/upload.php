<?php
/**
 * upload.php
 * 
 * The view of the upload page
 *
 * @category TeamCal Neo 
 * @version 0.5.006
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.upload 
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
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['upload_title']?></div>
                  <div class="panel-body">
                  
                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#image" data-toggle="tab"><?=$LANG['upload_tab_image']?></a></li>
                        <li><a href="#doc" data-toggle="tab"><?=$LANG['upload_tab_doc']?></a></li>
                     </ul>

                     <div id="myTabContent" class="tab-content">
                        
                        <!-- Image tab -->
                        <div class="tab-pane fade active in" id="image">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['upload_imageUpload']?><br>
                                       <span class="text-normal">
                                          <?=sprintf($LANG['upload_imageUpload_comment'],$viewData['image_maxsize']/1024,$viewData['image_formats'],$CONF['app_image_dir'])?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <input type="hidden" name="MAX_FILE_SIZE" value="<?=$viewData['image_maxsize']?>"><br>
                                       <input class="form-control" tabindex="<?=$tabindex++?>" name="file_image" type="file"><br>
                                       <button type="submit" class="btn btn-primary btn-sm" tabindex="<?=$tabindex++?>" name="btn_uploadImage"><?=$LANG['btn_upload']?></button>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                 
                                 <div class="form-group">
                                    <label class="col-lg-12 control-label">
                                       <?=$LANG['upload_images']?><br>
                                       <span class="text-normal">
                                          <?=$LANG['upload_images_comment']?></span>
                                    </label>
                                    <div class="col-lg-12">
                                       <?php foreach($viewData['imageFiles'] as $image) { ?>
                                          <div class="pull-left tooltip-warning text-center" style="border: 1px solid #eeeeee; padding: 4px;" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$image?>">
                                             <a class="image-popup" href="<?=$CONF['app_image_dir'].$image?>" title="<?=$image?>">
                                                <img src="<?=$CONF['app_image_dir'].$image?>" alt="" style="width: 80px; height: 80px;">
                                             </a><br>
                                             <input name="chk_image[]" value="<?=$image?>" tabindex="<?=$tabindex++?>" type="checkbox">
                                          </div>
                                       <?php } ?>
                                    </div>
                                    <div style="clear: both; padding: 16px 0 0 16px;">
                                       <button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteImages"><?=$LANG['btn_delete_selected']?></button>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                              
                              </div>
                           </div>
                        </div>
      
                        <!-- Document tab -->
                        <div class="tab-pane fade" id="doc">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['upload_docUpload']?><br>
                                       <span class="text-normal">
                                          <?=sprintf($LANG['upload_docUpload_comment'],$viewData['doc_maxsize']/1024,$viewData['doc_formats'],$CONF['app_doc_dir'])?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <input type="hidden" name="MAX_FILE_SIZE" value="<?=$viewData['doc_maxsize']?>"><br>
                                       <input class="form-control" tabindex="<?=$tabindex++?>" name="file_doc" type="file"><br>
                                       <button type="submit" class="btn btn-primary btn-sm" tabindex="<?=$tabindex++?>" name="btn_uploadDoc"><?=$LANG['btn_upload']?></button>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                 
                                 <div class="form-group">
                                    <label class="col-lg-12 control-label">
                                       <?=$LANG['upload_docs']?><br>
                                       <span class="text-normal">
                                          <?=$LANG['upload_docs_comment']?></span>
                                    </label>
                                    <div class="col-lg-12">
                                       <?php foreach($viewData['docFiles'] as $doc) {
                                          $pieces = explode(".", $doc);
                                          ?>
                                          <div class="pull-left tooltip-warning text-center" style="border: 1px solid #eeeeee; padding: 4px;" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$doc?>">
                                             <a href="<?=$CONF['app_doc_dir'].$doc?>"><img src="images/icons/mimetypes/<?=$pieces[1]?>.png" alt="" style="width: 80px; height: 80px;"></a>
                                             <br>
                                             <input name="chk_doc[]" value="<?=$doc?>" tabindex="<?=$tabindex++?>" type="checkbox">
                                          </div>
                                       <?php } ?>
                                    </div>
                                    <div style="clear: both; padding: 16px 0 0 16px;">
                                       <button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteDocs"><?=$LANG['btn_delete_selected']?></button>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                              
                              </div>
                           </div>
                        </div>
      
                     </div>
                     
                  </div>
               </div>

               <!-- Modal: Delete selected images -->
               <?=createModalTop('modalDeleteImages', $LANG['modal_confirm'])?>
                  <?=$LANG['upload_confirm_delete']?>
               <?=createModalBottom('btn_deleteImage', 'danger', $LANG['btn_delete_selected'])?>
                                       
               <!-- Modal: Delete selected docs -->
               <?=createModalTop('modalDeleteDocs', $LANG['modal_confirm'])?>
                  <?=$LANG['upload_confirm_delete']?>
               <?=createModalBottom('btn_deleteDoc', 'danger', $LANG['btn_delete_selected'])?>
                                       
            </form>
            
         </div>
         
      </div>      
