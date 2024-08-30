<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Groups View
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
view.groups
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
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['groups_title'] . $pageHelp ?></div>

      <div class="card-body">

        <form class="form-control-horizontal" name="form_create" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
          <div class="row mb-4">
            <div class="col-lg-4">
              <label for="inputSearch"><?= $LANG['search'] ?></label>
              <input id="inputSearch" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_searchGroup" maxlength="40" value="<?= $viewData['searchGroup'] ?>" type="text">
            </div>
            <div class="col-lg-3">
              <br>
              <button type="submit" class="btn btn-secondary" tabindex="<?= $tabindex++ ?>" name="btn_search"><?= $LANG['btn_search'] ?></button>
              <a href="index.php?action=groups" class="btn btn-secondary" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_reset'] ?></a>
            </div>
            <div class="col-lg-5 text-end">
              <br>
              <?php if (isAllowed($CONF['controllers'][$controller]->permission)) { ?>
                <button type="button" class="btn btn-success" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalCreateGroup"><?= $LANG['btn_create_group'] ?></button>
              <?php } ?>
            </div>
          </div>

          <!-- Modal: Create group -->
          <?= createModalTop('modalCreateGroup', $LANG['btn_create_group']) ?>
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
          <?= createModalBottom('btn_groupCreate', 'success', $LANG['btn_create_group']) ?>

        </form>

        <table id="dataTableGroups" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
          <thead>
          <tr>
            <th class="text-end">#</th>
            <th><?= $LANG['groups_name'] ?></th>
            <th><?= $LANG['groups_description'] ?></th>
            <th><?= $LANG['groups_minpresent'] ?></th>
            <th><?= $LANG['groups_maxabsent'] ?></th>
            <th><?= $LANG['groups_minpresentwe'] ?></th>
            <th><?= $LANG['groups_maxabsentwe'] ?></th>
            <th class="text-center"><?= $LANG['action'] ?></th>
          </tr>
          </thead>
          <tbody>
          <?php
          $i = 1;
          foreach ($viewData['groups'] as $group) : ?>
            <tr>
              <td class="text-end"><?= $i++ ?></td>
              <td><?= $group['name'] ?></td>
              <td><?= $group['description'] ?></td>
              <td><?= $group['minpresent'] ?></td>
              <td><?= $group['maxabsent'] ?></td>
              <td><?= $group['minpresentwe'] ?></td>
              <td><?= $group['maxabsentwe'] ?></td>
              <td class="align-top text-center">
                <form class="form-control-horizontal" name="form_<?= $group['id'] ?>" action="index.php?action=groups" method="post" target="_self" accept-charset="utf-8">
                  <?php if (isAllowed($CONF['controllers'][$controller]->permission)) { ?>
                    <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteGroup_<?= $group['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                  <?php } ?>
                  <a href="index.php?action=groupedit&amp;id=<?= $group['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_edit'] ?></a>
                  <a href="index.php?action=groupcalendaredit&amp;month=<?= date('Y') . date('m') ?>&amp;region=1&amp;group=<?= $group['id'] ?>" class="btn btn-info btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_calendar'] ?></a>
                  <input name="hidden_id" type="hidden" value="<?= $group['id'] ?>">
                  <input name="hidden_name" type="hidden" value="<?= $group['name'] ?>">
                  <input name="hidden_description" type="hidden" value="<?= $group['description'] ?>">
                  <!-- Modal: Delete group -->
                  <?= createModalTop('modalDeleteGroup_' . $group['id'], $LANG['modal_confirm']) ?>
                  <?= $LANG['groups_confirm_delete'] . $group['name'] ?> ?
                  <?= createModalBottom('btn_groupDelete', 'danger', $LANG['btn_delete_group']) ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <script>
          $(document).ready(function () {
            $('#dataTableGroups').DataTable({
              paging: true,
              ordering: true,
              info: true,
              pageLength: 50,
              language: {
                url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
              },
              columnDefs: [
                {targets: [0, 7], orderable: false, searchable: false}
              ]
            });
          });
        </script>

      </div>
    </div>

  </div>
</div>
