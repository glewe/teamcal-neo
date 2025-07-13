<?php
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
    $tabindex = 0;
    ?>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['abs_list_title'] ?><?= $pageHelp ?></div>

      <div class="card-body">

        <form class="row form-control-horizontal" name="form_create" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
          <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

          <div class="mb-4">
            <button type="button" class="btn btn-success float-end" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalCreateAbsence" aria-label="<?= $LANG['btn_create_abs'] ?>"><?= $LANG['btn_create_abs'] ?></button>
          </div>

          <!-- Modal: Creates Absence -->
          <?= createModalTop('modalCreateAbsence', $LANG['btn_create_abs']) ?>
          <label for="inputName"><?= $LANG['name'] ?></label>
          <input id="inputName" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_name" maxlength="40" value="<?= htmlspecialchars($viewData['txt_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" type="text">
          <?php if (isset($inputAlert["name"]) && strlen($inputAlert["name"])) { ?>
            <br>
            <div class="alert alert-dismissable alert-danger">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert">x</button><?= htmlspecialchars($inputAlert["name"], ENT_QUOTES, 'UTF-8') ?></div>
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
            if (!($absence['counts_as'] ?? false)) : ?>
              <tr>
                <td class="text-end"><?= $i++ ?></td>
                <td>
                  <?php
                  $bgstyle = (isset($absence['bgtrans']) && $absence['bgtrans']) ? "" : (isset($absence['bgcolor']) ? "background-color: #" . htmlspecialchars($absence['bgcolor'], ENT_QUOTES, 'UTF-8') . ";" : "");
                  ?>
                  <span style="display: inline-block; color: #<?= htmlspecialchars($absence['color'] ?? '', ENT_QUOTES, 'UTF-8') ?>;<?= $bgstyle ?>border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 4px;">
                    <?php if (($absence['icon'] ?? '') !== "No") { ?>
                      <span class="<?= htmlspecialchars($absence['icon'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></span>
                    <?php } else { ?>
                      <?= htmlspecialchars($absence['symbol'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    <?php } ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($absence['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <?= (($absence['approval_required'] ?? false) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['abs_approval_required'], ENT_QUOTES, 'UTF-8') . '"><i class="far fa-edit fa-lg text-danger"></i></i>' : '') ?>
                  <?= (($absence['manager_only'] ?? false) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['abs_manager_only'], ENT_QUOTES, 'UTF-8') . '"><i class="fas fa-user-circle fa-lg text-warning"></i></i>' : '') ?>
                  <?= (($absence['hide_in_profile'] ?? false) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['abs_hide_in_profile'], ENT_QUOTES, 'UTF-8') . '"><i class="far fa-eye-slash fa-lg text-info"></i></i>' : '') ?>
                  <?= (($absence['confidential'] ?? false) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['abs_confidential'], ENT_QUOTES, 'UTF-8') . '"><i class="fas fa-exclamation-circle fa-lg text-success"></i></i>' : '') ?>
                  <?= ((($absence['allowmonth'] ?? false) || ($absence['allowweek'] ?? false)) ? '<i data-bs-placement="top" data-bs-custom-class="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['abs_allow_active'], ENT_QUOTES, 'UTF-8') . '"><i class="far fa-hand-paper fa-lg text-warning"></i></i>' : '') ?>
                </td>
                <td class="align-top text-center">
                  <form class="form-control-horizontal" name="form_<?= htmlspecialchars($absence['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" action="index.php?action=<?= htmlspecialchars($CONF['controllers'][$controller]->name ?? '', ENT_QUOTES, 'UTF-8') ?>" method="post" target="_self" accept-charset="utf-8">
                    <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <button type="button" class="btn btn-danger btn-sm" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteAbsence_<?= htmlspecialchars($absence['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= $LANG['btn_delete'] ?></button>
                    <a href="index.php?action=absenceedit&amp;id=<?= htmlspecialchars($absence['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_edit'] ?></a>
                    <input name="hidden_id" type="hidden" value="<?= htmlspecialchars($absence['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <input name="hidden_name" type="hidden" value="<?= htmlspecialchars($absence['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <!-- Modal: Delete Absence -->
                    <?= createModalTop('modalDeleteAbsence_' . htmlspecialchars($absence['id'] ?? '', ENT_QUOTES, 'UTF-8'), $LANG['modal_confirm']) ?>
                    <?= sprintf($LANG['abs_confirm_delete'], htmlspecialchars($absence['name'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
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
                    $bgstyle = (isset($subabs['bgtrans']) && $subabs['bgtrans']) ? "" : (isset($subabs['bgcolor']) ? "background-color: #" . htmlspecialchars($subabs['bgcolor'], ENT_QUOTES, 'UTF-8') . ";" : "");
                    ?>
                    <i class="fas fa-angle-double-right me-3"></i>
                    <span style="display: inline-block; color: #<?= htmlspecialchars($subabs['color'] ?? '', ENT_QUOTES, 'UTF-8') ?>;<?= $bgstyle ?>border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 4px;">
                      <?php if (($subabs['icon'] ?? '') != "No") { ?>
                        <i class="<?= htmlspecialchars($subabs['icon'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></i>
                      <?php } else { ?>
                        <?= htmlspecialchars($subabs['symbol'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                      <?php } ?>
                    </span>
                  </td>
                  <td><?= htmlspecialchars($subabs['name'] ?? '', ENT_QUOTES, 'UTF-8') ?> <i>(<?= htmlspecialchars($LANG['abs_counts_as'], ENT_QUOTES, 'UTF-8') ?>: <?= htmlspecialchars($absence['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>)</i></td>
                  <td></td>
                  <td class="align-top text-center">
                    <form class="form-control-horizontal" name="form_<?= htmlspecialchars($subabs['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" action="index.php?action=absences" method="post" target="_self" accept-charset="utf-8">
                      <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                      <button type="button" class="btn btn-danger btn-sm" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteSubAbsence_<?= htmlspecialchars($subabs['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= $LANG['btn_delete'] ?></button>
                      <a href="index.php?action=absenceedit&amp;id=<?= htmlspecialchars($subabs['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_edit'] ?></a>
                      <input name="hidden_id" type="hidden" value="<?= htmlspecialchars($subabs['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                      <input name="hidden_name" type="hidden" value="<?= htmlspecialchars($subabs['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                      <!-- Modal: Delete SubAbsence -->
                      <?= createModalTop('modalDeleteSubAbsence_' . htmlspecialchars($subabs['id'] ?? '', ENT_QUOTES, 'UTF-8'), $LANG['modal_confirm']) ?>
                      <?= sprintf($LANG['abs_confirm_delete'], htmlspecialchars($subabs['name'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
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
