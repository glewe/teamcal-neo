<?php
/**
 * Calendar View View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
$formLink = 'index.php?action=' . $controller . '&amp;month=' . $viewData['year'] . $viewData['month'] . '&amp;region=' . $viewData['regionid'] . '&amp;group=' . $viewData['groupid'] . '&amp;abs=' . $viewData['absid'];
?>
<!-- ====================================================================
view.calendarview
-->
<div class="container content" style="<?= ($fontSize = $C->read('calendarFontSize')) ? "font-size:" . $fontSize . "%;" : ""; ?>">

  <?php if ($viewData['calendaronly']) {

    $formLink = $formLink . '&amp;calendaronly=1';
    foreach ($viewData['months'] as $vmonth) {
      $M = $vmonth['M'];
      $viewData['month'] = $vmonth['month'];
      $viewData['year'] = $vmonth['year'];
      $viewData['dateInfo'] = $vmonth['dateInfo'];
      $viewData['dayStyles'] = $vmonth['dayStyles'];
      $viewData['businessDays'] = $vmonth['businessDays'];
      require "calendarviewmonth.php";
    }
    ?>

  <?php } else { ?>

    <?php
    if (
      ($showAlert && $C->read("showAlerts") != "none") &&
      ($C->read("showAlerts") == "all" || $C->read("showAlerts") == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
    ) {
      echo createAlertBox($alertData);
    }
    $tabindex = 0;
    $colsleft = 1;
    $colsright = 4;
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="<?= $formLink ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">
      <input name="hidden_month" type="hidden" class="text" value="<?= $viewData['month'] ?>">
      <input name="hidden_region" type="hidden" class="text" value="<?= $viewData['regionid'] ?>">
      <input name="hidden_showmonths" type="hidden" class="text" value="<?= $showMonths ?>">
      <?php
      require_once "calendarviewpagemenu.php";
      foreach ($viewData['months'] as $vmonth) {
        $M = $vmonth['M'];
        $viewData['month'] = $vmonth['month'];
        $viewData['year'] = $vmonth['year'];
        $viewData['dateInfo'] = $vmonth['dateInfo'];
        $viewData['dayStyles'] = $vmonth['dayStyles'];
        $viewData['businessDays'] = $vmonth['businessDays'];
        require "calendarviewmonth.php";
      }
      ?>
      <div style="text-align: right;">
        <?php if ($showMonths < 12) { ?>
          <button type="submit" name="btn_onemore" class="btn btn-secondary" data-bs-placement="top" data-bs-custom-class="secondary" data-bs-toggle="tooltip" title="<?= $LANG['cal_tt_onemore'] ?>"><span class="fas fa-plus"></span></button>
        <?php } ?>
        <?php if ($showMonths > 1) { ?>
          <button type="submit" name="btn_oneless" class="btn btn-secondary" style="margin-right:4px;" data-bs-placement="tooltip-top" data-bs-custom-class="secondary" data-bs-toggle="tooltip" title="<?= $LANG['cal_tt_oneless'] ?>"><span class="fas fa-minus"></span></button>
        <?php } ?>
      </div>

      <!-- Modal: Select Month -->
      <?= createModalTop('modalSelectMonth', $LANG['cal_selMonth']) ?>
      <div style="width:48%;float:left;">
        <?= $LANG['year'] ?><br>
        <input id="year" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_year" type="number" min="2000" max="2100" value="<?= $viewData['year'] ?>">
      </div>
      <div style="width:45%;float:right;">
        <?= $LANG['month'] ?><br>
        <select id="month" class="form-select" name="sel_month" tabindex="<?= ++$tabindex ?>">
          <?php foreach ($LANG['monthnames'] as $key => $value) { ?>
            <option value="<?= sprintf('%02d', $key) ?>" <?= ($key == $viewData['month']) ? 'selected="selected"' : ''; ?>><?= $value ?></option>
          <?php } ?>
        </select>
      </div>
      <div style="height:80px;"></div>
      <?= createModalBottom('btn_month', 'success', $LANG['btn_select']) ?>

      <!-- Modal: Select Region -->
      <?= createModalTop('modalSelectRegion', $LANG['cal_selRegion']) ?>
      <select id="region" class="form-select" name="sel_region" tabindex="<?= ++$tabindex ?>">
        <?php foreach ($viewData['regions'] as $reg) { ?>
          <option value="<?= $reg['id'] ?>" <?= (($viewData['regionid'] == $reg['id']) ? ' selected="selected"' : '') ?>><?= $reg['name'] ?></option>
        <?php } ?>
      </select>
      <?= createModalBottom('btn_region', 'success', $LANG['btn_select']) ?>

      <!-- Modal: Select Group -->
      <?= createModalTop('modalSelectGroup', $LANG['cal_selGroup']) ?>
      <select id="group" class="form-select" name="sel_group" tabindex="<?= ++$tabindex ?>">
        <option value="all" <?= (($viewData['groupid'] == 'all') ? ' selected="selected"' : '') ?>><?= $LANG['all'] ?></option>
        <?php foreach ($viewData['allGroups'] as $grp) {
          if (isAllowed("calendarviewall") || ($UG->isMemberOrManagerOfGroup($UL->username, $grp['id']))) { ?>
            <option value="<?= $grp['id'] ?>" <?= (($viewData['groupid'] == $grp['id']) ? ' selected="selected"' : '') ?>><?= $grp['name'] ?></option>
          <?php }
        } ?>
      </select>
      <?= createModalBottom('btn_group', 'success', $LANG['btn_select']) ?>

      <!-- Modal: Select Absence -->
      <?= createModalTop('modalSelectAbsence', $LANG['cal_selAbsence']) ?>
      <p><?= $LANG['cal_selAbsence_comment'] ?></p>
      <select id="absence" class="form-select" name="sel_absence" tabindex="<?= ++$tabindex ?>">
        <option value="all" <?= (($viewData['absid'] == 'all') ? 'selected="selected"' : '') ?>><?= $LANG['all'] ?></option>
        <?php foreach ($viewData['absences'] as $abs) { ?>
          <option value="<?= $abs['id'] ?>" <?= (($viewData['absid'] == $abs['id']) ? ' selected="selected"' : '') ?>><?= $abs['name'] ?></option>
        <?php } ?>
      </select>
      <?= createModalBottom('btn_abssearch', 'warning', $LANG['btn_search']) ?>

      <!-- Modal: Screen Width -->
      <?= createModalTop('modalSelectWidth', $LANG['cal_selWidth']) ?>
      <p><?= $LANG['cal_selWidth_comment'] ?></p>
      <select id="width" class="form-select" name="sel_width" tabindex="<?= ++$tabindex ?>">
        <?php foreach ($LANG['widths'] as $key => $value) { ?>
          <option value="<?= $key ?>" <?= (($viewData['width'] == $key) ? ' selected="selected"' : '') ?>><?= $value ?></option>
        <?php } ?>
      </select>
      <?= createModalBottom('btn_width', 'warning', $LANG['btn_select']) ?>

      <!-- Modal: Search User -->
      <div class="modal fade" id="modalSearchUser" role="dialog" aria-labelledby="modalSearchUserLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><?= $LANG['cal_search'] ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input id="search" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_search" type="text" value="<?= $viewData["search"] ?>">
              <?php if (isset($inputAlert["search"])) { ?>
                <br>
                <div class="alert alert-dismissable alert-danger fade show" role="alert">
                  <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                  <?= $inputAlert['search'] ?>
                </div>
              <?php } ?>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info" tabindex="<?= ++$tabindex ?>" name="btn_search" style="margin-top: 4px;"><?= $LANG['btn_search'] ?></button>
              <?php if (strlen($viewData["search"])) { ?>
                <button type="submit" class="btn btn-danger" tabindex="<?= ++$tabindex ?>" name="btn_search_clear"><?= $LANG['btn_clear'] ?></button>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>

    </form>

  <?php } // end: if calendaronly ?>


  <?php if ($limit = $C->read("usersPerPage")) { ?>
    <nav aria-label="Paging">
      <ul class="pagination">

        <!-- First Page Link -->
        <?php if ($page == 1) { ?>
          <li class="disabled"><span><span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span></span></li>
        <?php } else { ?>
          <li><a href="<?= $formLink ?>&amp;page=1" title="<?= $LANG['page_first'] ?>"><span><i class="fas fa-angle-double-left"></i></span></a></li>
        <?php } ?>

        <!-- Previous Page Link -->
        <?php if ($page == 1) { ?>
          <li class="disabled"><span><span aria-hidden="true"><i class="fas fa-angle-left"></i></span></span></li>
        <?php } else { ?>
          <li><a href="<?= $formLink ?>&amp;page=<?= $page - 1 ?>" title="<?= $LANG['page_prev'] ?>"><span><i class="fas fa-angle-left"></i></span></a></li>
        <?php } ?>

        <!-- Page Link -->
        <?php for ($p = 1; $p <= $pages; $p++) {
          if ($p == $page) { ?>
            <li class="active"><span><?= $p ?><span class="sr-only">(current)</span></span></li>
          <?php } else { ?>
            <li><a href="<?= $formLink ?>&amp;page=<?= $p ?>" title="<?= sprintf($LANG['page_page'], $p) ?>"><span><?= $p ?></span></a></li>
          <?php }
        } ?>

        <!-- Next Page Link -->
        <?php if ($page == $pages) { ?>
          <li class="disabled"><span><span aria-hidden="true"><i class="fas fa-angle-right"></i></span></span></li>
        <?php } else { ?>
          <li><a href="<?= $formLink ?>&amp;page=<?= $page + 1 ?>" title="<?= $LANG['page_next'] ?>"><span><i class="fas fa-angle-right"></i></span></a></li>
        <?php } ?>

        <!-- Last Page Link -->
        <?php if ($page == $pages) { ?>
          <li class="disabled"><span><span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span></span></li>
        <?php } else { ?>
          <li><a href="<?= $formLink ?>&amp;page=<?= $pages ?>" title="<?= $LANG['page_last'] ?>"><span><i class="fas fa-angle-double-right"></i></span></a></li>
        <?php } ?>

      </ul>
    </nav>
  <?php } ?>

</div>
