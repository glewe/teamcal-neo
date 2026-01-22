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
      (!empty($showAlert) && $viewData['showAlerts'] !== "none") &&
      ($viewData['showAlerts'] === "all" || ($viewData['showAlerts'] === "warnings" && (isset($alertData['type']) && ($alertData['type'] === "warning" || $alertData['type'] === "danger"))))
    ) {
      echo createAlertBox($alertData);
    }
    $tabindex = 0;
    ?>

    <div class="card">
      <?php
      // Performance optimization: Cache config values to avoid repeated lookups
      $panelColor = htmlspecialchars($CONF['controllers'][$controller]->panelColor ?? '', ENT_QUOTES, 'UTF-8');
      $faIcon = htmlspecialchars($CONF['controllers'][$controller]->faIcon ?? '', ENT_QUOTES, 'UTF-8');
      $rolesTitle = htmlspecialchars($LANG['roles_title'] ?? '', ENT_QUOTES, 'UTF-8');
      
      $pageHelp = '';
      if ($viewData['pageHelp']) {
        $docurl = htmlspecialchars($CONF['controllers'][$controller]->docurl ?? '', ENT_QUOTES, 'UTF-8');
        $pageHelp = '<a href="' . $docurl . '" target="_blank" class="float-end" style="color:inherit;" aria-label="Help"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header text-bg-<?= $panelColor ?>"><i class="<?= $faIcon ?> fa-lg me-3"></i><?= $rolesTitle . $pageHelp ?></div>

      <div class="card-body">

        <form class="form-control-horizontal" name="form_create" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
          <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
          <div class="row mb-4">
            <div class="col-lg-12 text-end">
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
          // Performance optimization: Cache protected roles array outside loop
          $protectedRoles = array(1, 2, 3);
          $i = 1;
          foreach (($viewData['roles'] ?? []) as $role) :
            $roleId = htmlspecialchars($role['id'] ?? '', ENT_QUOTES, 'UTF-8');
            $roleName = htmlspecialchars($role['name'] ?? '', ENT_QUOTES, 'UTF-8');
            $roleDescription = htmlspecialchars($role['description'] ?? '', ENT_QUOTES, 'UTF-8');
            $roleColor = htmlspecialchars($role['color'] ?? '', ENT_QUOTES, 'UTF-8');
            $isProtected = in_array($role['id'], $protectedRoles);
            ?>
            <tr>
              <td class="text-end"><?= $i++ ?></td>
              <td><i class="fas fa-user-circle fa-lg text-<?= $roleColor ?> me-2"></i><?= $roleName ?></td>
              <td><?= $roleDescription ?></td>
              <td class="align-top text-center">
                <form class="form-control-horizontal" name="form_<?= $roleName ?>" action="index.php?action=roles" method="post" target="_self" accept-charset="utf-8">
                  <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                  <?php
                  if (!$isProtected) { ?>
                    <button type="button" class="btn btn-danger btn-sm" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteRole_<?= $roleId ?>" aria-label="<?= htmlspecialchars($LANG['btn_delete'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                      <?= htmlspecialchars($LANG['btn_delete'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </button>
                    <input name="hidden_id" type="hidden" value="<?= $roleId ?>">
                    <input name="hidden_name" type="hidden" value="<?= $roleName ?>">
                    <input name="hidden_description" type="hidden" value="<?= $roleDescription ?>">
                  <?php } ?>
                  <a href="index.php?action=roleedit&amp;id=<?= $roleId ?>" class="btn btn-warning btn-sm" tabindex="<?= ++$tabindex ?>" aria-label="<?= htmlspecialchars($LANG['btn_edit'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($LANG['btn_edit'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                  </a>
                  <!-- Modal: Delete role -->
                  <?= createModalTop('modalDeleteRole_' . $roleId, htmlspecialchars($LANG['modal_confirm'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                  <?= $LANG['roles_confirm_delete'] . $roleName ?> ?
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
