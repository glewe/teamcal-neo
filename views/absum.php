<?php
/**
 * absum.php
 * 
 * Absence summary page view
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
      view.absum
      -->
      <div class="container content">
      
         <?php $tabindex = 1; $colsleft = 1; $colsright = 4;?>
         
         <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;user=<?=$viewData['username']?>" method="post" target="_self" accept-charset="utf-8">

            <div class="page-menu">
               <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectUser"><?=$LANG['user'] . ': ' . $viewData['fullname']?></button>
               <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalYear"><?=$LANG['year']?> <span class="badge"><?=$viewData['year']?></span></button>
            </div>

            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><?=sprintf($LANG['absum_title'], $viewData['year'], $viewData['fullname'])?></div>
               <div class="panel-body">
               
                  <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                     <div class="col-lg-6 text-bold"><?=$LANG['absum_absencetype']?></div>
                     <div class="col-lg-2 text-right text-bold"><?=$LANG['absum_contingent']?>&nbsp;<?=iconTooltip($LANG['absum_contingent_tt'],$LANG['absum_contingent'],'bottom')?></div>
                     <div class="col-lg-2 text-right text-bold"><?=$LANG['absum_taken']?></div>
                     <div class="col-lg-2 text-right text-bold"><?=$LANG['absum_remainder']?></div>
                  </div>
                  <?php if ( count($viewData['absences']) ) {
                     foreach ($viewData['absences'] as $abs) { ?>
                        <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                           <div id="sample" class="col-lg-6"><i class="fa fa-<?=$abs['icon']?> fa-lg" style="color: #<?=$abs['color']?>; background-color: #<?=$abs['bgcolor']?>; border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 4px; margin-right: 8px;"></i><?=$abs['name']?></div>
                           <div class="col-lg-2 text-right"><?=$abs['contingent']?></div>
                           <div class="col-lg-2 text-right <?=(is_int($abs['allowance']) AND intval($abs['taken'])>intval($abs['allowance']))?'text-warning':'';?>"><?=$abs['taken']?></div>
                           <div class="col-lg-2 text-right <?=(is_int($abs['allowance']) AND intval($abs['remainder'])<0)?'text-danger':'text-success';?>"><?=$abs['remainder']?></div>
                        </div>
                     <?php } 
                  } ?>
               
               </div>
            </div>

            <!-- Modal: Select User -->
            <?=createModalTop('modalSelectUser', $LANG['caledit_selUser'])?>
               <select id="user" class="form-control" name="sel_user" tabindex="<?=$tabindex++?>">
                  <?php foreach($viewData['users'] as $usr) { ?>
                     <option value="<?=$usr['username']?>"<?=(($viewData['username'] == $usr['username'])?' selected="selected"':'')?>><?=$usr['lastfirst']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_user', 'success', $LANG['btn_select'])?>

            <!-- Modal: Year -->
            <?=createModalTop('modalYear', $LANG['absum_modalYearTitle'])?>
               <div>
                  <span class="text-bold"><?=$LANG['absum_year']?></span><br>
                  <span class="text-normal"><?=$LANG['absum_year_comment']?></span>
                  <select id="sel_year" class="form-control" name="sel_year" tabindex="<?=$tabindex++?>">
                     <option value="<?=date("Y")-1?>" <?=(($viewData['year']==date("Y")-1)?"selected":"")?>><?=date("Y")-1?></option>
                     <option value="<?=date("Y")?>" <?=(($viewData['year']==date("Y"))?"selected":"")?>><?=date("Y")?></option>
                     <option value="<?=date("Y")+1?>" <?=(($viewData['year']==date("Y")+1)?"selected":"")?>><?=date("Y")+1?></option>
                  </select><br>
               </div>
            <?=createModalBottom('btn_year', 'success', $LANG['btn_select'])?>
            
         </form>

      </div>