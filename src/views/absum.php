<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Absence Summary View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */
?>

      <!-- ==================================================================== 
      view.absum
      -->
      <div class="container content">
      
         <?php $tabindex = 1; $colsleft = 1; $colsright = 4;?>
         
         <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;user=<?=$viewData['username']?>" method="post" target="_self" accept-charset="utf-8">

            <div class="page-menu">
               <button type="button" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectUser"><?=$LANG['user'] . ': ' . $viewData['fullname']?></button>
               <?php if (!$C->read('currentYearOnly')) {?>
                  <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalYear"><?=$LANG['year']?> <span class="badge badge-light"><?=$viewData['year']?></span></button>
               <?php } ?>
               <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="left" title="Tooltip on left">Tooltip on left</button>
            </div>
            <div style="height:20px;"></div>

            <div class="card">
               <?php 
               $pageHelp = '';
               if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="float-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
               ?>
               <div class="card-header bg-<?=$CONF['controllers'][$controller]->panelColor?>"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=sprintf($LANG['absum_title'], $viewData['year'], $viewData['fullname'])?><?=$pageHelp?></div>
               <div class="card-body">
               
                  <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                     <div class="col-lg-6 text-bold"><?=$LANG['absum_absencetype']?></div>
                     <div class="col-lg-2 text-right text-bold"><?=$LANG['absum_contingent']?>&nbsp;<?=iconTooltip($LANG['absum_contingent_tt'],$LANG['absum_contingent'],'bottom')?></div>
                     <div class="col-lg-2 text-right text-bold"><?=$LANG['absum_taken']?></div>
                     <div class="col-lg-2 text-right text-bold"><?=$LANG['absum_remainder']?></div>
                  </div>
                  <?php if ( count($viewData['absences']) ) {
                     foreach ($viewData['absences'] as $abs) { 
                        if (!$abs['counts_as']) { ?>
                           <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                              <div class="col-lg-6"><i class="<?=$abs['icon']?>" style="color: #<?=$abs['color']?>; background-color: #<?=$abs['bgcolor']?>; border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 6px 4px 3px 4px; margin-right: 8px;"></i><?=$abs['name']?></div>
                              <div class="col-lg-2 text-right"><?=$abs['contingent']?></div>
                              <div class="col-lg-2 text-right <?=(is_int($abs['allowance']) AND intval($abs['taken'])>intval($abs['allowance']))?'text-warning':'';?>"><?=$abs['taken']?></div>
                              <div class="col-lg-2 text-right <?=(is_int($abs['allowance']) AND intval($abs['remainder'])<0)?'text-danger':'text-success';?>"><?=$abs['remainder']?></div>
                           </div>
                        <?php }
                        $subabsences = $A->getAllSub($abs['id']);
                        foreach ($subabsences as $subabs) { 
                           $summary = getAbsenceSummary($caluser,$subabs['id'],$viewData['year']);
                           $subabs['contingent'] = $summary['totalallowance'];
                           $subabs['taken'] = $summary['taken'];
                           $subabs['remainder'] = $summary['remainder'];
                           ?>
                           <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                              <div class="col-lg-1 text-right"><i class="fas fa-angle-double-right"></i></div>
                              <div class="col-lg-5 text-italic"><i class="<?=$subabs['icon']?>" style="color: #<?=$subabs['color']?>; background-color: #<?=$subabs['bgcolor']?>; border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 6px 4px 3px 4px; margin-right: 8px;"></i><?=$subabs['name']?></div>
                              <div class="col-lg-2 text-right text-italic"><?=$subabs['contingent']?></div>
                              <div class="col-lg-2 text-right  text-italic <?=(is_int($subabs['allowance']) AND intval($subabs['taken'])>intval($subabs['allowance']))?'text-warning':'';?>"><?=$subabs['taken']?></div>
                              <div class="col-lg-2 text-right  text-italic <?=(is_int($subabs['allowance']) AND intval($subabs['remainder'])<0)?'text-danger':'text-success';?>"><?=$subabs['remainder']?></div>
                           </div>
                        <?php } 
                     } 
                  } ?>
               
               </div>
            </div>

            <!-- Modal: Select User -->
            <?=createModalTop('modalSelectUser', $LANG['caledit_selUser'])?>
               <select class="form-control" name="sel_user" tabindex="<?=$tabindex++?>">
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