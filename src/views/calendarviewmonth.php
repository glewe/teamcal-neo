<?php
/**
 * Calendar View Month View
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
view.calendarviewmonth (<?= $viewData['year'] . $viewData['month'] ?>)
-->
<?php
//
// Check if this month entry is a split month view
//
$isSplitMonth = isset($viewData['isSplitMonth']) && $viewData['isSplitMonth'];

if (!$viewData['supportMobile']) {
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
  //
  // Check if this is a split month view with custom day range
  //
  $dayRangeStart = isset($viewData['dayStart']) ? $viewData['dayStart'] : 1;
  $dayRangeEnd = isset($viewData['dayEnd']) ? $viewData['dayEnd'] : $viewData['dateInfo']['daysInMonth'];
  
  //
  // For split month view, we need to handle the combined display differently
  //
  if ($isSplitMonth) {
    $days = 30;  // 15 days from current month + 15 days from next month
    $daystart = $dayRangeStart;
    $dayend = $dayRangeEnd;
    $tables = 1;  // Only one table for split month
  } else {
    $days = ($dayRangeEnd - $dayRangeStart) + 1;
    $tables = ceil($days / $cols);
  }
  
  $script = '';
  for ($t = 0; $t < $tables; $t++) {
    if (!$isSplitMonth) {
      $daystart = ($t * $cols) + $dayRangeStart;
      $daysleft = $days - ($cols * $t);
      if ($daysleft >= $cols) {
        $dayend = $daystart + ($cols - 1);
      } else {
        $dayend = $dayRangeEnd;
      }
    }
    ?>
    <div class="table<?= ($viewData['supportMobile']) ? $key : ''; ?>">
      <table class="table table-bordered month">
        <?php require "calendarviewmonthheader.php"; ?>
        <!-- Rows 4ff: Users -->
        <?php
        // Initialize arrays for all possible days (31 for regular month + 15 for next month in split view = 46 total)
        $dayAbsCount = array_fill(0, 46, 0);
        $dayPresCount = array_fill(0, 46, 0);
        //
        // Array to hold the users of which we have counted the absences in calendarviewuserrow.php
        // If a user is in several groups and shown more than once on the calendar page
        // we do not want to count his absences twice.
        //
        $absCountUsers = array();
        if ($viewData['defgroupfilter'] == "allbygroup") {
          //
          // All-by-Group Display
          //
          $repeatHeaderCount = $allConfig["repeatHeaderCount"];
          if ($repeatHeaderCount) {
            $rowcount = 1;
          }
          //
          // Loop through all groups
          //
          foreach ($viewData['groups'] as $grp) {
            $groupHeader = false;
            //
            // Loop through all users of this group
            //
            foreach ($viewData['users'] as $usr) {
              if (count($viewData['groups']) == 1) {
                //
                // Single group only, we will show the guests as well
                //
                if ($repeatHeaderCount && $rowcount > $repeatHeaderCount) {
                  require "calendarviewmonthheader.php";
                  $rowcount = 1;
                }
                if (!$groupHeader) { ?>
                  <!-- Row: Group <?= $grp['name'] ?> -->
                  <tr>
                    <th class="m-groupname" colspan="<?= $days + 1 ?>" scope="col"><?= $grp['description'] . ' (' . $grp['name'] . ')' ?></th>
                  </tr>
                  <?php $groupHeader = true;
                }
                //
                // Skip if user is group manager and hideManagers is enabled
                //
                if ($viewData['hideManagers'] && $UG->isGroupManagerOfGroup($usr['username'], $grp['id'])) {
                  continue;
                }
                ?>
                <!-- Row: User <?= $usr['username'] ?> -->
                <?php require "calendarviewuserrow.php";
                if ($repeatHeaderCount) {
                  $rowcount++;
                }
              } else {
                //
                // Multiple groups, we will not show the guests
                //
                if ($UG->isMemberOrManagerOfGroup($usr['username'], $grp['id'])) {
                  if ($repeatHeaderCount || (isset($rowcount) && $rowcount > $repeatHeaderCount)) {
                    require "calendarviewmonthheader.php";
                    $rowcount = 1;
                  }
                  if (!$groupHeader) { ?>
                    <!-- Row: Group <?= $grp['name'] ?> -->
                    <tr>
                      <th class="m-groupname" colspan="<?= $days + 1 ?>" scope="col"><?= $grp['description'] . ' (' . $grp['name'] . ')' ?></th>
                    </tr>
                    <?php $groupHeader = true;
                  } ?>
                  <!-- Row: User <?= $usr['username'] ?> -->
                  <?php require "calendarviewuserrow.php";
                  if ($repeatHeaderCount) {
                    $rowcount++;
                  }
                }
              }
            }
          }
        } else {
          $repeatHeaderCount = $viewData['repeatHeaderCount'];
          if ($repeatHeaderCount) {
            $rowcount = 1;
          }
          foreach ($viewData['users'] as $usr) {
            if ($repeatHeaderCount && $rowcount > $repeatHeaderCount) {
              require "calendarviewmonthheader.php";
              $rowcount = 1;
            } ?>
            <!-- Row: User <?= $usr['username'] ?> -->
            <?php require "calendarviewuserrow.php";
            if ($repeatHeaderCount) {
              $rowcount++;
            }
          }
        } // End if AllByGroup
        ?>
        <?php if ($viewData['includeSummary']) { ?>
          <!-- Row: Summary Header -->
          <tr>
            <td class="m-label" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target=".summary" aria-expanded="<?= $viewData['showSummary'] ? 'true' : 'false' ?>">
              <i class="bi-caret-right-fill" style="display: inline-block; width: 1em; transition: transform 0.3s;"></i>&nbsp;<?= $LANG['cal_summary'] ?>
            </td>
            <td class="m-label" colspan="<?= $isSplitMonth ? 15 : ($dayend - $daystart + 1) ?>" style="vertical-align: middle; <?= $isSplitMonth ? 'border-right: 2px solid #000;' : '' ?>">
              <span class="float-end text-normal"><?= $viewData['businessDays'] ?>&nbsp;<?= $LANG['cal_businessDays'] ?></span>
            </td>
            <?php if ($isSplitMonth) { ?>
            <td class="m-label" colspan="15" style="vertical-align: middle;">
              <span class="float-end text-normal"><?= isset($viewData['nextMonthBusinessDays']) ? $viewData['nextMonthBusinessDays'] : 0 ?>&nbsp;<?= $LANG['cal_businessDays'] ?></span>
            </td>
            <?php } ?>
          </tr>
          <!-- Row: Summary Absences -->
          <tr class="summary collapse <?= ($viewData['showSummary']) ? 'show' : ''; ?>">
            <td class="m-name"><?= $LANG['sum_absent'] ?></td>
            <?php
            if ($isSplitMonth) {
              // In split month view, show days 17-31 of current month + days 1-15 of next month
              for ($i = 17; $i <= 31; $i++) {
                $style = $viewData['dayStyles'][$i] ?? '';
                // Add right border to last day of current month (day 31)
                if ($i == 31) {
                  $style .= 'border-right: 2px solid #000;';
                }
                if (strlen($style)) {
                  $style = ' style="' . $style . '"';
                }
                ?>
                <td class="m-day m-summary text-center td-summary-absence" <?= $style ?>><?= $dayAbsCount[$i] ?></td>
              <?php }
              for ($i = 1; $i <= 15; $i++) {
                $styleKey = 'next_' . $i;
                $style = $viewData['dayStyles'][$styleKey] ?? '';
                if (strlen($style)) {
                  $style = ' style="' . $style . '"';
                }
                ?>
                <td class="m-day m-summary text-center td-summary-absence" <?= $style ?>><?= $dayAbsCount[$i + 15] ?></td>
              <?php }
            } else {
              // Regular month view
              for ($i = $daystart; $i <= $dayend; $i++) {
                $style = $viewData['dayStyles'][$i] ?? '';
                if (strlen($style)) {
                  $style = ' style="' . $style . '"';
                }
                ?>
                <td class="m-day m-summary text-center td-summary-absence" <?= $style ?>><?= $dayAbsCount[$i] ?></td>
              <?php }
            } ?>
          </tr>
          <!-- Row: Summary Presences -->
          <tr class="summary collapse <?= ($viewData['showSummary']) ? 'show' : ''; ?>">
            <td class="m-name"><?= $LANG['sum_present'] ?></td>
            <?php
            if ($isSplitMonth) {
              // In split month view, show days 17-31 of current month + days 1-15 of next month
              for ($i = 17; $i <= 31; $i++) {
                $style = $viewData['dayStyles'][$i] ?? '';
                // Add right border to last day of current month (day 31)
                if ($i == 31) {
                  $style .= 'border-right: 2px solid #000;';
                }
                if (strlen($style)) {
                  $style = ' style="' . $style . '"';
                }
                ?>
                <td class="m-day m-summary text-center td-summary-presence" <?= $style ?>><?= $dayPresCount[$i] ?></td>
              <?php }
              for ($i = 1; $i <= 15; $i++) {
                $styleKey = 'next_' . $i;
                $style = $viewData['dayStyles'][$styleKey] ?? '';
                if (strlen($style)) {
                  $style = ' style="' . $style . '"';
                }
                ?>
                <td class="m-day m-summary text-center td-summary-presence" <?= $style ?>><?= $dayPresCount[$i + 15] ?></td>
              <?php }
            } else {
              // Regular month view
              for ($i = $daystart; $i <= $dayend; $i++) {
                $style = $viewData['dayStyles'][$i] ?? '';
                if (strlen($style)) {
                  $style = ' style="' . $style . '"';
                }
                ?>
                <td class="m-day m-summary text-center td-summary-presence" <?= $style ?>><?= $dayPresCount[$i] ?></td>
              <?php }
            } ?>
          </tr>
        <?php } ?>
      </table>
    </div>
    <?php if ($viewData['summaryAbsenceTextColor'] || $viewData['summaryPresenceTextColor']) { ?>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          var absenceTextColor = "#<?= $viewData['summaryAbsenceTextColor'] ?>";
          var elements = document.getElementsByClassName("td-summary-absence");
          for (var i = 0; i < elements.length; i++) {
            elements[i].style.color = absenceTextColor;
          }
          var presenceTextColor = "#<?= $viewData['summaryPresenceTextColor'] ?>";
          elements = document.getElementsByClassName("td-summary-presence");
          for (var i = 0; i < elements.length; i++) {
            elements[i].style.color = presenceTextColor;
          }
        });
      </script>
    <?php } ?>


  <?php } ?>
<?php } ?>
