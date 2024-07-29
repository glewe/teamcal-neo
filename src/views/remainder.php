<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Remainder View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */

$formLink = 'index.php?action=' . $controller . '&amp;group=' . $viewData['groupid'];
?>

<!-- ====================================================================
view.remainder
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

  <form class="form-control-horizontal" enctype="multipart/form-data" action="<?= $formLink ?>" method="post" target="_self" accept-charset="utf-8">

    <div class="page-menu">
      <button type="button" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalSelectGroup"><?= $LANG['group'] . ': ' . $viewData['group'] ?></button>
      <button type="button" class="btn btn-info" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalSearchUser"><?= $LANG['search'] . ': ' . $viewData['search'] ?></button>
      <?php if (!$C->read('currentYearOnly')) { ?>
        <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalYear"><?= $LANG['year'] ?> <span class="badge badge-light"><?= $viewData['year'] ?></span></button>
      <?php } ?>
      <button type="submit" class="btn btn-success" tabindex="<?= $tabindex++ ?>" name="btn_reset"><?= $LANG['btn_reset'] ?></button>
      <a href="index.php?action=calendarview&rand=<?= rand(100, 9999) ?>" class="btn btn-secondary float-end" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_showcalendar'] ?></a>
    </div>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['rem_title'] . $pageHelp ?></div>
    </div>
    <div style="height:20px;"></div>

    <!-- Remainder Table -->
    <div class="card">
      <div class="card-body">

        <table class="table table-bordered table-hover year">
          <thead>
          <tr>
            <th><?= $LANG['users_user'] ?></th>
            <?php foreach ($viewData['absences'] as $abs) {
              if ($abs['show_in_remainder']) {
                ?>
                <th class="text-center">
                  <?php if ($abs['bgtrans']) $bgstyle = "";
                  else $bgstyle = "background-color: #" . $abs['bgcolor'] . ";"; ?>
                  <div style="color:#<?= $abs['color'] ?>;<?= $bgstyle ?>border:1px solid #333333; width:26px; height:26px;">
                    <?php if ($abs['icon'] != "No") { ?>
                      <a href="#" style="color:inherit;" data-placement="top" data-type="secondary" data-bs-toggle="tooltip" title="<?= $abs['name'] ?>"><span class="<?= $abs['icon'] ?>"></span></a>
                    <?php } else { ?>
                      <?= $abs['symbol'] ?>
                    <?php } ?>
                  </div>
                </th>
              <?php }
            } ?>
          </tr>
          </thead>

          <tbody>
          <?php foreach ($viewData['users'] as $user) { ?>
            <tr>
              <td class="m-name"><a href="index.php?action=useredit&amp;profile=<?= $user['username'] ?>" tabindex="<?= $tabindex++ ?>"><?= $user['dispname'] ?></a></td>
              <?php foreach ($viewData['absences'] as $abs) {
                if ($abs['show_in_remainder']) {
                  echo '<td class="m-day text-center">';

                  if ($AL->find($user['username'], $abs['id'])) {
                    $carryover = $AL->carryover;
                    if (!$AL->allowance) {
                      //
                      // Zero personal allowance will take over global yearly allowance
                      //
                      $AL->allowance = $abs['allowance'];
                      $AL->update();
                    }
                    $allowance = $AL->allowance;
                  } else {
                    $carryover = 0;
                    $allowance = $abs['allowance'];
                  }
                  $totalAllowance = $allowance + $carryover;
                  $taken = 0;
                  if (!$abs['counts_as_present']) {
                    $taken = countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
                  }
                  $remainder = $allowance + $carryover - ($taken * $abs['factor']);
                  $dispTaken = '<span class="badge btn-info">' . $taken . '</span>';
                  $dispAllowance = '<span class="badge btn-primary">' . $totalAllowance . '</span>';
                  $dispRemainder = '<span class="badge btn-' . (($remainder < 0) ? "danger" : "success") . '">' . $remainder . '</span>';
                  $separator = "-";
                  echo $dispTaken . $separator . $dispAllowance . $separator . $dispRemainder;
                  echo '</td>';
                }
              } ?>
            </tr>
          <?php } ?>
          </tbody>
        </table>

        <p><span class="badge btn-info"><?= $LANG['rem_legend_taken'] ?></span>-<span class="badge btn-primary"><?= $LANG['rem_legend_allowance'] ?></span>-<span class="badge btn-success"><?= $LANG['rem_legend_remainder'] ?></span></p>

      </div>
    </div>

    <!-- Modal: Select Group -->
    <?= createModalTop('modalSelectGroup', $LANG['cal_selGroup']) ?>
    <select id="group" class="form-control" name="sel_group" tabindex="<?= $tabindex++ ?>">
      <option value="all" <?= (($viewData['groupid'] == 'all') ? ' selected="selected"' : '') ?>><?= $LANG['all'] ?></option>
      <?php foreach ($viewData['allGroups'] as $grp) { ?>
        <option value="<?= $grp['id'] ?>" <?= (($viewData['groupid'] == $grp['id']) ? ' selected="selected"' : '') ?>><?= $grp['name'] ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_group', 'success', $LANG['btn_select']) ?>

    <!-- Modal: Search User -->
    <div class="modal fade" id="modalSearchUser" role="dialog" aria-labelledby="modalSearchUserLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalSearchUserLabel"><?= $LANG['cal_search'] ?></h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <input id="search" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_search" type="text" value="<?= $viewData["search"] ?>">
            <?php if (isset($inputAlert["search"])) { ?>
              <br>
              <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-bs-dismiss="alert"><i class="far fa-times-circle"></i></button><?= $inputAlert['search'] ?></div>
            <?php } ?>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-info" tabindex="<?= $tabindex++ ?>" name="btn_search" style="margin-top: 4px;"><?= $LANG['btn_search'] ?></button>
            <?php if (strlen($viewData["search"])) { ?>
              <button type="submit" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" name="btn_search_clear"><?= $LANG['btn_clear'] ?></button><?php } ?>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $LANG['btn_cancel'] ?></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Year -->
    <?= createModalTop('modalYear', $LANG['absum_modalYearTitle']) ?>
    <div>
      <span class="text-bold"><?= $LANG['year'] ?></span><br>
      <span class="text-normal"><?= $LANG['rem_year_comment'] ?></span>
      <select id="sel_year" class="form-control" name="sel_year" tabindex="<?= $tabindex++ ?>">
        <option value="<?= date("Y") - 2 ?>" <?= (($viewData['year'] == date("Y") - 2) ? "selected" : "") ?>><?= date("Y") - 2 ?></option>
        <option value="<?= date("Y") - 1 ?>" <?= (($viewData['year'] == date("Y") - 1) ? "selected" : "") ?>><?= date("Y") - 1 ?></option>
        <option value="<?= date("Y") ?>" <?= (($viewData['year'] == date("Y")) ? "selected" : "") ?>><?= date("Y") ?></option>
      </select><br>
    </div>
    <?= createModalBottom('btn_year', 'success', $LANG['btn_select']) ?>

  </form>

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
