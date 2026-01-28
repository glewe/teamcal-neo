<?php
/**
 * Calendar View User Row View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
//
// Check whether the current user may view users profiles.
// If so, we link the name to the viewprofile page
//
$fullName = $U->getLastFirst($usr['username']);
if (isAllowed($CONF['controllers']['viewprofile']->permission)) {
  $profileName = '<a href="index.php?action=viewprofile&amp;profile=' . $usr['username'] . '">' . $fullName . '</a>';
} else {
  $profileName = $fullName;
}
//
// Check whether the current user may edit this loop users calendar.
// If so, we link each table cell to the editcalendar page (done so in the day loop below).
//
$editAllowed = false;
$editLink = '';
if (isAllowed($CONF['controllers']['calendaredit']->permission)) {
  if ($UL->username == $usr['username']) {
    if (isAllowed("calendareditown")) {
      $editAllowed = true;
    }
  } elseif ($UG->shareGroupMemberships($UL->username, $usr['username'])) {
    if (isAllowed("calendareditgroup") || (isAllowed("calendareditgroupmanaged") && $UG->isGroupManagerOfUser($UL->username, $usr['username']))) {
      $editAllowed = true;
    }
  } else {
    if (isAllowed("calendareditall")) {
      $editAllowed = true;
    }
  }
}
if ($editAllowed) {
  $editLink = ' onclick="window.location.href = \'index.php?action=calendaredit&amp;month=' . $viewData['year'] . $viewData['month'] . '&amp;region=' . $viewData['regionid'] . '&user=' . $usr['username'] . '\';"';
}
?>
<!-- ====================================================================
view.calendaruserrow (<?= $viewData['year'] . $viewData['month'] ?> - <?= $fullName ?>)
-->
<tr>
  <?php
  $nameStyle = "m-name";
  if ($viewData['groupid'] != "all" && !$UG->isMemberOrManagerOfGroup($usr['username'], $viewData['groupid'])) {
    //
    // We have a group display. Let's display guests in italic letters.
    //
    $nameStyle = "m-name-guest";
  } ?>
  <td class="<?= $nameStyle ?>">
    <?php if ($viewData['showAvatars']) { ?>
      <i data-placement="top" data-type="secondary" data-bs-toggle="tooltip" title="<img src='<?= APP_AVATAR_DIR . $UO->read($usr['username'], 'avatar') ?>' style='width: 80px; height: 80px;'>"><img src="<?= APP_AVATAR_DIR ?>/<?= $UO->read($usr['username'], 'avatar') ?>" alt="" style="width: 16px; height: 16px;"></i>
    <?php } ?>
    <?php if ($viewData['showRoleIcons']) {
      $thisRole = $U->getRole($usr['username']);
      ?>
      <i data-placement="top" data-type="info" data-bs-toggle="tooltip" title="<?= $LANG['role'] ?>: <?= $RO->getNameById($thisRole) ?>"><i class="fas fa-user-circle fa-sm text-<?= $RO->getColorById($thisRole) ?>"></i></i>
    <?php } ?>
    <?= $profileName ?>
    <?php foreach ($viewData['monitorAbsence'] as $monAbsId) {
      if (!$monAbsId) continue;
      $summary = getAbsenceSummary($usr['username'], $monAbsId, $viewData['year']);
      ?>
      <div style="text-align:right;font-style:italic;" title="<?= $A->getName($monAbsId) . ' ' . $viewData['year'] . ': ' . $LANG['remainder'] . ' / ' . $LANG['allowance'] ?>">
        <span class="abs-icon-square" style="color: #<?= $A->getColor($monAbsId) ?>;"><?= $viewData['symbolAsIcon'] ? $A->getSymbol($monAbsId) : '<i class="' . $A->getIcon($monAbsId) . '"></i>' ?></span>
        &nbsp;<span class="text-danger"><?= $summary['remainder'] ?></span> / <?= $summary['totalallowance'] ?>
      </div>
    <?php } ?>
  </td>
  <?php
  $T->getTemplate($usr['username'], $viewData['year'], $viewData['month']);
  $currDate = date('Y-m-d');
  
  //
  // For split month view, also load the next month's template
  //
  $nextMonthTemplate = null;
  if (isset($viewData['isSplitMonth']) && $viewData['isSplitMonth']) {
    $nextMonthNum = intval($viewData['month']) + 1;
    $nextMonthYear = $viewData['year'];
    if ($nextMonthNum > 12) {
      $nextMonthNum = 1;
      $nextMonthYear++;
    }
    $nextMonthTemplate = new Templates();
    $nextMonthTemplate->getTemplate($usr['username'], $nextMonthYear, sprintf('%02d', $nextMonthNum));
  }
  
  //
  // Loop through all days of this month
  //
  // Initialize day range if not set (fallback to full month)
  $daystart = $daystart ?? 1;
  $dayend = $dayend ?? $viewData['dateInfo']['daysInMonth'];
  
  for ($i = $daystart; $i <= $dayend; $i++) {
    $loopDate = date('Y-m-d', mktime(0, 0, 0, $viewData['month'], $i, $viewData['year']));
    $abs = 'abs' . $i;
    $style = $viewData['dayStyles'][$i] ?? '';
    
    //
    // In split month view, add a right border to the last day of the first month
    //
    if (isset($viewData['isSplitMonth']) && $viewData['isSplitMonth'] && $i == $dayend) {
      $style .= 'border-right: 2px solid #000;';
    }
    $icon = '&nbsp;';
    $absstart = '';
    $absend = '';
    $note = false;
    $notestart = '';
    $noteend = '';
    $bday = false;
    $bdaystart = '';
    $bdayend = '';
    $allowed = true;

    if ($T->$abs) {
      //
      // This is an absence. Get icon and coloring info.
      //
      if (!$viewData['absfilter'] || ($viewData['absfilter'] && $T->$abs == $viewData['absid'])) {
        if ($A->isConfidential($T->$abs)) {
          //
          // This absence type is confidential. Check whether the logged in user may see it.
          // Rules:
          // - Logged in user must be in a trusted role or must be "admin"
          //
          $allowed = false;
          if (in_array($UL->getRole($UL->username), $viewData['trustedRoles']) || $UL->username == 'admin' || $UL->username == $usr['username']) {
            $allowed = true;
          }
        }

        if ($allowed) {
          if ($A->getBgTrans($T->$abs)) {
            $bgStyle = "";
          } else {
            $bgStyle = "background-color: #" . $A->getBgColor($T->$abs) . ";";
          }
          $style .= 'color: #' . $A->getColor($T->$abs) . ';' . $bgStyle;
          if ($viewData['symbolAsIcon']) {
            $icon = $A->getSymbol($T->$abs);
          } else {
            $icon = '<span class="' . $A->getIcon($T->$abs) . ' align-bottom"></span>';
          }
          $countFrom = $viewData['year'] . $viewData['month'] . '01';
          $countTo = $viewData['year'] . $viewData['month'] . $dayend;
          $taken = '';
          if ($viewData['showTooltipCount']) {
            $taken .= ' (';
            $taken .= countAbsence($usr['username'], $T->$abs, $countFrom, $countTo, true, false);
            $taken .= ')';
          }
          $absstart = '<span data-bs-custom-class="danger" data-bs-placement="top" data-bs-toggle="tooltip" title="' . $A->getName($T->$abs) . $taken . '">';
          $absend = '</span>';
        } else {
          //
          // This is a confidential absence and the logged in user is not allowed to see it. Just color it gray and add a tooltip.
          //
          $style .= 'color: #d5d5d5;background-color: #d5d5d5;';
          $icon = '&nbsp;';
          $absstart = '<span data-bs-custom-class="danger" data-placement="top" data-bs-toggle="tooltip" title="' . $LANG['cal_tt_absent'] . '">';
          $absend = '</span>';
        }
      } else {
        //
        // An absence filter was submitted. This is not it but a different absence. Just color it gray and add a tooltip.
        //
        $style .= 'color: #d5d5d5;background-color: #d5d5d5;';
        $icon = '&nbsp;';
        $absstart = '<span data-bs-custom-class="danger" data-bs-placement="top" data-bs-toggle="tooltip" title="' . $LANG['cal_tt_anotherabsence'] . '">';
        $absend = '</span>';
      }
      //
      // Count this absence/presence if we haven't done so for this user/day
      //
      if (!in_array($usr['username'] . "::" . $i, $absCountUsers)) {
        if (!$A->getCountsAsPresent($T->$abs)) {
          $dayAbsCount[$i]++;
        } else {
          $dayPresCount[$i]++;
        }
        $absCountUsers[] = $usr['username'] . "::" . $i; // Put username and day into array so we know we have counted him
      }
    } else {
      if ($loopDate < $currDate && $bgColor = $viewData['pastDayColor']) {
        $style .= "background-color:#" . $bgColor . ";";
      }
      $dayPresCount[$i]++;
    }

    if ($D->get($viewData['year'] . $viewData['month'] . sprintf("%02d", $i), $usr['username'], $viewData['regionid'], true)) {
      //
      // This is a user's daynote
      //
      $allowed = true;
      if ($D->isConfidential($D->id)) {
        //
        // This daynote is confidential. Check whether the logged in user may see it.
        // Rules:
        // - Logged in user must be in a trusted role or
        // - Daynote belongs to the logged in user
        // - Logged in user is admin
        //
        $allowed = false;
        if (in_array($UL->getRole($UL->username), $viewData['trustedRoles']) || $UL->username == $D->username || $UL->username == 'admin') {
          $allowed = true;
        }
      }
      if ($allowed) {
        $note = true;
        $notestart = '<span data-bs-custom-class="' . $D->color . '" data-bs-placement="bottom" data-bs-toggle="tooltip" title="' . $D->daynote . '">';
        $noteend = '</span>';
      }
    }
    //
    // Select the upper right corner indicator if applicable
    //
    if ($note && $bday) {
      $style .= 'background-image: url(images/ovl_bdaynote.gif); background-repeat: no-repeat; background-position: top right; background-size: 10px 10px;';
    } elseif ($note) {
      $style .= 'background-image: url(images/ovl_daynote.gif); background-repeat: no-repeat; background-position: top right; background-size: 10px 10px;';
    } elseif ($bday) {
      $style .= 'background-image: url(images/ovl_birthday.gif); background-repeat: no-repeat; background-position: top right; background-size: 10px 10px;';
    }
    //
    // Regional holiday in another region
    //
    if ($viewData['regionalHolidays']) {
      $userRegion = $UO->read($usr['username'], 'region');
      if (!$userRegion) {
        $userRegion = '1';
      }
      if ($userRegion != $viewData['regionid']) {
        $regionHoliday = $M->getHoliday($viewData['year'], $viewData['month'], $i, $userRegion);
        if ($regionHoliday) {
          //
          // We have a Holiday in another region than the one displayed
          //
          if (!$viewData['regionalHolidaysColor']) {
            $regionalHolidaysColor = "cc0000";
            $C->save("regionalHolidaysColor", $regionalHolidaysColor);
          }
          $style .= 'border: 2px solid #' . $regionalHolidaysColor . ' !important;';
        }
      }
    }
    //
    // If we have styles collected, build the style attribute
    //
    if (strlen($style)) {
      $style = ' style="' . $style . '"';
    }
    ?>

    <td class="m-day text-center" <?= $style ?><?= $editLink ?>>
      <?php
      if ($editAllowed) {
        echo '<i data-bs-placement="right" data-bs-custom-class="secondary" data-bs-toggle="tooltip" title="' . $LANG['cal_tt_clicktoedit'] . '">';
      }
      echo $bdaystart . $notestart . $absstart . $icon . $absend . $noteend . $bdayend;
      if ($editAllowed) {
        echo '</i>';
      }
      ?>
    </td>

  <?php }
  
  //
  // For split month view, also loop through the first 15 days of next month
  //
  if (isset($viewData['isSplitMonth']) && $viewData['isSplitMonth']) {
    $nextMonthNum = intval($viewData['month']) + 1;
    $nextMonthYear = $viewData['year'];
    if ($nextMonthNum > 12) {
      $nextMonthNum = 1;
      $nextMonthYear++;
    }
    
    //
    // Build edit link for next month
    //
    $nextMonthEditLink = '';
    if ($editAllowed) {
      $nextMonthEditLink = ' onclick="window.location.href = \'index.php?action=calendaredit&amp;month=' . $nextMonthYear . sprintf('%02d', $nextMonthNum) . '&amp;region=' . $viewData['regionid'] . '&user=' . $usr['username'] . '\';"';
    }
    
    for ($i = 1; $i <= 15; $i++) {
      $loopDate = date('Y-m-d', mktime(0, 0, 0, $nextMonthNum, $i, $nextMonthYear));
      $abs = 'abs' . $i;
      $style = $viewData['dayStyles']['next_' . $i] ?? '';  // Apply day styles for next month days with fallback
      $icon = '&nbsp;';
      $absstart = '';
      $absend = '';
      $note = false;
      $notestart = '';
      $noteend = '';
      $bday = false;
      $bdaystart = '';
      $bdayend = '';
      $allowed = true;

      if ($nextMonthTemplate && $nextMonthTemplate->$abs) {
        //
        // This is an absence. Get icon and coloring info.
        //
        if (!$viewData['absfilter'] || ($viewData['absfilter'] && $nextMonthTemplate->$abs == $viewData['absid'])) {
          if ($A->isConfidential($nextMonthTemplate->$abs)) {
            $allowed = false;
            if (in_array($UL->getRole($UL->username), $viewData['trustedRoles']) || $UL->username == 'admin' || $UL->username == $usr['username']) {
              $allowed = true;
            }
          }

          if ($allowed) {
            if ($A->getBgTrans($nextMonthTemplate->$abs)) {
              $bgStyle = "";
            } else {
              $bgStyle = "background-color: #" . $A->getBgColor($nextMonthTemplate->$abs) . ";";
            }
            $style .= 'color: #' . $A->getColor($nextMonthTemplate->$abs) . ';' . $bgStyle;
            if ($viewData['symbolAsIcon']) {
              $icon = $A->getSymbol($nextMonthTemplate->$abs);
            } else {
              $icon = '<span class="' . $A->getIcon($nextMonthTemplate->$abs) . ' align-bottom"></span>';
            }
            $absstart = '<span data-bs-custom-class="danger" data-bs-placement="top" data-bs-toggle="tooltip" title="' . $A->getName($nextMonthTemplate->$abs) . '">';
            $absend = '</span>';
          } else {
            $style .= 'color: #d5d5d5;background-color: #d5d5d5;';
            $icon = '&nbsp;';
            $absstart = '<span data-bs-custom-class="danger" data-placement="top" data-bs-toggle="tooltip" title="' . $LANG['cal_tt_absent'] . '">';
            $absend = '</span>';
          }
        } else {
          $style .= 'color: #d5d5d5;background-color: #d5d5d5;';
          $icon = '&nbsp;';
          $absstart = '<span data-bs-custom-class="danger" data-bs-placement="top" data-bs-toggle="tooltip" title="' . $LANG['cal_tt_anotherabsence'] . '">';
          $absend = '</span>';
        }
      } else {
        if ($loopDate < $currDate && $bgColor = $viewData['pastDayColor']) {
          $style .= "background-color:#" . $bgColor . ";";
        }
      }

      if ($D->get($nextMonthYear . sprintf('%02d', $nextMonthNum) . sprintf("%02d", $i), $usr['username'], $viewData['regionid'], true)) {
        //
        // This is a user's daynote for the next month
        //
        $allowed = true;
        if ($D->isConfidential($D->id)) {
          //
          // This daynote is confidential. Check whether the logged in user may see it.
          // Rules:
          // - Logged in user must be in a trusted role or
          // - Daynote belongs to the logged in user
          // - Logged in user is admin
          //
          $allowed = false;
          if (in_array($UL->getRole($UL->username), $viewData['trustedRoles']) || $UL->username == $D->username || $UL->username == 'admin') {
            $allowed = true;
          }
        }
        if ($allowed) {
          $note = true;
          $notestart = '<span data-bs-custom-class="' . $D->color . '" data-bs-placement="bottom" data-bs-toggle="tooltip" title="' . $D->daynote . '">';
          $noteend = '</span>';
        }
      }

      if (strlen($style)) {
        $style = ' style="' . $style . '"';
      }
      ?>

      <td class="m-day text-center" <?= $style ?><?= $nextMonthEditLink ?>>
        <?php
        if ($editAllowed) {
          echo '<i data-bs-placement="right" data-bs-custom-class="secondary" data-bs-toggle="tooltip" title="' . $LANG['cal_tt_clicktoedit'] . '">';
        }
        echo $bdaystart . $notestart . $absstart . $icon . $absend . $noteend . $bdayend;
        if ($editAllowed) {
          echo '</i>';
        }
        ?>
      </td>

    <?php }
  }
  ?>
</tr>
