<?php
/**
 * Holidays View
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
view.holidays
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
    $colsleft = 8;
    $colsright = 4;
    ?>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['hol_list_title'] . $pageHelp ?></div>

      <div class="card-body">

        <form class="row form-control-horizontal" name="form_create" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
          <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">
          <div class="mb-4">
            <button type="button" class="btn btn-success float-end" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalCreateHoliday"><?= $LANG['btn_create_holiday'] ?></button>
          </div>

          <!-- Modal: Create Holiday -->
          <?= createModalTop('modalCreateHoliday', $LANG['btn_create_holiday']) ?>
          <label for="inputName"><?= $LANG['name'] ?></label>
          <input id="inputName" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_name" maxlength="40" value="<?= $viewData['txt_name'] ?>" type="text">
          <?php if (isset($inputAlert["name"]) && strlen($inputAlert["name"])) { ?>
            <br>
            <div class="alert alert-dismissable alert-danger">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert">x</button><?= $inputAlert["name"] ?></div>
          <?php } ?>
          <label for="inputDescription"><?= $LANG['description'] ?></label>
          <input id="inputDescription" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_description" maxlength="100" value="<?= $viewData['txt_description'] ?>" type="text">
          <?php if (isset($inputAlert["description"]) && strlen($inputAlert["description"])) { ?>
            <br>
            <div class="alert alert-dismissable alert-danger">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert">x</button><?= $inputAlert["description"] ?></div>
          <?php } ?>
          <?= createModalBottom('btn_holCreate', 'success', $LANG['btn_create_holiday']) ?>

        </form>

        <table id="dataTableHolidays" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
          <thead>
          <tr>
            <th class="text-end">#</th>
            <th class="text-center"><?= $LANG['display'] ?></th>
            <th><?= $LANG['name'] ?></th>
            <th><?= $LANG['description'] ?></th>
            <th><?= $LANG['options'] ?></th>
            <th class="text-center"><?= $LANG['action'] ?></th>
          </tr>
          </thead>
          <tbody>
          <?php
          $i = 1;
          foreach ($viewData['holidays'] as $holiday) : ?>
            <tr>
              <td class="text-end"><?= $i++ ?></td>
              <td class="text-center"><span style="color: #<?= $holiday['color'] ?>; background-color: #<?= $holiday['bgcolor'] ?>; border: 1px solid; width: 30px; height: 30px; padding: 4px;">23</span></td>
              <td><?= $holiday['name'] ?></td>
              <td><?= $holiday['description'] ?></td>
              <td>
                <?= (($holiday['businessday']) ? '<i data-placement="top" data-type="info" data-bs-toggle="tooltip" title="' . $LANG['hol_businessday'] . '"><i class="fas fa-wrench fa-lg text-default"></i></i>&nbsp;' : '') ?>
                <?= (($holiday['keepweekendcolor']) ? '<i data-placement="top" data-type="info" data-bs-toggle="tooltip" title="' . $LANG['hol_keepweekendcolor'] . '"><i class="fas fa-paint-brush fa-lg text-success"></i></i>&nbsp;' : '') ?>
                <?= (($holiday['noabsence']) ? '<i data-placement="top" data-type="info" data-bs-toggle="tooltip" title="' . $LANG['hol_noabsence'] . '"><i class="fas fa-minus-circle fa-lg text-danger"></i></i>' : '') ?>
              </td>
              <td class="align-top text-center">
                <form class="rorm-control-horizontal" name="form_<?= $holiday['id'] ?>" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
                  <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">
                  <?php if ($holiday['id'] > 3) { ?>
                    <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteHoliday_<?= $holiday['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                    <input name="hidden_id" type="hidden" value="<?= $holiday['id'] ?>">
                    <input name="hidden_name" type="hidden" value="<?= $holiday['name'] ?>">
                    <input name="hidden_description" type="hidden" value="<?= $holiday['description'] ?>">
                    <!-- Modal: Delete Holiday -->
                    <?= createModalTop('modalDeleteHoliday_' . $holiday['id'], $LANG['btn_delete_holiday']) ?>
                    <?= sprintf($LANG['hol_confirm_delete'], $holiday['name']) ?>
                    <?= createModalBottom('btn_holDelete', 'danger', $LANG['btn_delete_holiday']) ?>
                  <?php } ?>
                  <a href="index.php?action=holidayedit&amp;id=<?= $holiday['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_edit'] ?></a>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <script>
          $(document).ready(function () {
            $('#dataTableHolidays').DataTable({
              paging: true,
              ordering: true,
              info: true,
              pageLength: 50,
              language: {
                url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
              },
              columnDefs: [
                {targets: [0, 5], orderable: false, searchable: false}
              ]
            });
          });
        </script>

      </div>
    </div>
  </div>
</div>
