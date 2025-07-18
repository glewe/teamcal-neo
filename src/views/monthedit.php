<?php
/**
 * Month Edit View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
?>
<!-- ====================================================================
view.monthedit
-->
<div class="container content" style="padding-left: 4px; padding-right: 4px;">

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

  <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;month=<?= $viewData['year'] . $viewData['month'] ?>&amp;region=<?= $viewData['regionid'] ?>" method="post" target="_self" accept-charset="utf-8">
    <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">
    <input name="hidden_month" type="hidden" class="text" value="<?= $viewData['month'] ?>">
    <input name="hidden_region" type="hidden" class="text" value="<?= $viewData['regionid'] ?>">

    <?php
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

    <div class="page-menu">
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $pageBwdYear . $pageBwdMonth ?>&amp;region=<?= $viewData['regionid'] ?>"><span class="fas fa-angle-double-left"></span></a>
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $pageFwdYear . $pageFwdMonth ?>&amp;region=<?= $viewData['regionid'] ?>"><span class="fas fa-angle-double-right"></span></a>
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $viewData['yearToday'] . $viewData['monthToday'] ?>&amp;region=<?= $viewData['regionid'] ?>"><?= $LANG['today'] ?></a>
      <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectRegion"><?= $LANG['region'] . ': ' . $viewData['regionname'] ?></button>

      <div class="float-end">
        <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
        <button type="button" class="btn btn-danger" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalClearAll"><?= $LANG['btn_clear_all'] ?></button>
      </div>
    </div>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= sprintf($LANG['monthedit_title'], $viewData['year'], $viewData['month'], $viewData['regionname']) . $pageHelp ?></div>
    </div>
    <div style="height:20px;"></div>

    <?php
    if (!$viewData['supportMobile']) {
      $mobilecols = array( 'full' => $viewData['dateInfo']['daysInMonth'] );
    }
    foreach ($mobilecols as $key => $cols) {
      $days = $viewData['dateInfo']['daysInMonth'];
      $tables = ceil($days / $cols);
      for ($t = 0; $t < $tables; $t++) {
        $daystart = ($t * $cols) + 1;
        $daysleft = $days - ($cols * $t);
        if ($daysleft >= $cols) {
          $dayend = $daystart + ($cols - 1);
        } else {
          $dayend = $days;
        }
        ?>
        <div class="table<?= ($viewData['supportMobile']) ? $key : ''; ?>">
          <table class="table table-bordered month">
            <thead>
            <!-- Row: Month name and day numbers -->
            <tr>
              <th class="m-monthname"><?= $viewData['dateInfo']['monthname'] ?> <?= $viewData['dateInfo']['year'] ?></th>
              <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                <th class="m-daynumber text-center" <?= $viewData['dayStyles'][$i] ?>><?= $i ?></th>
              <?php } ?>
            </tr>

            <!-- Row: Weekdays -->
            <tr>
              <th class="m-label">&nbsp;</th>
              <?php for ($i = $daystart; $i <= $dayend; $i++) {
                $prop = 'wday' . $i; ?>
                <th class="m-weekday text-center" <?= $viewData['dayStyles'][$i] ?>><?= $LANG['weekdayShort'][$M->$prop] ?></th>
              <?php } ?>
            </tr>

            <?php if ($viewData['showWeekNumbers']) { ?>
              <!-- Row: Week numbers -->
              <tr>
                <th class="m-label"><?= $LANG['weeknumber'] ?></th>
                <?php for ($i = $daystart; $i <= $dayend; $i++) {
                  $prop = 'week' . $i;
                  $wprop = 'wday' . $i; ?>
                  <th class="m-weeknumber text-center<?= (($M->$wprop == 1) ? ' first' : ' inner') ?>"><?= (($M->$wprop == 1) ? $M->$prop : '') ?></th>
                <?php } ?>
              </tr>
            <?php } ?>

            <!-- Row: Daynotes -->
            <tr>
              <th class="m-label"><?= $LANG['dn_title'] ?></th>
              <?php for ($i = $daystart; $i <= $dayend; $i++) {
                $prop = 'wday' . $i;
                if ($D->get($viewData['year'] . $viewData['month'] . sprintf("%02d", $i), 'all', $viewData['regionid'], true)) {
                  $icon = 'fas fa-sticky-note';
                  $tooltip = ' data-placement="top" data-type="' . $D->color . '" data-bs-toggle="tooltip" title="' . $D->daynote . '"';
                } else {
                  $icon = 'far fa-sticky-note';
                  $tooltip = '';
                }
                ?>
                <th class="m-weekday text-center" <?= $viewData['dayStyles'][$i] ?>>
                  <a href="index.php?action=daynote&amp;date=<?= $viewData['year'] . $viewData['month'] . sprintf("%02d", $i) ?>&amp;for=all&amp;region=<?= $viewData['regionid'] ?>">
                    <i class="<?= $icon ?> text-info" <?= $tooltip ?>></i>
                  </a>
                </th>
              <?php } ?>
            </tr>

            </thead>
            <tbody>
            <!-- Rows 4ff: Holidays -->
            <?php foreach ($viewData['holidays'] as $hol) { ?>
              <tr>
                <td class="m-name"><?= $hol['name'] ?></td>
                <?php for ($i = $daystart; $i <= $dayend; $i++) {
                  $prop = 'hol' . $i; ?>
                  <td class="m-day text-center" <?= $viewData['dayStyles'][$i] ?>><input class="form-check-input" name="opt_hol_<?= $i ?>" type="radio" value="<?= $hol['id'] ?>" <?= (($M->$prop == $hol['id']) ? ' checked' : '') ?>></td>
                <?php } ?>
              </tr>
            <?php } ?>

            <!-- Last Row: Clear radio button -->
            <tr>
              <td class="m-label"><?= $LANG['monthedit_clearHoliday'] ?></td>
              <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                <td class="m-label text-center"><input class="form-check-input" name="opt_hol_<?= $i ?>" type="radio" value="0"></td>
              <?php } ?>
            </tr>
            </tbody>
          </table>
        </div>

      <?php } ?>
    <?php } ?>

    <?php
    if ($viewData['month'] == 1) {
      $pageBwdYear = $viewData['year'] - 1;
      $pageBwdMonth = 12;
      $pageFwdYear = $viewData['year'];
      $pageFwdMonth = sprintf('%02d', $viewData['month'] + 1);
    } elseif ($viewData['month'] == 12) {
      $pageBwdYear = $viewData['year'];
      $pageBwdMonth = sprintf('%02d', $viewData['month'] - 1);
      $pageFwdYear = $viewData['year'] + 1;
      $pageFwdMonth = '01';
    } else {
      $pageBwdYear = $viewData['year'];
      $pageBwdMonth = sprintf('%02d', $viewData['month'] - 1);
      $pageFwdYear = $viewData['year'];
      $pageFwdMonth = sprintf('%02d', $viewData['month'] + 1);
    }
    ?>

    <!-- Modal: Clear All -->
    <?= createModalTop('modalClearAll', $LANG['modal_confirm']) ?>
    <?= sprintf($LANG['monthedit_confirm_clearall'], $viewData['year'], $viewData['month'], $viewData['regionname']) ?>
    <?= createModalBottom('btn_clearall', 'success', $LANG['btn_clear_all']) ?>

    <!-- Modal: Select Region -->
    <?= createModalTop('modalSelectRegion', $LANG['monthedit_selRegion']) ?>
    <select id="region" class="form-select" name="sel_region" tabindex="<?= ++$tabindex ?>">
      <?php foreach ($viewData['regions'] as $reg) { ?>
        <option value="<?= $reg['id'] ?>" <?= (($viewData['regionid'] == $reg['id']) ? ' selected="selected"' : '') ?>><?= $reg['name'] ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_region', 'success', $LANG['btn_select']) ?>

  </form>
</div>
