<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Group Calendar Edit View
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
view.groupcalendaredit
-->
<div class="container content" style="padding-left: 4px; padding-right: 4px;">

  <?php
  if (
    ($showAlert && $C->read("showAlerts") != "none") &&
    ($C->read("showAlerts") == "all" || $C->read("showAlerts") == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
  ) {
    echo createAlertBox($alertData);
  }
  $tabindex = 1;
  $colsleft = 1;
  $colsright = 4;
  ?>

  <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;month=<?= $viewData['year'] . $viewData['month'] ?>&amp;region=<?= $viewData['regionid'] ?>&amp;group=<?= $viewData['groupid'] ?>" method="post" target="_self" accept-charset="utf-8">

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
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $pageBwdYear . $pageBwdMonth ?>&amp;region=<?= $viewData['regionid'] ?>&amp;group=<?= $viewData['groupid'] ?>"><span class="fas fa-angle-double-left"></span></a>
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $pageFwdYear . $pageFwdMonth ?>&amp;region=<?= $viewData['regionid'] ?>&amp;group=<?= $viewData['groupid'] ?>"><span class="fas fa-angle-double-right"></span></a>
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $viewData['yearToday'] . $viewData['monthToday'] ?>&amp;region=<?= $viewData['regionid'] ?>&amp;group=<?= $viewData['groupid'] ?>"><?= $LANG['today'] ?></a>
      <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalPeriod"><?= $LANG['caledit_Period'] ?></button>
      <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalRecurring"><?= $LANG['caledit_Recurring'] ?></button>
      <button type="button" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalSelectRegion"><?= $LANG['region'] . ': ' . $viewData['regionname'] ?></button>
      <button type="button" class="btn btn-success" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalSelectGroup"><?= $LANG['group'] . ': ' . $viewData['groupname'] ?></button>
      <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalSave"><?= $LANG['btn_save'] ?></button>
      <button type="button" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalClearAll"><?= $LANG['btn_clear_all'] ?></button>
      <?php if ($viewData['supportMobile']) { ?>
        <button type="button" class="btn btn-secondary" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalSelectWidth"><?= $LANG['screen'] . ': ' . $viewData['width'] ?></button>
      <?php } ?>
    </div>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><?= sprintf($LANG['caledit_title'], $viewData['year'], $viewData['month'], $LANG['group'] . ': ' . $viewData['groupname']) ?><?= $pageHelp ?></div>
    </div>
    <div style="height:20px;"></div>

    <?php if (!$viewData['supportMobile']) {
      $mobilecols = array( 'full' => $viewData['dateInfo']['daysInMonth'] );
    } else {
      switch ($viewData['width']) {
        case '1024plus':
          $mobilecols = array( 'full' => $viewData['dateInfo']['daysInMonth'] );
          break;

        case '1024':
          $mobilecols = array( '1024' => 25 );
          break;

        case '800':
          $mobilecols = array( '800' => 17 );
          break;

        case '640':
          $mobilecols = array( '640' => 14 );
          break;

        case '480':
          $mobilecols = array( '480' => 9 );
          break;

        case '400':
          $mobilecols = array( '400' => 7 );
          break;

        case '320':
          $mobilecols = array( '320' => 5 );
          break;

        case '240':
          $mobilecols = array( '240' => 3 );
          break;

        default:
          $mobilecols = array( 'full' => $viewData['dateInfo']['daysInMonth'] );
          break;
      }
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
                  <th class="m-weeknumber text-center<?= (($M->$wprop == $viewData['firstDayOfWeek']) ? ' first' : ' inner') ?>"><?= (($M->$wprop == $viewData['firstDayOfWeek']) ? $M->$prop : '') ?></th>
                <?php } ?>
              </tr>
            <?php } ?>

            </thead>
            <tbody>

            <!-- Rows: Current absence -->
            <tr>
              <td class="m-label"><?= $LANG['caledit_currentAbsence'] ?></td>
              <?php
              for ($i = $daystart; $i <= $dayend; $i++) {
                $style = $viewData['dayStyles'][$i];
                $icon = '';
                if ($abs = $T->getAbsence($viewData['groupusername'], $viewData['year'], $viewData['month'], $i)) {
                  /**
                   * This is an absence. Get the coloring info.
                   */
                  $style = ' style="color: #' . $A->getColor($abs) . '; background-color: #' . $A->getBgColor($abs) . ';';
                  if ($C->read('symbolAsIcon')) {
                    $icon = $A->getSymbol($abs);
                  } else {
                    $icon = '<span class="' . $A->getIcon($abs) . '"></span>';
                  }
                  $loopDate = date('Y-m-d', mktime(0, 0, 0, $viewData['month'], $i, $viewData['year']));
                  if ($loopDate == $currDate) {
                    $style .= 'border-left: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';border-right: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
                  }
                  $style .= '"';
                }
                ?>
                <td class="m-day text-center" <?= $style ?>><?= $icon ?></td>
              <?php } ?>
            </tr>

            <!-- Rows ff: Absences -->
            <?php foreach ($viewData['absences'] as $abs) {
              if (($abs['manager_only'] && ($UG->isGroupManagerOfGroup($UL->username, $viewData['groupid']) || $UL->username == 'admin')) || !$abs['manager_only']) { ?>
                <tr>
                  <td class="m-name"><?= $abs['name'] ?></td>
                  <?php
                  for ($i = $daystart; $i <= $dayend; $i++) {
                    $prop = 'abs' . $i;
                    ?>
                    <td class="m-day text-center" <?= $viewData['dayStyles'][$i] ?>><input name="opt_abs_<?= $i ?>" type="radio" value="<?= $abs['id'] ?>" <?= (($T->$prop == $abs['id']) ? ' checked' : '') ?>></td>
                  <?php } ?>
                </tr>
              <?php }
            } ?>

            <!-- Last Row: Clear radio button -->
            <tr>
              <td class="m-label"><?= $LANG['caledit_clearAbsence'] ?></td>
              <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                <td class="m-label text-center"><input name="opt_abs_<?= $i ?>" type="radio" value="0"></td>
              <?php } ?>
            </tr>
            </tbody>
          </table>
        </div>

      <?php } ?>
    <?php } ?>

    <!-- Modal: Save -->
    <?= createModalTop('modalSave', $LANG['modal_confirm']) ?>
    <?= sprintf($LANG['caledit_confirm_savegroup'], $viewData['year'], $viewData['month'], $viewData['groupname']) ?>
    <div class="checkbox">
      <label><input type="checkbox" name="chk_keepExisting" tabindex="<?= $tabindex++ ?>" checked><?= $LANG['caledit_keepExisting'] ?></label>
    </div>
    <?= createModalBottom('btn_save', 'primary', $LANG['btn_save']) ?>

    <!-- Modal: Clear All -->
    <?= createModalTop('modalClearAll', $LANG['modal_confirm']) ?>
    <?= sprintf($LANG['caledit_confirm_clearall'], $viewData['year'], $viewData['month'], $LANG['group'] . ': ' . $viewData['groupname']) ?>
    <?= createModalBottom('btn_clearall', 'success', $LANG['btn_clear_all']) ?>

    <!-- Modal: Select Region -->
    <?= createModalTop('modalSelectRegion', $LANG['cal_selRegion']) ?>
    <select id="region" class="form-control" name="sel_region" tabindex="<?= $tabindex++ ?>">
      <?php foreach ($viewData['regions'] as $reg) { ?>
        <option value="<?= $reg['id'] ?>" <?= (($viewData['regionid'] == $reg['id']) ? 'selected="selected"' : '') ?>><?= $reg['name'] ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_region', 'success', $LANG['btn_select']) ?>

    <!-- Modal: Screen Width -->
    <?= createModalTop('modalSelectWidth', $LANG['cal_selWidth']) ?>
    <p><?= $LANG['cal_selWidth_comment'] ?></p>
    <select id="width" class="form-control" name="sel_width" tabindex="<?= $tabindex++ ?>">
      <?php foreach ($LANG['widths'] as $key => $value) { ?>
        <option value="<?= $key ?>" <?= (($viewData['width'] == $key) ? ' selected="selected"' : '') ?>><?= $value ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_width', 'warning', $LANG['btn_select']) ?>

    <!-- Modal: Select Group -->
    <?= createModalTop('modalSelectGroup', $LANG['caledit_selGroup']) ?>
    <select id="group" class="form-control" name="sel_group" tabindex="<?= $tabindex++ ?>">
      <?php foreach ($viewData['groups'] as $group) { ?>
        <option value="<?= $group['id'] ?>" <?= (($viewData['groupid'] == $group['id']) ? ' selected="selected"' : '') ?>><?= $group['name'] ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_group', 'success', $LANG['btn_select']) ?>

    <!-- Modal: Period -->
    <?= createModalTop('modalPeriod', $LANG['caledit_PeriodTitle']) ?>
    <div class="row">
      <div class="col-lg-7" style="margin-bottom: 20px;">
        <span class="text-bold"><?= $LANG['caledit_absenceType'] ?></span><br>
        <span class="text-normal"><?= $LANG['caledit_absenceType_comment'] ?></span>
      </div>
      <div class="col-lg-5" style="margin-bottom: 20px;">
        <select id="user" class="form-control" name="sel_periodAbsence" tabindex="<?= $tabindex++ ?>">
          <?php foreach ($viewData['absences'] as $abs) { ?>
            <option value="<?= $abs['id'] ?>"><?= $abs['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div>&nbsp;</div>
    <div class="row">
      <div class="col-lg-7" style="margin-bottom: 20px;">
        <span class="text-bold"><?= $LANG['caledit_startDate'] ?></span><br>
        <span class="text-normal"><?= $LANG['caledit_startDate_comment'] ?></span>
      </div>
      <div class="col-lg-5" style="margin-bottom: 20px;">
        <input id="periodStart" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_periodStart" type="text" maxlength="10" value="">
        <script>
          $(function () {
            $("#periodStart").datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: "yy-mm-dd"
            });
          });
          // Make drop downs work in modal dialogs. Needed once on page.
          var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
          $.fn.modal.Constructor.prototype.enforceFocus = function () {
          };
        </script>
      </div>
      <?php if (isset($inputAlert["periodStart"]) && strlen($inputAlert["periodStart"])) { ?>
        <br>
        <div class="alert alert-dismissable alert-danger">
          <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['periodStart'] ?></div>
      <?php } ?>
    </div>
    <div>&nbsp;</div>
    <div class="row">
      <div class="col-lg-7" style="margin-bottom: 20px;">
        <span class="text-bold"><?= $LANG['caledit_endDate'] ?></span><br>
        <span class="text-normal"><?= $LANG['caledit_endDate_comment'] ?></span>
      </div>
      <div class="col-lg-5" style="margin-bottom: 20px;">
        <input id="periodEnd" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_periodEnd" type="text" maxlength="10" value="">
        <script>
          $(function () {
            $("#periodEnd").datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: "yy-mm-dd"
            });
          });
        </script>
      </div>
      <?php if (isset($inputAlert["periodEnd"]) && strlen($inputAlert["periodEnd"])) { ?>
        <br>
        <div class="alert alert-dismissable alert-danger">
          <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['periodEnd'] ?></div>
      <?php } ?>
    </div>
    <?= createModalBottom('btn_saveperiod', 'success', $LANG['btn_save']) ?>

    <!-- Modal: Recurring -->
    <?= createModalTop('modalRecurring', $LANG['caledit_RecurringTitle']) ?>
    <div class="row">
      <div class="col-lg-7" style="margin-bottom: 20px;">
        <span class="text-bold"><?= $LANG['caledit_absenceType'] ?></span><br>
        <span class="text-normal"><?= $LANG['caledit_absenceType_comment'] ?></span>
      </div>
      <div class="col-lg-5" style="margin-bottom: 20px;">
        <select id="user" class="form-control" name="sel_recurringAbsence" tabindex="<?= $tabindex++ ?>">
          <?php foreach ($viewData['absences'] as $abs) { ?>
            <option value="<?= $abs['id'] ?>"><?= $abs['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div>&nbsp;</div>
    <div>
      <span class="text-bold"><?= $LANG['caledit_recurrence'] ?></span><br>
      <span class="text-normal"><?= $LANG['caledit_recurrence_comment'] ?></span>
    </div>
    <div>&nbsp;</div>
    <div class="row">
      <div class="col-lg-5">
        <div class="checkbox"><input id="monday" name="monday" value="monday" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['weekdayLong'][1] ?></div>
        <div class="checkbox"><input id="tuesday" name="tuesday" value="tuesday" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['weekdayLong'][2] ?></div>
        <div class="checkbox"><input id="wedensday" name="wednesday" value="wednesday" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['weekdayLong'][3] ?></div>
        <div class="checkbox"><input id="thursday" name="thursday" value="thursday" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['weekdayLong'][4] ?></div>
        <div class="checkbox"><input id="friday" name="friday" value="friday" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['weekdayLong'][5] ?></div>
      </div>
      <div class="col-lg-5">
        <div class="checkbox"><input id="saturday" name="saturday" value="saturday" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['weekdayLong'][6] ?></div>
        <div class="checkbox"><input id="sunday" name="sunday" value="sunday" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['weekdayLong'][7] ?></div>
        <div class="checkbox"><input id="workdays" name="workdays" value="workdays" tabindex="<?= $tabindex++ ?>" type="checkbox">Mon-Fri</div>
        <div class="checkbox"><input id="weekends" name="weekends" value="weekends" tabindex="<?= $tabindex++ ?>" type="checkbox">Sat-Sun</div>
      </div>
    </div>
    <div>&nbsp;</div>
    <?= createModalBottom('btn_saverecurring', 'success', $LANG['btn_save']) ?>

  </form>

</div>
