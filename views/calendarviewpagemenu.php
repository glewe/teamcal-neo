<?php
/**
 * calendarviewpagemenu.php
 * 
 * Calendar view page - Page menu
 *
 * @category TeamCal Neo 
 * @version 2.2.1
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"calendarviewuserrow.php: \");</script>";

if ($viewData['month']==1) 
{
   $pageBwdYear = $viewData['year'] - 1;
   $pageBwdMonth = '12'; 
   $pageFwdYear = $viewData['year']; 
   $pageFwdMonth = sprintf('%02d', $viewData['month'] + 1); 
}
elseif ($viewData['month']==12) 
{
   $pageBwdYear = $viewData['year']; 
   $pageBwdMonth = sprintf('%02d', $viewData['month'] - 1); 
   $pageFwdYear = $viewData['year'] + 1; 
   $pageFwdMonth = '01'; 
}
else 
{
   $pageBwdYear = $viewData['year']; 
   $pageFwdYear = $viewData['year']; 
   $pageBwdMonth = sprintf('%02d', $viewData['month'] - 1); 
   $pageFwdMonth = sprintf('%02d', $viewData['month'] + 1); 
}
?>

<!-- ==================================================================== 
view.calendarviewpagemenu
-->
<div class="page-menu">
   <a class="btn btn-default tooltip-warning" href="index.php?action=<?=$controller?>&amp;month=<?=$pageBwdYear.$pageBwdMonth?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>&amp;abs=<?=$viewData['absid']?>" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$LANG['cal_tt_backward']?>"><span class="fas fa-angle-double-left"></span></a>
   <a class="btn btn-default tooltip-warning" href="index.php?action=<?=$controller?>&amp;month=<?=$pageFwdYear.$pageFwdMonth?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>&amp;abs=<?=$viewData['absid']?>" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$LANG['cal_tt_forward']?>"><span class="fas fa-angle-double-right"></span></a>
   <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$viewData['yearToday'].$viewData['monthToday']?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>&amp;abs=<?=$viewData['absid']?>"><?=$LANG['today']?></a>
   <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectMonth"><?=$LANG['month'] . ': ' . $viewData['year'].$viewData['month']?></button>
   <?php if ($C->read('showRegionButton')) { ?>
      <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectRegion"><?=$LANG['region'] . ': ' . $viewData['regionname']?></button>
   <?php } ?>
   <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectGroup"><?=$LANG['group'] . ': ' . $viewData['group']?></button>
   <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectAbsence"><?=$LANG['absence'] . ': ' . $viewData['absence']?></button>
   <button type="button" class="btn btn-info" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSearchUser"><?=$LANG['search'] . ': ' . $viewData['search']?></button>
   <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" name="btn_reset"><?=$LANG['btn_reset']?></button>
   <?php if ($viewData['supportMobile']) { ?> 
      <button type="button" class="btn btn-default" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectWidth"><?=$LANG['screen'] . ': ' . $viewData['width']?></button>
   <?php } ?>
   <?php if (isAllowed($CONF['controllers']['calendaredit']->permission)) { ?>
      <a class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>" href="index.php?action=<?=$CONF['controllers']['calendaredit']->name?>&amp;month=<?=$viewData['year'].$viewData['month']?>&amp;region=<?=$viewData['regionid']?>&amp;user=<?=L_USER?>"><?=$LANG['btn_cal_edit']?></a>
   <?php } ?>
</div>

<div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
   <?php 
   $pageHelp = '';
   if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
   ?>
   <div class="panel-heading"><i class="fas <?=$CONF['controllers']['calendarview']->faIcon?> fa-lg fa-header"></i><?=sprintf($LANG['cal_title'], $viewData['year'], $viewData['month'], $viewData['regionname'])?><?=$pageHelp?></div>
</div>

