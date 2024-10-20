<?php
/**
 * Daynote View
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
view.daynote
-->
<div class="container content">

  <div class="col-lg-12">
    <?php
    if (
      ($showAlert && $C->read("showAlerts") != "none") &&
      ($C->read("showAlerts") == "all" || $C->read("showAlerts") == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
    ) {
      echo createAlertBox($alertData);
    }
    $tabindex = 1;
    $colsleft = 7;
    $colsright = 5;
    ?>

    <form class="form-control-horizontal" action="index.php?action=<?= $controller ?>&amp;date=<?= str_replace('-', '', $viewData['date']) ?>&amp;for=<?= $viewData['user'] ?>&amp;region=<?= $viewData['region'] ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="card">
        <?php
        $title = $LANG['dn_title'] . ' ' . $LANG['dn_title_for'] . ' ' . $viewData['date'] . ' (' . $LANG['user'] . ': ' . $viewData['userFullname'];
        if ($viewData['user'] == 'all') {
          $title .= ', ' . $LANG['region'] . ': ' . $viewData['regionName'];
        }
        $title .= ')';
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $title . $pageHelp ?></div>
        <div class="card-body">

          <div class="card">
            <div class="card-body">
              <?php if ($viewData['exists']) { ?>
                <input name="hidden_id" type="hidden" value="<?= $viewData['id'] ?>">
                <button type="submit" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" name="btn_update"><?= $LANG['btn_update'] ?></button>
                <button type="button" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteDaynote_<?= $viewData['id'] ?>"><?= $LANG['btn_delete'] ?></button>
              <?php } else { ?>
                <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_create"><?= $LANG['btn_create'] ?></button>
              <?php } ?>
              <a href="index.php?action=calendarview&rand=<?= rand(100, 9999) ?>" class="btn btn-primary float-end" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_showcalendar'] ?></a>
              <?php if ($viewData['user'] == 'all' && isAllowed($CONF['controllers']['monthedit']->permission)) { ?>
                <a href="index.php?action=monthedit&amp;month=<?= $viewData['month'] ?>&amp;region=<?= $viewData['region'] ?>" class="btn btn-info float-end" tabindex="<?= $tabindex++ ?>" style="margin-right: 6px;"><?= $LANG['btn_region_calendar'] ?></a>
              <?php } else { ?>
                <a href="index.php?action=calendaredit&amp;month=<?= substr($viewData['date'], 0, 4) . substr($viewData['date'], 5, 2) ?>&amp;region=<?= $viewData['region'] ?>&amp;user=<?= $viewData['user'] ?>" class="btn btn-info float-end" style="margin-right:6px;"><?= $LANG['btn_user_calendar'] ?></a>
              <?php } ?>
            </div>
          </div>
          <div style="height:20px;"></div>

          <div class="card">
            <div class="card-body">
              <?php foreach ($viewData['daynote'] as $formObject) {
                echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
              } ?>
            </div>
          </div>

        </div>
      </div>

      <!-- Modal: Delete Daynote -->
      <?= createModalTop('modalDeleteDaynote_' . $viewData['id'], $LANG['btn_delete']) ?>
      <?= sprintf($LANG['dn_confirm_delete']) ?>
      <?= createModalBottom('btn_delete', 'danger', $LANG['btn_delete']) ?>

    </form>

  </div>

</div>
