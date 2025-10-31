<?php

/**
 * Calendar View Month Header View
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
view.calendarviewmonthheader (<?= $viewData['year'] . $viewData['month'] ?>)
-->

<!-- Row: Month name and day numbers -->
<tr>
  <?php if (isset($viewData['isSplitMonth']) && $viewData['isSplitMonth']) { ?>
    <th class="m-monthname" scope="col"><?= $viewData['dateInfo']['monthshort'] ?>/<?= $viewData['nextMonthInfo']['monthshort'] ?> <?= $viewData['dateInfo']['year'] ?></th>
  <?php } else { ?>
    <th class="m-monthname" scope="col"><?= $viewData['dateInfo']['monthname'] ?> <?= $viewData['dateInfo']['year'] ?></th>
  <?php } ?>

  <?php
  //
  // Display day numbers from daystart to dayend
  // For split month, daystart=16 and dayend=31, then we add days 1-15 of next month
  //
  for ($i = $daystart; $i <= $dayend; $i++) {
    $notestart = '';
    $noteend = '';
    $notestyle = '';
    if ($D->get($viewData['year'] . $viewData['month'] . sprintf("%02d", $i), 'all', $viewData['regionid'], true)) {
      $notestart = '<div style="width: 100%; height: 100%;" data-placement="top" data-type="' . $D->color . '" data-bs-toggle="tooltip" title="' . $D->daynote . '">';
      $noteend = '</div>';
      $notestyle = 'background-image: url(images/ovl_daynote.gif); background-repeat: no-repeat; background-position: top right;';
    }
    if (isset($viewData['dayStyles'][$i]) && strlen($viewData['dayStyles'][$i])) {
      $dayStyles = ' style="' . $viewData['dayStyles'][$i] . $notestyle . '"';
    } else {
      $dayStyles = ' style="' . $notestyle . '"';
    }

    //
    // In split month view, add a right border to the last day of the first month
    //
    if ($i == $dayend) {
      if (isset($viewData['dayStyles'][$i]) && strlen($viewData['dayStyles'][$i])) {
        $dayStyles = ' style="' . $viewData['dayStyles'][$i] . 'border-right: 2px solid #000;"';
      } else {
        $dayStyles = ' style="border-right: 2px solid #000;"';
      }
    }

  ?>
    <th class="m-daynumber text-center" scope="col" <?= $dayStyles ?>><?= $notestart . $i . $noteend ?></th>
    <?php }

  //
  // For split month view, also display days 1-15 of next month
  //
  if (isset($viewData['isSplitMonth']) && $viewData['isSplitMonth']) {
    $nextMonthNum = intval($viewData['month']) + 1;
    $nextMonthYear = $viewData['year'];
    if ($nextMonthNum > 12) {
      $nextMonthNum = 1;
      $nextMonthYear++;
    }
    for ($i = 1; $i <= 15; $i++) {
      $notestart = '';
      $noteend = '';
      $notestyle = '';
      if ($D->get($nextMonthYear . sprintf("%02d", $nextMonthNum) . sprintf("%02d", $i), 'all', $viewData['regionid'], true)) {
        $notestart = '<div style="width: 100%; height: 100%;" data-placement="top" data-type="' . $D->color . '" data-bs-toggle="tooltip" title="' . $D->daynote . '">';
        $noteend = '</div>';
        $notestyle = 'background-image: url(images/ovl_daynote.gif); background-repeat: no-repeat; background-position: top right;';
      }
      if (isset($viewData['dayStyles']['next_' . $i]) && strlen($viewData['dayStyles']['next_' . $i])) {
        $dayStyles = ' style="' . $viewData['dayStyles']['next_' . $i] . $notestyle . '"';
      } else {
        $dayStyles = ' style="' . $notestyle . '"';
      }
    ?>
      <th class="m-daynumber text-center" scope="col" <?= $dayStyles ?>><?= $notestart . $i . $noteend ?></th>
  <?php }
  }
  ?>
</tr>

<!-- Row: Weekdays -->
<tr>
  <th class="m-label" scope="col"></th>
  <?php
  if (isset($viewData['isSplitMonth']) && $viewData['isSplitMonth']) {
    // Weekdays for current month (last 15 days)
    for ($i = $daystart; $i <= $dayend; $i++) {
      if (isset($viewData['dayStyles'][$i]) && strlen($viewData['dayStyles'][$i])) {
        $dayStyles = ' style="' . $viewData['dayStyles'][$i] . '"';
      } else {
        $dayStyles = '';
      }

      //
      // In split month view, add a right border to the last day of the first month
      //
      if ($i == $dayend) {
        if (isset($viewData['dayStyles'][$i]) && strlen($viewData['dayStyles'][$i])) {
          $dayStyles = ' style="' . $viewData['dayStyles'][$i] . 'border-right: 2px solid #000;"';
        } else {
          $dayStyles = ' style="border-right: 2px solid #000;"';
        }
      }

      $prop = 'wday' . $i;
  ?>
      <th class="m-weekday text-center" scope="col" <?= $dayStyles ?>><?= $LANG['weekdayShort'][$M->$prop] ?></th>
    <?php }
    // Weekdays for next month (first 15 days)
    if (isset($vmonth['nextM'])) {
      //
      // Use the nextM object from the month data if available
      //
      $nextM = $vmonth['nextM'];
    } else {
      //
      // Otherwise, create it on the fly
      //
      $nextMonthNum = intval($viewData['month']) + 1;
      $nextMonthYear = $viewData['year'];
      if ($nextMonthNum > 12) {
        $nextMonthNum = 1;
        $nextMonthYear++;
      }
      $nextM = new Months();
      $nextM->getMonth($nextMonthYear, sprintf('%02d', $nextMonthNum), $viewData['regionid']);
    }
    for ($i = 1; $i <= 15; $i++) {
      if (isset($viewData['dayStyles']['next_' . $i]) && strlen($viewData['dayStyles']['next_' . $i])) {
        $dayStyles = ' style="' . $viewData['dayStyles']['next_' . $i] . '"';
      } else {
        $dayStyles = '';
      }
      $prop = 'wday' . $i;
    ?>
      <th class="m-weekday text-center" scope="col" <?= $dayStyles ?>><?= $LANG['weekdayShort'][$nextM->$prop] ?></th>
    <?php }
  } else {
    // Standard month display
    for ($i = $daystart; $i <= $dayend; $i++) {
      if (isset($viewData['dayStyles'][$i]) && strlen($viewData['dayStyles'][$i])) {
        $dayStyles = ' style="' . $viewData['dayStyles'][$i] . '"';
      } else {
        $dayStyles = '';
      }
      $prop = 'wday' . $i;
    ?>
      <th class="m-weekday text-center" scope="col" <?= $dayStyles ?>><?= $LANG['weekdayShort'][$M->$prop] ?></th>
  <?php }
  }
  ?>
</tr>

<?php if ($viewData['showWeekNumbers']) { ?>
  <!-- Row: Week numbers -->
  <tr>
    <th class="m-label" scope="col"><?= $LANG['weeknumber'] ?></th>
    <?php
    // Week numbers for current month (last 15 days)
    for ($i = $daystart; $i <= $dayend; $i++) {
      $prop = 'week' . $i;
      $wprop = 'wday' . $i; ?>
      <th class="m-weeknumber text-center<?= (($M->$wprop == $viewData['firstDayOfWeek']) ? ' first' : ' inner') ?>" scope="col"><?= (($M->$wprop == $viewData['firstDayOfWeek']) ? $M->$prop : '') ?></th>
      <?php }

    // Week numbers for next month (first 15 days) in split month mode
    if (isset($viewData['isSplitMonth']) && $viewData['isSplitMonth']) {
      if (isset($vmonth['nextM'])) {
        //
        // Use the nextM object from the month data if available
        //
        $nextM = $vmonth['nextM'];
      } else {
        //
        // Otherwise, create it on the fly
        //
        $nextMonthNum = intval($viewData['month']) + 1;
        $nextMonthYear = $viewData['year'];
        if ($nextMonthNum > 12) {
          $nextMonthNum = 1;
          $nextMonthYear++;
        }
        $nextM = new Months();
        $nextM->getMonth($nextMonthYear, sprintf('%02d', $nextMonthNum), $viewData['regionid']);
      }
      for ($i = 1; $i <= 15; $i++) {
        $prop = 'week' . $i;
        $wprop = 'wday' . $i; ?>
        <th class="m-weeknumber text-center<?= (($nextM->$wprop == $viewData['firstDayOfWeek']) ? ' first' : ' inner') ?>" scope="col"><?= (($nextM->$wprop == $viewData['firstDayOfWeek']) ? $nextM->$prop : '') ?></th>
    <?php }
    }
    ?>
  </tr>
<?php } ?>