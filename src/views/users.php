<?php
/**
 * Users View
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
view.users
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

    <form class="form-control-horizontal" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['users_title'] . $pageHelp ?></div>
        <div class="card-body">

          <div class="row">
            <div class="col-lg-3">
              <label for="inputSearch" id="labelInputSearch"><?= $LANG['search'] ?></label>
              <input id="inputSearch" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_searchUser" maxlength="40" value="<?= htmlspecialchars($viewData['searchUser'] ?? '', ENT_QUOTES, 'UTF-8') ?>" type="text" aria-labelledby="labelInputSearch" aria-label="<?= $LANG['search'] ?>">
            </div>
            <div class="col-lg-2">
              <label for="sel_searchGroup" id="labelSearchGroup"><?= $LANG['group'] ?></label>
              <select class="form-select" name="sel_searchGroup" id="sel_searchGroup" tabindex="<?= ++$tabindex ?>" aria-labelledby="labelSearchGroup" aria-label="<?= $LANG['group'] ?>">
                <option value="All" <?= ('All' === ($viewData['searchGroup'] ?? '')) ? ' selected=""' : ''; ?>><?= $LANG['all'] ?></option>
                <?php foreach (($viewData['groups'] ?? []) as $group) { ?>
                  <option value="<?= htmlspecialchars($group['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= ((($group['id'] ?? '') === ($viewData['searchGroup'] ?? '')) ? ' selected=""' : '') ?>><?= htmlspecialchars($group['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-2">
              <label for="sel_searchRole" id="labelSearchRole"><?= $LANG['role'] ?></label>
              <select class="form-select" name="sel_searchRole" id="sel_searchRole" tabindex="<?= ++$tabindex ?>" aria-labelledby="labelSearchRole" aria-label="<?= $LANG['role'] ?>">
                <option value="All" <?= ('All' === ($viewData['searchRole'] ?? '')) ? ' selected=""' : ''; ?>><?= $LANG['all'] ?></option>
                <?php foreach (($viewData['roles'] ?? []) as $role) { ?>
                  <option value="<?= htmlspecialchars($role['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= ((($role['id'] ?? '') === ($viewData['searchRole'] ?? '')) ? ' selected=""' : '') ?>><?= htmlspecialchars($role['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-5 text-end">
              <br>
              <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_search" aria-label="<?= $LANG['btn_search'] ?>"><?= $LANG['btn_search'] ?></button>
              <button type="submit" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>" name="btn_reset" aria-label="<?= $LANG['btn_reset'] ?>"><?= $LANG['btn_reset'] ?></button>
              <a href="index.php?action=useradd" class="btn btn-success" tabindex="<?= ++$tabindex ?>" aria-label="<?= $LANG['btn_create_user'] ?>"><?= $LANG['btn_create_user'] ?></a>
              <a href="index.php?action=userimport" class="btn btn-info" tabindex="<?= ++$tabindex ?>" aria-label="<?= $LANG['btn_import'] ?>"><?= $LANG['btn_import'] ?></a>
            </div>
          </div>
          <div style="height:20px;"></div>


          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                [ 'id' => 'tab-active', 'href' => '#panel-active', 'label' => $LANG['users_tab_active'], 'active' => true ],
                [ 'id' => 'tab-archived', 'href' => '#panel-archived', 'label' => $LANG['users_tab_archived'], 'active' => false ],
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Active tab -->
                <div class="tab-pane fade show active" id="panel-active" role="tabpanel" aria-labelledby="tab-active">

                  <table id="dataTableUsers" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
                    <thead>
                    <tr>
                      <th class="text-center"><i class="fas fa-check"></i></th>
                      <th><?= $LANG['users_user'] ?></th>
                      <th><?= $LANG['users_attributes'] ?></th>
                      <th><?= $LANG['users_created'] ?></th>
                      <th><?= $LANG['users_last_login'] ?></th>
                      <th class="text-center"><?= $LANG['action'] ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (($viewData['users'] ?? []) as $user) : ?>
                      <tr>
                        <td class="align-top text-center">
                          <?php if (($user['username'] ?? '') !== "admin") { ?>
                            <input class="form-check-input" type="checkbox" name="chk_userActive[]" value="<?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                          <?php } ?>
                        </td>
                        <td class="align-top">
                          <i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" data-bs-title="<img src='<?= htmlspecialchars(APP_AVATAR_DIR . $UO->read($user['username'] ?? '', 'avatar'), ENT_QUOTES, 'UTF-8') ?>' style='width: 80px; height: 80px;'>"><img src="<?= htmlspecialchars(APP_AVATAR_DIR . '/' . $UO->read($user['username'] ?? '', 'avatar'), ENT_QUOTES, 'UTF-8') ?>" alt="" style="width: 16px; height: 16px;"></i>&nbsp;&nbsp;<?= htmlspecialchars($user['dispname'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td class="align-top">
                          <a href="#" data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="<?= $LANG['role'] ?>: <?= htmlspecialchars($user['role'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-user-circle fa-sm text-<?= htmlspecialchars($user['color'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></i></a>
                          <?= (($user['locked'] ?? false) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['users_attribute_locked'], ENT_QUOTES, 'UTF-8') . '"><i class="fas fa-lock fa-sm text-danger"></i></i>' : '') ?>
                          <?= (($user['onhold'] ?? false) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['users_attribute_onhold'], ENT_QUOTES, 'UTF-8') . '"><i class="far fa-clock fa-sm text-warning"></i></i>' : '') ?>
                          <?= (($user['verify'] ?? false) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['users_attribute_verify'], ENT_QUOTES, 'UTF-8') . '"><i class="fas fa-exclamation-circle fa-sm text-success"></i></i>' : '') ?>
                          <?= (($user['hidden'] ?? false) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['users_attribute_hidden'], ENT_QUOTES, 'UTF-8') . '"><i class="far fa-eye-slash fa-sm text-info"></i></i>' : '') ?>
                          <?= (($UO->read($user['username'] ?? '', 'secret')) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . htmlspecialchars($LANG['users_attribute_secret'], ENT_QUOTES, 'UTF-8') . '"><i class="fa fa-shield fa-sm text-secondary"></i></i>' : '') ?>
                        </td>
                        <td class="align-top"><?= $user['created'] ?></td>
                        <td class="align-top"><?= (($user['last_login'] != DEFAULT_TIMESTAMP) ? $user['last_login'] : "") ?></td>
                        <td class="align-top text-center">
                          <a href="index.php?action=viewprofile&amp;profile=<?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary btn-sm" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_view'] ?></a>
                          <a href="index.php?action=useredit&amp;profile=<?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_edit'] ?></a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
                  <script>
                    $(document).ready(function () {
                      $('#dataTableUsers').DataTable({
                        paging: true,
                        ordering: true,
                        info: true,
                        pageLength: 50,
                        language: {
                          url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
                        },
                        columnDefs: [
                          {targets: [0, 2, 5], orderable: false, searchable: false}
                        ]
                      });
                    });
                  </script>

                  <div class="row mt-3 pb-0">
                    <div class="col-lg-2">
                      <div class="form-check"><label for="chk_selectAllActive"><input class="form-check-input" type="checkbox" name="chk_selectAllActive" id="chk_selectAllActive" aria-label="<?= $LANG['select_all'] ?>"><?= $LANG['select_all'] ?></label></div>
                    </div>
                    <div class="col-lg-10 text-end">

                      <button type="button" class="btn btn-info" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalRemoveSecret"><?= $LANG['btn_remove_secret_selected'] ?></button>
                      <button type="button" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalResetPassword"><?= $LANG['btn_reset_password_selected'] ?></button>
                      <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalArchiveSelected"><?= $LANG['btn_archive_selected'] ?></button>
                      <button type="button" class="btn btn-danger" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteSelected"><?= $LANG['btn_delete_selected'] ?></button>
                      <button type="button" class="btn btn-success" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalActivateSelected"><?= $LANG['btn_activate_selected'] ?></button>

                      <!-- Modal: Activate selected -->
                      <?= createModalTop('modalActivateSelected', $LANG['modal_confirm']) ?>
                      <?= $LANG['users_confirm_activate'] ?>
                      <?= createModalBottom('btn_userActivate', 'success', $LANG['btn_activate_selected']) ?>

                      <!-- Modal: Archive selected -->
                      <?= createModalTop('modalArchiveSelected', $LANG['modal_confirm']) ?>
                      <?= $LANG['users_confirm_archive'] ?>
                      <?= createModalBottom('btn_userArchive', 'warning', $LANG['btn_archive_selected']) ?>

                      <!-- Modal: Delete selected -->
                      <?= createModalTop('modalDeleteSelected', $LANG['modal_confirm']) ?>
                      <?= $LANG['users_confirm_delete'] ?>
                      <?= createModalBottom('btn_profileDelete', 'danger', $LANG['btn_delete_selected']) ?>

                      <!-- Model: Reset password -->
                      <?= createModalTop('modalResetPassword', $LANG['modal_confirm']) ?>
                      <?= $LANG['users_confirm_password'] ?>
                      <?= createModalBottom('btn_userResetPassword', 'primary', $LANG['btn_reset_password_selected']) ?>

                      <!-- Model: Remove secret -->
                      <?= createModalTop('modalRemoveSecret', $LANG['modal_confirm']) ?>
                      <?= $LANG['users_confirm_secret'] ?>
                      <?= createModalBottom('btn_userRemoveSecret', 'primary', $LANG['btn_remove_secret_selected']) ?>

                    </div>
                  </div>
                </div>

                <!-- Archived tab -->
                <div class="tab-pane fade" id="panel-archived" role="tabpanel" aria-labelledby="tab-archived">

                  <table id="dataTableUsersArchived" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
                    <thead>
                    <tr>
                      <th class="text-center"><i class="fas fa-check"></i></th>
                      <th><?= $LANG['users_user'] ?></th>
                      <th><?= $LANG['users_attributes'] ?></th>
                      <th><?= $LANG['users_created'] ?></th>
                      <th><?= $LANG['users_last_login'] ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($viewData['users1'] as $user1) : ?>
                      <tr>
                        <td class="align-top text-center">
                          <?php if (($user1['username'] ?? '') !== "admin") { ?>
                            <input class="form-check-input" type="checkbox" name="chk_userArchived[]" value="<?= $user1['username'] ?>">&nbsp;&nbsp;
                          <?php } ?>
                        </td>
                        <td class="align-top">
                          <i class="me-2" data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" data-bs-title="<img src='<?= APP_AVATAR_DIR . $UO->read($user1['username'], 'avatar', true) ?>' style='width: 80px; height: 80px;'>">
                            <img src="<?= APP_AVATAR_DIR ?>/<?= $UO->read($user1['username'], 'avatar', true) ?>" alt="" style="width: 16px; height: 16px;">
                          </i><?= $user1['dispname'] ?>
                        </td>
                        <td class="align-top">
                          <a href="#" data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="<?= $LANG['role'] ?>: <?= $user1['role'] ?>"><i class="fas fa-user-circle fa-sm text-<?= $user1['color'] ?>"></i></a>
                          <?= (($user1['locked']) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . $LANG['users_attribute_locked'] . '"><i class="fas fa-lock fa-sm text-danger"></i></i>' : '') ?>
                          <?= (($user1['onhold']) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . $LANG['users_attribute_onhold'] . '"><i class="far fa-clock fa-sm text-warning"></i></i>' : '') ?>
                          <?= (($user1['verify']) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . $LANG['users_attribute_verify'] . '"><i class="fas fa-exclamation-circle fa-sm text-success"></i></i>' : '') ?>
                          <?= (($user1['hidden']) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . $LANG['users_attribute_hidden'] . '"><i class="far fa-eye-slash fa-sm text-info"></i></i>' : '') ?>
                          <?= (($UO->read($user1['username'], 'secret')) ? '<i data-bs-placement="top" data-bs-type="info" data-bs-toggle="tooltip" title="' . $LANG['users_attribute_secret'] . '"><i class="fa fa-shield fa-sm text-secondary"></i></i>' : '') ?>
                        </td>
                        <td class="align-top"><?= $user1['created'] ?></td>
                        <td class="align-top"><?= $user1['last_login'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
                  <script>
                    //
                    // DataTables init
                    //
                    $(document).ready(function () {
                      $('#dataTableUsersArchived').DataTable({
                        paging: true,
                        ordering: true,
                        info: true,
                        pageLength: 50,
                        language: {
                          url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
                        },
                        columnDefs: [
                          {targets: [0, 2], orderable: false, searchable: false}
                        ]
                      });
                    });
                  </script>

                  <div class="row" style="margin-top: 10px; padding-bottom: 0px;">
                    <div class="col-lg-2">
                      <div class="form-check"><label for="chk_selectAllArchived"><input class="form-check-input" type="checkbox" name="chk_selectAllArchived" id="chk_selectAllArchived" aria-label="<?= $LANG['select_all'] ?>"><?= $LANG['select_all'] ?></label></div>
                    </div>
                    <div class="col-lg-10 text-end">
                      <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalRestoreArchived"><?= $LANG['btn_restore_selected'] ?></button>
                      <button type="button" class="btn btn-danger" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteArchived"><?= $LANG['btn_delete_selected'] ?></button>
                      <!-- Modal: Delete archived -->
                      <?= createModalTop('modalDeleteArchived', $LANG['modal_confirm']) ?>
                      <?= $LANG['users_confirm_delete'] ?>
                      <?= createModalBottom('btn_profileDeleteArchived', 'danger', $LANG['btn_delete_selected']) ?>
                      <!-- Modal: Restore archived -->
                      <?= createModalTop('modalRestoreArchived', $LANG['modal_confirm']) ?>
                      <?= $LANG['users_confirm_restore'] ?>
                      <?= createModalBottom('btn_profileRestore', 'warning', $LANG['btn_restore_selected']) ?>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $('#chk_selectAllActive').click(function (event) {
    if (this.checked) {
      $(":checkbox[name='chk_userActive[]']").each(function () {
        this.checked = true;
      });
    } else {
      $(":checkbox[name='chk_userActive[]']").each(function () {
        this.checked = false;
      });
    }
  });
  $('#chk_selectAllArchived').click(function (event) {
    if (this.checked) {
      $(":checkbox[name='chk_userArchived[]']").each(function () {
        this.checked = true;
      });
    } else {
      $(":checkbox[name='chk_userArchived[]']").each(function () {
        this.checked = false;
      });
    }
  });
</script>
