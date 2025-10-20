<?php
/**
 * Roles View
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
view.roles
-->
<div class="container content">

  <div class="col-lg-12">
    <?php
    if (
      (!empty($showAlert) && $C->read("showAlerts") !== "none") &&
      ($C->read("showAlerts") === "all" || ($C->read("showAlerts") === "warnings" && (isset($alertData['type']) && ($alertData['type'] === "warning" || $alertData['type'] === "danger"))))
    ) {
      echo createAlertBox($alertData);
    }
    $tabindex = 0;
    ?>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $docurl = htmlspecialchars($CONF['controllers'][$controller]->docurl ?? '', ENT_QUOTES, 'UTF-8');
        $pageHelp = '<a href="' . $docurl . '" target="_blank" class="float-end" style="color:inherit;" aria-label="Help"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header text-bg-<?= htmlspecialchars($CONF['controllers'][$controller]->panelColor ?? '', ENT_QUOTES, 'UTF-8') ?>"><i class="<?= htmlspecialchars($CONF['controllers'][$controller]->faIcon ?? '', ENT_QUOTES, 'UTF-8') ?> fa-lg me-3"></i><?= htmlspecialchars($LANG['roles_title'] ?? '', ENT_QUOTES, 'UTF-8') . $pageHelp ?></div>

      <div class="card-body">

        <form class="form-control-horizontal" name="form_create" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
          <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
          <div class="row mb-4">
            <div class="col-lg-4">
              <label for="inputSearch"><?= htmlspecialchars($LANG['search'] ?? '', ENT_QUOTES, 'UTF-8') ?></label>
              <input id="inputSearch" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_searchRole" maxlength="40" value="<?= htmlspecialchars($viewData['searchRole'] ?? '', ENT_QUOTES, 'UTF-8') ?>" type="text" aria-label="<?= htmlspecialchars($LANG['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-lg-3">
              <br>
              <button type="submit" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>" name="btn_search" aria-label="<?= htmlspecialchars($LANG['btn_search'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($LANG['btn_search'] ?? '', ENT_QUOTES, 'UTF-8') ?></button>
              <a href="index.php?action=roles" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>" aria-label="<?= htmlspecialchars($LANG['btn_reset'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($LANG['btn_reset'] ?? '', ENT_QUOTES, 'UTF-8') ?></a>
            </div>
            <div class="col-lg-5 text-end">
              <br>
              <button type="button" class="btn btn-success" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalCreateRole" aria-label="<?= htmlspecialchars($LANG['btn_create_role'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($LANG['btn_create_role'] ?? '', ENT_QUOTES, 'UTF-8') ?>
              </button>
            </div>
          </div>

          <!-- Modal: Create role -->
          <?= createModalTop('modalCreateRole', $LANG['btn_create_role']) ?>
          <label for="inputName"><?= htmlspecialchars($LANG['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></label>
          <input id="inputName" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_name" maxlength="40" value="<?= htmlspecialchars($viewData['txt_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" type="text" aria-label="<?= htmlspecialchars($LANG['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
          <?php if (!empty($inputAlert["name"])) { ?>
            <br>
            <div class="alert alert-dismissable alert-danger" role="alert">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button><?= htmlspecialchars($inputAlert["name"], ENT_QUOTES, 'UTF-8') ?></div>
          <?php } ?>
          <label for="inputDescription"><?= htmlspecialchars($LANG['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></label>
          <input id="inputDescription" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_description" maxlength="100" value="<?= htmlspecialchars($viewData['txt_description'] ?? '', ENT_QUOTES, 'UTF-8') ?>" type="text" aria-label="<?= htmlspecialchars($LANG['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
          <?php if (!empty($inputAlert["description"])) { ?>
            <br>
            <div class="alert alert-dismissable alert-danger" role="alert">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button><?= htmlspecialchars($inputAlert["description"], ENT_QUOTES, 'UTF-8') ?></div>
          <?php } ?>
          <?= createModalBottom('btn_roleCreate', 'success', $LANG['btn_create_role']) ?>

        </form>

        <table id="dataTableRoles" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
          <thead>
          <tr>
            <th class="text-end">#</th>
            <th><?= htmlspecialchars($LANG['roles_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars($LANG['roles_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></th>
            <th class="text-center"><?= htmlspecialchars($LANG['action'] ?? '', ENT_QUOTES, 'UTF-8') ?></th>
          </tr>
          </thead>
          <tbody>
          <?php
          $i = 1;
          foreach (($viewData['roles'] ?? []) as $role) : ?>
            <tr>
              <td class="text-end"><?= $i++ ?></td>
              <td><i class="fas fa-user-circle fa-lg text-<?= htmlspecialchars($role['color'] ?? '', ENT_QUOTES, 'UTF-8') ?> me-2"></i><?= htmlspecialchars($role['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($role['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td class="align-top text-center">
                <form class="form-control-horizontal" name="form_<?= htmlspecialchars($role['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" action="index.php?action=roles" method="post" target="_self" accept-charset="utf-8">
                  <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                  <?php
                  $protectedRoles = array( 1, 2, 3 );
                  if (!in_array($role['id'], $protectedRoles)) { ?>
                    <button type="button" class="btn btn-danger btn-sm" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteRole_<?= htmlspecialchars($role['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars($LANG['btn_delete'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                      <?= htmlspecialchars($LANG['btn_delete'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </button>
                    <input name="hidden_id" type="hidden" value="<?= htmlspecialchars($role['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <input name="hidden_name" type="hidden" value="<?= htmlspecialchars($role['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <input name="hidden_description" type="hidden" value="<?= htmlspecialchars($role['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                  <?php } ?>
                  <a href="index.php?action=roleedit&amp;id=<?= htmlspecialchars($role['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm" tabindex="<?= ++$tabindex ?>" aria-label="<?= htmlspecialchars($LANG['btn_edit'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($LANG['btn_edit'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                  </a>
                  <!-- Modal: Delete role -->
                  <?= createModalTop('modalDeleteRole_' . htmlspecialchars($role['id'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($LANG['modal_confirm'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                  <?= htmlspecialchars(($LANG['roles_confirm_delete'] ?? '') . ($role['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?> ?
                  <?= createModalBottom('btn_roleDelete', 'danger', htmlspecialchars($LANG['btn_delete_role'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <script>
          $(document).ready(function () {
            $('#dataTableRoles').DataTable({
              paging: true,
              ordering: true,
              info: true,
              pageLength: 50,
              language: {
                url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
              },
              columnDefs: [
                {targets: [0, 3], orderable: false, searchable: false}
              ]
            });
          });
        </script>

      </div>
    </div>
  </div>
</div>
