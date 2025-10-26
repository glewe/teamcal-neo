<?php
/**
 * Calendar View Page Menu View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
if ($viewData['month'] == 1) {
  $pageBwdYear = $viewData['year'] - 1;
  $pageBwdMonth = '12';
  $pageFwdYear = $viewData['year'];
  $pageFwdMonth = sprintf('%02d', $viewData['month'] + 1);
} elseif ($viewData['month'] == 12) {
  $pageBwdYear = $viewData['year'];
  $pageBwdMonth = sprintf('%02d', $viewData['month'] - 1);
  $pageFwdYear = $viewData['year'] + 1;
  $pageFwdMonth = '01';
} else {
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
  <a class="btn btn-outline-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $viewData['year'] . $viewData['month'] ?>&amp;region=<?= $viewData['regionid'] ?>&amp;group=<?= $viewData['groupid'] ?>&amp;abs=<?= $viewData['absid'] ?>&amp;viewmode=<?= ($viewData['viewmode'] === 'splitmonth' ? 'fullmonth' : 'splitmonth') ?>" data-bs-placement="top" data-bs-custom-class="warning" data-bs-toggle="tooltip" title="<?= ($viewData['viewmode'] === 'splitmonth' ? $LANG['cal_switchFullmonthView'] : $LANG['cal_switchSplitmonthView']) ?>"><span class="bi<?= ($viewData['viewmode'] === 'splitmonth' ? '-window-split' : '-window') ?>"></span></a>
  <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $pageBwdYear . $pageBwdMonth ?>&amp;region=<?= $viewData['regionid'] ?>&amp;group=<?= $viewData['groupid'] ?>&amp;abs=<?= $viewData['absid'] ?>" data-bs-placement="top" data-bs-custom-class="warning" data-bs-toggle="tooltip" title="<?= $LANG['cal_tt_backward'] ?>"><span class="fas fa-angle-double-left"></span></a>
  <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $pageFwdYear . $pageFwdMonth ?>&amp;region=<?= $viewData['regionid'] ?>&amp;group=<?= $viewData['groupid'] ?>&amp;abs=<?= $viewData['absid'] ?>" data-bs-placement="top" data-bs-custom-class="warning" data-bs-toggle="tooltip" title="<?= $LANG['cal_tt_forward'] ?>"><span class="fas fa-angle-double-right"></span></a>
  <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $viewData['yearToday'] . $viewData['monthToday'] ?>&amp;region=<?= $viewData['regionid'] ?>&amp;group=<?= $viewData['groupid'] ?>&amp;abs=<?= $viewData['absid'] ?>"><?= $LANG['today'] ?></a>
  <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectMonth"><?= $LANG['month'] . ': ' . $viewData['year'] . $viewData['month'] ?></button>
  <?php if ($viewData['showRegionButton']) { ?>
    <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectRegion"><?= $LANG['region'] . ': ' . $viewData['regionname'] ?></button>
  <?php } ?>
  <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectGroup"><?= $LANG['group'] . ': ' . $viewData['group'] ?></button>
  <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectAbsence"><?= $LANG['absencetype'] . ': ' . $viewData['absence'] ?></button>
  <button type="button" class="btn btn-info" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSearchUser"><?= $LANG['search'] . ': ' . $viewData['search'] ?></button>
  <button type="submit" class="btn btn-success" tabindex="<?= ++$tabindex ?>" name="btn_reset"><?= $LANG['btn_reset'] ?></button>
  <?php if ($viewData['supportMobile']) { ?>
    <button type="button" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectWidth"><?= $LANG['screen'] . ': ' . $viewData['width'] ?></button>
  <?php } ?>
  <?php if (isAllowed($CONF['controllers']['calendaredit']->permission)) { ?>
    <a class="btn btn-secondary float-end" tabindex="<?= ++$tabindex ?>" href="index.php?action=<?= $CONF['controllers']['calendaredit']->name ?>&amp;month=<?= $viewData['year'] . $viewData['month'] ?>&amp;region=<?= $viewData['regionid'] ?>&amp;user=<?= L_USER ?>"><?= $LANG['btn_cal_edit'] ?></a>
  <?php } ?>
</div>
<div style="height:20px;"></div>

<div class="card">
  <?php
  $pageHelp = '';
  if ($viewData['pageHelp']) {
    $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
  }
  ?>
  <div class="card-header text-bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers']['calendarview']->faIcon ?> fa-lg me-3"></i><?= sprintf($LANG['cal_title'], $viewData['year'], $viewData['month'], $viewData['regionname']) ?><?= $pageHelp ?></div>
</div>
<div style="height:20px;"></div>
