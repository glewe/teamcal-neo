<?php

/**
 * Calendar Edit View
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
view.editcalendar
-->
<div class="container content" style="padding-left: 4px; padding-right: 4px;">

  <?php
  if (
    ($showAlert && $viewData['showAlerts'] != "none") &&
    ($viewData['showAlerts'] == "all" || $viewData['showAlerts'] == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
  ) {
    echo createAlertBox($alertData);
  }
  $tabindex = 0;
  $colsleft = 1;
  $colsright = 4;
  ?>

  <form class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;month=<?= $viewData['year'] . $viewData['month'] ?>&amp;region=<?= $viewData['regionid'] ?>&amp;user=<?= $viewData['username'] ?>" method="post" target="_self" accept-charset="utf-8">
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
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $pageBwdYear . $pageBwdMonth ?>&amp;region=<?= $viewData['regionid'] ?>&amp;user=<?= $viewData['username'] ?>"><span class="fas fa-angle-double-left"></span></a>
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $pageFwdYear . $pageFwdMonth ?>&amp;region=<?= $viewData['regionid'] ?>&amp;user=<?= $viewData['username'] ?>"><span class="fas fa-angle-double-right"></span></a>
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>&amp;month=<?= $viewData['yearToday'] . $viewData['monthToday'] ?>&amp;region=<?= $viewData['regionid'] ?>&amp;user=<?= $viewData['username'] ?>"><?= $LANG['today'] ?></a>
      <button type="button" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalPattern"><?= $LANG['caledit_Pattern'] ?></button>
      <button type="button" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalPeriod"><?= $LANG['caledit_Period'] ?></button>
      <button type="button" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalRecurring"><?= $LANG['caledit_Recurring'] ?></button>
      <?php if ($viewData['showRegionButton']) { ?>
        <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectRegion"><?= $LANG['region'] . ': ' . $viewData['regionname'] ?></button>
      <?php } ?>
      <button type="button" class="btn btn-success" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectUser"><?= $LANG['user'] . ': ' . $viewData['fullname'] ?></button>
      <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
      <button type="button" class="btn btn-danger" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalClearAll"><?= $LANG['btn_clear_all'] ?></button>
      <?php if ($viewData['supportMobile']) { ?>
        <button type="button" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalSelectWidth"><?= $LANG['screen'] . ': ' . $viewData['width'] ?></button>
      <?php } ?>
      <a href="index.php?action=calendarview&rand=<?= rand(100, 9999) ?>" class="btn btn-secondary float-end" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_showcalendar'] ?></a>
    </div>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($viewData['pageHelp']) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header text-bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= sprintf($LANG['caledit_title'], $viewData['year'], $viewData['month'], $viewData['fullname']) . $viewData['groupnames'] ?><?= $pageHelp ?></div>
    </div>
    <div style="height:20px;"></div>

    <?php if (!$viewData['supportMobile']) {
      $mobilecols = array('full' => $viewData['dateInfo']['daysInMonth']);
    } else {
      switch ($viewData['width']) {
        case '1024plus':
          $mobilecols = array('full' => $viewData['dateInfo']['daysInMonth']);
          break;

        case '1024':
          $mobilecols = array('1024' => 25);
          break;

        case '800':
          $mobilecols = array('800' => 17);
          break;

        case '640':
          $mobilecols = array('640' => 14);
          break;

        case '480':
          $mobilecols = array('480' => 9);
          break;

        case '400':
          $mobilecols = array('400' => 7);
          break;

        case '320':
          $mobilecols = array('320' => 5);
          break;

        case '240':
          $mobilecols = array('240' => 3);
          break;

        default:
          $mobilecols = array('full' => $viewData['dateInfo']['daysInMonth']);
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
                  <th class="m-daynumber text-center" <?= $viewData['calendarDays'][$i]['style'] ?>><?= $i ?></th>
                <?php } ?>
              </tr>

              <!-- Row: Weekdays -->
              <tr>
                <th class="m-label">&nbsp;</th>
                <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                  <th class="m-weekday text-center" <?= $viewData['calendarDays'][$i]['style'] ?>><?= $LANG['weekdayShort'][$viewData['calendarDays'][$i]['weekday']] ?></th>
                <?php } ?>
              </tr>

              <?php if ($viewData['showWeekNumbers']) { ?>
                <!-- Row: Week numbers -->
                <tr>
                  <th class="m-label"><?= $LANG['weeknumber'] ?></th>
                  <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                    <th class="m-weeknumber text-center<?= ($viewData['calendarDays'][$i]['isFirstDayOfWeek'] ? ' first' : ' inner') ?>"><?= ($viewData['calendarDays'][$i]['isFirstDayOfWeek'] ? $viewData['calendarDays'][$i]['weeknum'] : '') ?></th>
                  <?php } ?>
                </tr>
              <?php } ?>

              <!-- Row: Daynotes -->
              <tr>
                <th class="m-label"><?= $LANG['dn_title'] ?></th>
                <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                  <th class="m-weekday text-center" <?= $viewData['calendarDays'][$i]['style'] ?>>
                    <a href="index.php?action=daynote&amp;date=<?= $viewData['calendarDays'][$i]['date'] ?>&amp;for=<?= $viewData['username'] ?>&amp;region=<?= $viewData['regionid'] ?>">
                      <i class="<?= $viewData['calendarDays'][$i]['daynoteIcon'] ?> text-info" <?= $viewData['calendarDays'][$i]['daynoteTooltip'] ?>></i>
                    </a>
                  </th>
                <?php } ?>
              </tr>

            </thead>
            <tbody>

              <!-- Rows: Current absence -->
              <tr>
                <td class="m-label"><?= $LANG['caledit_currentAbsence'] ?></td>
                <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                  <?php $day = $viewData['calendarDays'][$i]; ?>
                  <?php $style = $day['absenceStyle']; ?>
                  <?php $icon = $day['absenceIcon']; ?>
                  <td class="m-day text-center align-middle" <?= $style ?>><?= $icon ?></td>
                <?php } ?>
              </tr>

              <!-- Rows ff: Absences -->
              <?php foreach ($viewData['absenceRows'] as $row) { ?>
                <tr>
                  <td class="m-name"><?= $row['name'] ?></td>
                  <?php foreach ($row['days'] as $day) { ?>
                    <td class="m-day text-center" <?= $day['style'] ?>>
                      <input class="form-check-input" name="opt_abs_<?= $day['num'] ?>" type="radio" value="<?= $row['id'] ?>" <?= $day['checked'] ? 'checked' : '' ?>>
                    </td>
                  <?php } ?>
                </tr>
              <?php } ?>

              <!-- Last Row: Clear radio button -->
              <tr>
                <td class="m-label"><?= $LANG['caledit_clearAbsence'] ?></td>
                <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                  <td class="m-label text-center"><input class="form-check-input" name="opt_abs_<?= $i ?>" type="radio" value="0"></td>
                <?php } ?>
              </tr>

              <?php if ($viewData['takeover'] && $UL->username != $viewData['username']) { ?>
                <!-- Take over row -->
                <tr>
                  <td class="m-label">Take over</td>
                  <?php for ($i = $daystart; $i <= $dayend; $i++) { ?>
                    <td class="m-label text-center"><input class="form-check-input" name="opt_abs_<?= $i ?>" type="radio" value="takeover"></td>
                  <?php } ?>
                </tr>
              <?php } ?>

            </tbody>
          </table>
        </div>

      <?php } ?>
    <?php } ?>

    <!-- Modal: Clear All -->
    <?= createModalTop('modalClearAll', $LANG['modal_confirm']) ?>
    <?= sprintf($LANG['caledit_confirm_clearall'], $viewData['year'], $viewData['month'], $viewData['fullname']) ?>
    <div class="form-check mt-3">
      <input id="clearAbsences" type="checkbox" class="form-check-input" name="chk_clearAbsences" tabindex="<?= ++$tabindex ?>">
      <label for="clearAbsences" class="fw-bold"><?= $LANG['caledit_clearAbsences'] ?></label>
    </div>
    <?php if (isAllowed($CONF['controllers']['daynote']->permission) || isAllowed('daynoteglobal')) { ?>
      <div class="form-check">
        <input id="clearDaynotes" type="checkbox" class="form-check-input" name="chk_clearDaynotes" tabindex="<?= ++$tabindex ?>">
        <label for="clearDaynotes" class="fw-bold"><?= $LANG['caledit_clearDaynotes'] ?></label>
      </div>
    <?php } ?>
    <?= createModalBottom('btn_clearall', 'success', $LANG['btn_clear_all']) ?>

    <!-- Modal: Select Region -->
    <?= createModalTop('modalSelectRegion', $LANG['cal_selRegion']) ?>
    <select class="form-select" name="sel_region" tabindex="<?= ++$tabindex ?>">
      <?php foreach ($viewData['regions'] as $reg) { ?>
        <option value="<?= $reg['id'] ?>" <?= (($viewData['regionid'] == $reg['id']) ? 'selected="selected"' : '') ?>><?= $reg['name'] ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_region', 'success', $LANG['btn_select']) ?>

    <!-- Modal: Screen Width -->
    <?= createModalTop('modalSelectWidth', $LANG['cal_selWidth']) ?>
    <p><?= $LANG['cal_selWidth_comment'] ?></p>
    <select class="form-select" name="sel_width" tabindex="<?= ++$tabindex ?>">
      <?php foreach ($LANG['widths'] as $key => $value) { ?>
        <option value="<?= $key ?>" <?= (($viewData['width'] == $key) ? ' selected="selected"' : '') ?>><?= $value ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_width', 'warning', $LANG['btn_select']) ?>

    <!-- Modal: Select User -->
    <?= createModalTop('modalSelectUser', $LANG['caledit_selUser']) ?>
    <select class="form-select" name="sel_user" tabindex="<?= ++$tabindex ?>">
      <?php foreach ($viewData['users'] as $usr) { ?>
        <option value="<?= $usr['username'] ?>" <?= (($viewData['username'] == $usr['username']) ? ' selected="selected"' : '') ?>><?= $usr['lastfirst'] ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_user', 'success', $LANG['btn_select']) ?>

    <!-- Modal: Pattern -->
    <?= createModalTop('modalPattern', $LANG['caledit_PatternTitle'], 'lg') ?>
    <div class="row">
      <div class="col-lg-7">
        <label for="absencePattern" class="text-bold"><?= $LANG['caledit_absencePattern'] ?></label><br>
        <span class="text-normal"><?= $LANG['caledit_absencePattern_comment'] ?></span>
      </div>
      <div class="col-lg-5">
        <select class="form-select" id="absencePattern" name="sel_absencePattern" tabindex="<?= ++$tabindex ?>" onchange="showPattern(this.value)">
          <?php foreach ($viewData['patterns'] as $ptn) { ?>
            <option value="<?= $ptn['id'] ?>"><?= $ptn['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="mt-4">
      <?php foreach ($viewData['patterns'] as $ptn) { ?>
        <div id="pattern-<?= $ptn['id'] ?>" class="col">
          <?= createPatternTable($ptn['id']) ?>
        </div>
      <?php } ?>
      <script>
        function hideAllPatterns() {
          <?php foreach ($viewData['patterns'] as $ptn) { ?>
            document.getElementById('pattern-<?= $ptn['id'] ?>').style.display = "none";
          <?php } ?>
        }

        function showPattern(id) {
          hideAllPatterns();
          document.getElementById('pattern-' + id).style.display = "block";
        }

        //
        // Show first pattern on load
        //
        showPattern(<?= $viewData['patterns'][0]['id'] ?>);
      </script>
    </div>
    <div class="row mt-4">
      <div class="col-lg-7">
        <label for="absencePatternSkipHolidays" class="text-bold"><?= $LANG['caledit_absencePatternSkipHolidays'] ?></label><br>
        <span class="text-normal"><?= $LANG['caledit_absencePatternSkipHolidays_comment'] ?></span>
      </div>
      <div class="col-lg-5">
        <div class="form-check">
          <input id="absencePatternSkipHolidays" name="chk_absencePatternSkipHolidays" value="1" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="flexCheckDefault">
            <?= $LANG['caledit_absencePatternSkipHolidays'] ?>
          </label>
        </div>
      </div>
    </div>
    <?= createModalBottom('btn_savepattern', 'success', $LANG['btn_save']) ?>

    <!-- Modal: Period -->
    <?= createModalTop('modalPeriod', $LANG['caledit_PeriodTitle'], 'lg') ?>
    <div class="row">
      <div class="col-lg-7">
        <label for="periodAbsence" class="text-bold"><?= $LANG['caledit_absenceType'] ?></label><br>
        <span class="text-normal"><?= $LANG['caledit_absenceType_comment'] ?></span>
      </div>
      <div class="col-lg-5">
        <select class="form-select" id="periodAbsence" name="sel_periodAbsence" tabindex="<?= ++$tabindex ?>">
          <?php foreach ($viewData['absences'] as $abs) {
            if (($abs['manager_only'] && ($UG->isGroupManagerOfUser($UL->username, $viewData['username']) || $UL->username == 'admin')) || !$abs['manager_only']) { ?>
              <option value="<?= $abs['id'] ?>"><?= $abs['name'] ?></option>
          <?php }
          } ?>
        </select>
      </div>
    </div>
    <div>&nbsp;</div>
    <div class="row">
      <div class="col-lg-7">
        <label for="periodStart" class="text-bold"><?= $LANG['caledit_startDate'] ?></label><br>
        <span class="text-normal"><?= $LANG['caledit_startDate_comment'] ?></span>
      </div>
      <div class="col-lg-5">
        <input id="periodStart" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_periodStart" type="text" maxlength="10" value="">
        <script>
          $(function() {
            $("#periodStart").datepicker({
              changeMonth: false,
              changeYear: false,
              dateFormat: "yy-mm-dd"
            });
          });
          // Make drop downs work in modal dialogs. Needed once on page.
          // var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
          // $.fn.modal.Constructor.prototype.enforceFocus = function () {
          // };
          // $confModal.on('hidden', function() {
          //    $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
          // });
          // $confModal.modal({ backdrop : false });
        </script>
      </div>
      <?php if (isset($inputAlert["periodStart"]) && strlen($inputAlert["periodStart"])) { ?>
        <br>
        <div class="alert alert-dismissable alert-danger">
          <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['periodStart'] ?>
        </div>
      <?php } ?>
    </div>
    <div>&nbsp;</div>
    <div class="row">
      <div class="col-lg-7">
        <label for="periodEnd" class="text-bold"><?= $LANG['caledit_endDate'] ?></label><br>
        <span class="text-normal"><?= $LANG['caledit_endDate_comment'] ?></span>
      </div>
      <div class="col-lg-5">
        <input id="periodEnd" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_periodEnd" type="text" maxlength="10" value="">
        <script>
          $(function() {
            $("#periodEnd").datepicker({
              changeMonth: false,
              changeYear: false,
              dateFormat: "yy-mm-dd"
            });
          });
        </script>
      </div>
      <?php if (isset($inputAlert["periodEnd"]) && strlen($inputAlert["periodEnd"])) { ?>
        <br>
        <div class="alert alert-dismissable alert-danger">
          <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['periodEnd'] ?>
        </div>
      <?php } ?>
    </div>
    <?= createModalBottom('btn_saveperiod', 'success', $LANG['btn_save']) ?>

    <!-- Modal: Recurring -->
    <?= createModalTop('modalRecurring', $LANG['caledit_RecurringTitle'], 'lg') ?>
    <div class="row" style="margin-bottom:30px;padding-left:0px;">
      <div class="col-lg-6">
        <label for="recurringAbsence" class="fw-bold"><?= $LANG['caledit_absenceType'] ?></label><br>
        <?= $LANG['caledit_absenceType_comment'] ?>
      </div>
      <div class="col-lg-6">
        <select id="recurringAbsence" class="form-select" name="sel_recurringAbsence" tabindex="<?= ++$tabindex ?>">
          <?php foreach ($viewData['absences'] as $abs) {
            if (($abs['manager_only'] && ($UG->isGroupManagerOfUser($UL->username, $viewData['username']) || $UL->username == 'admin')) || !$abs['manager_only']) { ?>
              <option value="<?= $abs['id'] ?>"><?= $abs['name'] ?></option>
          <?php }
          } ?>
        </select>
      </div>
    </div>
    <div>
      <p><b><?= $LANG['caledit_recurrence'] ?></b><br>
        <?= $LANG['caledit_recurrence_comment'] ?></p>
    </div>
    <div class="row">
      <div class="col-lg-6" style="padding-left:20px;">
        <div class="form-check">
          <input id="monday" name="monday" value="monday" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="monday">
            <?= $LANG['weekdayLong'][1] ?>
          </label>
        </div>
        <div class="form-check">
          <input id="tuesday" name="tuesday" value="tuesday" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="tuesday">
            <?= $LANG['weekdayLong'][2] ?>
          </label>
        </div>
        <div class="form-check">
          <input id="wedensday" name="wednesday" value="wednesday" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="wedensday">
            <?= $LANG['weekdayLong'][3] ?>
          </label>
        </div>
        <div class="form-check">
          <input id="thursday" name="thursday" value="thursday" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="thursday">
            <?= $LANG['weekdayLong'][4] ?>
          </label>
        </div>
        <div class="form-check">
          <input id="friday" name="friday" value="friday" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="friday">
            <?= $LANG['weekdayLong'][5] ?>
          </label>
        </div>
      </div>
      <div class="col-lg-6" style="padding-left:20px;">
        <div class="form-check">
          <input id="saturday" name="saturday" value="saturday" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="saturday">
            <?= $LANG['weekdayLong'][6] ?>
          </label>
        </div>
        <div class="form-check">
          <input id="sunday" name="sunday" value="sunday" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="sunday">
            <?= $LANG['weekdayLong'][7] ?>
          </label>
        </div>
        <div>
          <hr>
        </div>
        <div class="form-check">
          <input id="workdays" name="workdays" value="workdays" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="workdays">
            <?= $LANG['mon_fri'] ?>
          </label>
        </div>
        <div class="form-check">
          <input id="weekends" name="weekends" value="weekends" tabindex="<?= ++$tabindex ?>" type="checkbox" class="form-check-input">
          <label class="form-check-label" for="weekends">
            <?= $LANG['sat_sun'] ?>
          </label>
        </div>
      </div>
    </div>
    <div>&nbsp;</div>
    <?= createModalBottom('btn_saverecurring', 'success', $LANG['btn_save']) ?>

  </form>

</div>