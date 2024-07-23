<?php
if (!defined('VALID_ROOT')) { exit(''); }
/**
 * Caolendar View Month View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */

// echo "<script type=\"text/javascript\">alert(\"calendarviewuserrow.php: \");</script>";
?>

  <!-- ====================================================================
view.calendarviewmonth (<?= $viewData[ 'year' ] . $viewData[ 'month' ] ?>)
-->
<?php if (!$viewData[ 'supportMobile' ]) {
  $mobilecols = array( 'full' => $viewData[ 'dateInfo' ][ 'daysInMonth' ] );
} else {
  switch ($viewData[ 'width' ]) {
    case '1024plus':
      $mobilecols = array( 'full' => $viewData[ 'dateInfo' ][ 'daysInMonth' ] );
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
      $mobilecols = array( 'full' => $viewData[ 'dateInfo' ][ 'daysInMonth' ] );
      break;
  }
}

foreach ($mobilecols as $key => $cols) {
  $days = $viewData[ 'dateInfo' ][ 'daysInMonth' ];
  $tables = ceil($days / $cols);
  $script = '';
  for ($t = 0; $t < $tables; $t++) {
    $daystart = ($t * $cols) + 1;
    $daysleft = $days - ($cols * $t);
    if ($daysleft >= $cols) $dayend = $daystart + ($cols - 1);
    else $dayend = $days;
    ?>
    <div class="table<?= ($viewData[ 'supportMobile' ]) ? $key : ''; ?>">
      <table class="table table-bordered month">

        <?php require("calendarviewmonthheader.php"); ?>

        <!-- Rows 4ff: Users -->
        <?php
        $dayAbsCount = array( 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 );
        $dayPresCount = array( 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 );

        //
        // Array to hold the users of which we have counted the absences in calendarviewuserrow.php
        // If a user is in several groups and shown more than once on the calendar page
        // we do not want to count his absences twice.
        //
        $absCountUsers = array();

        if ($C->read("defgroupfilter") == "allbygroup") {
          //
          // All-by-Group Display
          //
          $repeatHeaderCount = $C->read("repeatHeaderCount");
          if ($repeatHeaderCount) $rowcount = 1;
          //
          // Loop through all groups
          //
          foreach ($viewData[ 'groups' ] as $grp) {
            $groupHeader = false;
            //
            // Loop through all users of this group
            //
            foreach ($viewData[ 'users' ] as $usr) {
              if (count($viewData[ 'groups' ]) == 1) {
                //
                // Single group only, we will show the guests as well
                //
                if ($repeatHeaderCount && $rowcount > $repeatHeaderCount) {
                  require("calendarviewmonthheader.php");
                  $rowcount = 1;
                }
                if (!$groupHeader) { ?>
                  <!-- Row: Group <?= $grp[ 'name' ] ?> -->
                  <tr>
                    <th class="m-groupname" colspan="<?= $days + 1 ?>"><?= $grp[ 'description' ] . ' (' . $grp[ 'name' ] . ')' ?></th>
                  </tr>
                  <?php $groupHeader = true;
                }
                //
                // Skip if user is group manager and hideManagers is enabled
                //
                if ($C->read('hideManagers') and $UG->isGroupManagerOfGroup($usr[ 'username' ], $grp[ 'id' ])) {
                  continue;
                }
                ?>

                <!-- Row: User <?= $usr[ 'username' ] ?> -->
                <?php require("calendarviewuserrow.php");
                if ($repeatHeaderCount) $rowcount++;
              } else {
                //
                // Multiple groups, we will not show the guests
                //
                if ($UG->isMemberOrManagerOfGroup($usr[ 'username' ], $grp[ 'id' ])) {
                  if ($repeatHeaderCount and $rowcount > $repeatHeaderCount) {
                    require("calendarviewmonthheader.php");
                    $rowcount = 1;
                  }

                  if (!$groupHeader) { ?>
                    <!-- Row: Group <?= $grp[ 'name' ] ?> -->
                    <tr>
                      <th class="m-groupname" colspan="<?= $days + 1 ?>"><?= $grp[ 'description' ] . ' (' . $grp[ 'name' ] . ')' ?></th>
                    </tr>
                    <?php $groupHeader = true;
                  } ?>

                  <!-- Row: User <?= $usr[ 'username' ] ?> -->
                  <?php require("calendarviewuserrow.php");
                  if ($repeatHeaderCount) $rowcount++;
                }
              }
            }
          }
        } else {
          $repeatHeaderCount = $C->read("repeatHeaderCount");
          if ($repeatHeaderCount) $rowcount = 1;
          foreach ($viewData[ 'users' ] as $usr) {
            if ($repeatHeaderCount and $rowcount > $repeatHeaderCount) {
              require("calendarviewmonthheader.php");
              $rowcount = 1;
            } ?>
            <!-- Row: User <?= $usr[ 'username' ] ?> -->
            <?php require("calendarviewuserrow.php");
            if ($repeatHeaderCount) $rowcount++;
          }
        } // End if AllByGroup
        ?>

        <?php if ($C->read('includeSummary')) { ?>
          <!-- Row: Summary Header -->
          <tr>
            <td class="m-label" colspan="<?= $dayend - $daystart + 2 ?>">
              <span style="float: left;"><?= $LANG[ 'cal_summary' ] ?>&nbsp;<a class="btn btn-secondary btn-sm" data-bs-toggle="collapse" data-bs-target=".summary">...</a></span>
              <span class="float-end text-normal"><?= $viewData[ 'businessDays' ] ?>&nbsp;<?= $LANG[ 'cal_businessDays' ] ?></span>
            </td>
          </tr>

          <!-- Row: Summary Absences -->
          <tr class="summary <?= (!$C->read('showSummary')) ? 'collapse' : 'in'; ?>">
            <td class="m-name"><?= $LANG[ 'sum_absent' ] ?></td>
            <?php for ($i = $daystart; $i <= $dayend; $i++) {
              // $style = substr($viewData['dayStyles'][$i], 14);
              $style = $viewData[ 'dayStyles' ][ $i ];
              if (strlen($style)) $style = ' style="' . $style . '"';
              ?>
              <td class="m-day m-summary text-center text-danger" <?= $style ?>><?= $dayAbsCount[ $i ] ?></td>
            <?php } ?>
          </tr>

          <!-- Row: Summary Presences -->
          <tr class="summary <?= (!$C->read('showSummary')) ? 'collapse' : 'in'; ?>">
            <td class="m-name"><?= $LANG[ 'sum_present' ] ?></td>
            <?php for ($i = $daystart; $i <= $dayend; $i++) {
              // $style = substr($viewData['dayStyles'][$i], 14);
              $style = $viewData[ 'dayStyles' ][ $i ];
              if (strlen($style)) $style = ' style="' . $style . '"';
              ?>
              <td class="m-day m-summary text-center text-success" <?= $style ?>><?= $dayPresCount[ $i ] ?></td>
            <?php } ?>
          </tr>
        <?php } ?>
      </table>
    </div>

  <?php } ?>
<?php } ?>
