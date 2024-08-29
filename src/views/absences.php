<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Absence Types View
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
view.absences
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
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['abs_list_title'] ?><?= $pageHelp ?></div>

      <div class="card-body">

        <form class="form-control-horizontal" name="form_create" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
          <?php
          $actionButtons = '
            <button type="button" class="btn btn-success float-end" tabindex="'. $tabindex++ .'" data-bs-toggle="modal" data-bs-target="#modalCreateAbsence">'. $LANG['btn_create_abs'] .'</button>';
          echo $actionButtons;
          ?>
          <div style="height:50px;"></div>

          <!-- Modal: Creates Absence -->
          <?= createModalTop('modalCreateAbsence', $LANG['btn_create_abs']) ?>
          <label for="inputName"><?= $LANG['name'] ?></label>
          <input id="inputName" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_name" maxlength="40" value="<?= $viewData['txt_name'] ?>" type="text">
          <?php if (isset($inputAlert["name"]) && strlen($inputAlert["name"])) { ?>
            <br>
            <div class="alert alert-dismissable alert-danger">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert">x</button><?= $inputAlert["name"] ?></div>
          <?php } ?>
          <?= createModalBottom('btn_absCreate', 'success', $LANG['btn_create_abs']) ?>

        </form>

        <table id="dataTableAbsences" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
          <thead>
          <tr>
            <th class="text-end">#</th>
            <th class="text-center"><?= $LANG['display'] ?></th>
            <th><?= $LANG['name'] ?></th>
            <th><?= $LANG['options'] ?></th>
            <th class="text-center"><?= $LANG['action'] ?></th>
          </tr>
          </thead>
          <tbody>
          <?php
          $i = 1;
          foreach ($viewData['absences'] as $absence) :
            if (!$absence['counts_as']) : ?>
              <tr>
                <td class="text-end"><?= $i++ ?></td>
                <td>
                  <?php
                  if ($absence['bgtrans']) {
                    $bgstyle = "";
                  } else {
                    $bgstyle = "background-color: #" . $absence['bgcolor'] . ";";
                  }
                  ?>
                  <span style="display: inline-block; color: #<?= $absence['color'] ?>;<?= $bgstyle ?>border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 4px;">
                    <?php if ($absence['icon'] != "No") { ?>
                      <span class="<?= $absence['icon'] ?>"></span>
                    <?php } else { ?>
                      <?= $absence['symbol'] ?>
                    <?php } ?>
                  </span>
                </td>
                <td><?= $absence['name'] ?></td>
                <td>
                  <?= (($absence['approval_required']) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . $LANG['abs_approval_required'] . '"><i class="far fa-edit fa-lg text-danger"></i></i>' : '') ?>
                  <?= (($absence['manager_only']) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . $LANG['abs_manager_only'] . '"><i class="fas fa-user-circle fa-lg text-warning"></i></i>' : '') ?>
                  <?= (($absence['hide_in_profile']) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . $LANG['abs_hide_in_profile'] . '"><i class="far fa-eye-slash fa-lg text-info"></i></i>' : '') ?>
                  <?= (($absence['confidential']) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . $LANG['abs_confidential'] . '"><i class="fas fa-exclamation-circle fa-lg text-success"></i></i>' : '') ?>
                  <?= (($absence['allowmonth'] || ($absence['allowweek'])) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . $LANG['abs_allow_active'] . '"><i class="far fa-hand-paper fa-lg text-warning"></i></i>' : '') ?>
                </td>
                <td class="align-top text-center">
                  <form class="form-control-horizontal" name="form_<?= $absence['id'] ?>" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
                    <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteAbsence_<?= $absence['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                    <a href="index.php?action=absenceedit&amp;id=<?= $absence['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_edit'] ?></a>
                    <input name="hidden_id" type="hidden" value="<?= $absence['id'] ?>">
                    <input name="hidden_name" type="hidden" value="<?= $absence['name'] ?>">
                    <!-- Modal: Delete Absence -->
                    <?= createModalTop('modalDeleteAbsence_' . $absence['id'], $LANG['modal_confirm']) ?>
                    <?= sprintf($LANG['abs_confirm_delete'], $absence['name']) ?>
                    <?= createModalBottom('btn_absDelete', 'danger', $LANG['btn_delete_abs']) ?>
                  </form>
                </td>
              </tr>
              <?php
              $subabsences = $A->getAllSub($absence['id']);
              foreach ($subabsences as $subabs) : ?>
                <tr>
                  <td class="text-end"><?= $i++ ?></td>
                  <td>
                    <?php
                    if ($subabs['bgtrans']) {
                      $bgstyle = "";
                    } else {
                      $bgstyle = "background-color: #" . $subabs['bgcolor'] . ";";
                    }
                    ?>
                    <i class="fas fa-angle-double-right me-3"></i>
                    <span style="display: inline-block; color: #<?= $subabs['color'] ?>;<?= $bgstyle ?>border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 4px;">
                      <?php if ($subabs['icon'] != "No") { ?>
                        <i class="<?= $subabs['icon'] ?>"></i>
                      <?php } else { ?>
                        <?= $subabs['symbol'] ?>
                      <?php } ?>
                    </span>
                  </td>
                  <td><?= $subabs['name'] ?> <i>(<?= $LANG['abs_counts_as'] ?>: <?= $absence['name'] ?>)</i></td>
                  <td></td>
                  <td class="align-top text-center">
                    <form class="form-control-horizontal" name="form_<?= $subabs['id'] ?>" action="index.php?action=absences" method="post" target="_self" accept-charset="utf-8">
                      <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteSubAbsence_<?= $subabs['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                      <a href="index.php?action=absenceedit&amp;id=<?= $subabs['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_edit'] ?></a>
                      <input name="hidden_id" type="hidden" value="<?= $subabs['id'] ?>">
                      <input name="hidden_name" type="hidden" value="<?= $subabs['name'] ?>">
                      <!-- Modal: Delete SubAbsence -->
                      <?= createModalTop('modalDeleteSubAbsence_' . $subabs['id'], $LANG['modal_confirm']) ?>
                      <?= sprintf($LANG['abs_confirm_delete'], $subabs['name']) ?>
                      <?= createModalBottom('btn_absDelete', 'danger', $LANG['btn_delete_abs']) ?>
                    </form>
                  </td>
                </tr>
              <?php endforeach; // subabs ?>
            <?php endif; // (!$absence['counts_as'])
          endforeach; // absence
          ?>
          </tbody>
        </table>
        <script>
          $(document).ready(function () {
            $('#dataTableAbsences').DataTable({
              paging: true,
              ordering: true,
              info: true,
              pageLength: 50,
              language: {
                url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
              },
              columnDefs: [
                {targets: [0, 3, 4], orderable: false, searchable: false}
              ]
            });
          });
        </script>

      </div>
    </div>
  </div>
</div>
