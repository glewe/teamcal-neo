<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Permissions View
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
view.permissions
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

    <form class="form-control-horizontal" action="index.php?action=<?= $controller ?>&amp;scheme=<?= $viewData['scheme'] ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['perm_title'] . ': ' . $viewData['scheme'] . ' ' . (($viewData['scheme'] == $viewData['currentScheme']) ? $LANG['perm_active'] : $LANG['perm_inactive']) . $pageHelp ?></div>
        <div class="card-body">

          <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_permSave"><?= $LANG['perm_save_scheme'] ?></button>
          <button type="button" class="btn btn-info" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalSelectScheme"><?= $LANG['perm_select_scheme'] ?></button>
          <button type="button" class="btn btn-success" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalCreateScheme"><?= $LANG['perm_create_scheme'] ?></button>
          <button type="button" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalResetScheme"><?= $LANG['perm_reset_scheme'] ?></button>
          <?php if ($viewData['scheme'] != $viewData['currentScheme']) { ?>
            <button type="button" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalActivateScheme"><?= $LANG['perm_activate_scheme'] ?></button>
            <?php if ($viewData['scheme'] != "Default") { ?>
              <button type="button" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteScheme"><?= $LANG['perm_delete_scheme'] ?></button>
            <?php }
          } ?>
          <?php if ($viewData['mode'] == 'byrole') { ?>
            <a href="index.php?action=permissions&amp;scheme=<?= $viewData['scheme'] ?>&amp;mode=byperm" class="btn btn-secondary float-end" style="margin-right: 4px;" tabindex="<?= $tabindex++ ?>"><?= $LANG['perm_view_by_perm'] ?></a>
          <?php } else { ?>
            <a href="index.php?action=permissions&amp;scheme=<?= $viewData['scheme'] ?>&amp;mode=byrole" class="btn btn-secondary float-end" style="margin-right: 4px;" tabindex="<?= $tabindex++ ?>"><?= $LANG['perm_view_by_role'] ?></a>
          <?php } ?>

          <!-- Modal: Select scheme -->
          <?= createModalTop('modalSelectScheme', $LANG['perm_select_scheme']) ?>
          <select id="sel_scheme" class="form-select" name="sel_scheme">
            <?php foreach ($viewData['schemes'] as $schm) { ?>
              <option value="<?= $schm ?>" <?= (($schm == $viewData['scheme']) ? " selected" : "") ?>><?= $schm ?></option>
            <?php } ?>
          </select>
          <?= $LANG['perm_select_confirm'] ?>
          <?= createModalBottom('btn_permSelect', 'info', $LANG['btn_select']) ?>

          <!-- Modal: Create scheme -->
          <?= createModalTop('modalCreateScheme', $LANG['perm_create_scheme']) ?>
          <input class="form-control" name="txt_newScheme" maxlength="80" type="text" value="">
          <?= $LANG['perm_create_scheme_desc'] ?>
          <?= createModalBottom('btn_permCreate', 'success', $LANG['btn_create']) ?>

          <!-- Modal: Reset scheme -->
          <?= createModalTop('modalResetScheme', $LANG['perm_reset_scheme']) ?>
          <?= $LANG['perm_reset_confirm'] ?>
          <?= createModalBottom('btn_permReset', 'warning', $LANG['btn_reset']) ?>

          <!-- Modal: Activate scheme -->
          <?= createModalTop('modalActivateScheme', $LANG['modal_confirm']) ?>
          <?= $LANG['perm_activate_confirm'] ?>
          <?= createModalBottom('btn_permActivate', 'warning', $LANG['btn_activate']) ?>

          <!-- Modal: Delete scheme -->
          <?= createModalTop('modalDeleteScheme', $LANG['modal_confirm']) ?>
          <?= $viewData['scheme'] ?>":<br><?= $LANG['perm_delete_confirm'] ?>
          <?= createModalBottom('btn_permDelete', 'danger', $LANG['btn_delete']) ?>

          <div style="height:20px;"></div>

          <?php if ($viewData['mode'] == 'byrole') { ?>

            <div class="card">

              <div class="card-header">
                <?php
                foreach ($viewData['roles'] as $role) {
                  $pageTabs[] = [ 'id' => 'tab-' . $role['id'], 'href' => '#panel-' . $role['id'], 'label' => $role['name'], 'active' => (($role['id'] == 1) ? true : false) ];
                }
                echo createPageTabs($pageTabs);
                ?>
              </div>

              <div class="card-body">
                <div class="tab-content" id="myTabContent">

                  <?php foreach ($viewData['roles'] as $role) { ?>
                    <!-- Role <?= $role['name'] ?> tab -->
                    <div class="tab-pane fade show<?= (($role['id'] == 1) ? " active" : "") ?>" id="panel-<?= $role['id'] ?>" role="tabpanel" aria-labelledby="tab-<?= $role['id'] ?>">

                      <?php foreach ($viewData['permgroups'] as $permgroup => $permnames) {
                        $checked = 'checked="checked"';
                        foreach ($permnames as $permname) {
                          if (!$P->isAllowed($viewData['scheme'], $permname, $role['id'])) {
                            $checked = '';
                          }
                          ?>
                        <?php } ?>
                        <div class="form-check">
                          <label><input class="form-check-input" type="checkbox" name="chk_<?= $permgroup ?>_<?= $role['id'] ?>" value="chk_<?= $permgroup ?>_<?= $role['id'] ?>" tabindex="<?= $tabindex++ ?>" <?= $checked ?> <?= (($role['id'] == '1') ? 'disabled="disabled"' : '') ?>><strong><?= $LANG['perm_' . $permgroup . '_title'] ?></strong><br><?= $LANG['perm_' . $permgroup . '_desc'] ?></label>
                        </div>
                        <hr>
                      <?php } ?>

                      <?php foreach ($viewData['fperms'] as $fperm) { ?>
                        <div class="form-check">
                          <label><input class="form-check-input" type="checkbox" name="chk_<?= $fperm ?>_<?= $role['id'] ?>" value="chk_<?= $fperm ?>_<?= $role['id'] ?>" tabindex="<?= $tabindex++ ?>" <?= (($P->isAllowed($viewData['scheme'], $fperm, $role['id'])) ? " checked" : "") ?> <?= (($role['id'] == '1') ? 'disabled="disabled"' : '') ?>><strong><?= $LANG['perm_' . $fperm . '_title'] ?></strong><br><?= $LANG['perm_' . $fperm . '_desc'] ?></label>
                        </div>
                        <hr>
                      <?php } ?>

                    </div>
                  <?php } ?>
                </div>
              </div>

            </div>

          <?php } else { ?>

            <div class="card">

              <div class="card-header">
                <?php
                $pageTabs = [
                  ['id' => 'tab-general', 'href' => '#panel-general', 'label' => $LANG['perm_tab_general'], 'active' => true],
                  ['id' => 'tab-features', 'href' => '#panel-features', 'label' => $LANG['perm_tab_features'], 'active' => false],
                ];
                echo createPageTabs($pageTabs);
                ?>
              </div>

              <div class="card-body">
                <div class="tab-content" id="myTabContent">

                  <!-- Tab: General -->
                  <div class="tab-pane fade show active" id="panel-general" role="tabpanel" aria-labelledby="tab-general">
                    <?php foreach ($viewData['permgroups'] as $key => $pages) { ?>
                      <div class="form-group row">
                        <label class="col-lg-<?= $colsleft ?> control-label">
                          <?= $LANG['perm_' . $key . '_title'] ?><br>
                          <span class="text-normal"><?= $LANG['perm_' . $key . '_desc'] ?></span>
                        </label>
                        <div class="col-lg-<?= $colsright ?>">
                          <?php foreach ($viewData['roles'] as $role) {
                            $checked = 'checked="checked"';
                            foreach ($pages as $page) {
                              if (!$P->isAllowed($viewData['scheme'], $page, $role['id'])) {
                                $checked = '';
                              }
                            } ?>
                            <div class="form-check">
                              <label><input class="form-check-input" type="checkbox" name="chk_<?= $key ?>_<?= $role['id'] ?>" value="chk_<?= $key ?>_<?= $role['id'] ?>" tabindex="<?= $tabindex++ ?>" <?= $checked ?> <?= (($role['id'] == '1') ? 'disabled="disabled"' : '') ?>><?= $role['name'] ?></label>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="divider">
                        <hr>
                      </div>
                    <?php } ?>
                  </div>

                  <!-- Tab: Features -->
                  <div class="tab-pane fade show" id="panel-features" role="tabpanel" aria-labelledby="tab-features">
                    <?php foreach ($viewData['fperms'] as $fperm) { ?>
                      <div class="form-group row">
                        <label class="col-lg-<?= $colsleft ?> control-label">
                          <?= $LANG['perm_' . $fperm . '_title'] ?><br>
                          <span class="text-normal"><?= $LANG['perm_' . $fperm . '_desc'] ?></span>
                        </label>
                        <div class="col-lg-<?= $colsright ?>">
                          <?php foreach ($viewData['roles'] as $role) { ?>
                            <div class="form-check">
                              <label><input class="form-check-input" type="checkbox" name="chk_<?= $fperm ?>_<?= $role['id'] ?>" value="chk_<?= $fperm ?>_<?= $role['id'] ?>" tabindex="<?= $tabindex++ ?>" <?= (($P->isAllowed($viewData['scheme'], $fperm, $role['id'])) ? " checked" : "") ?> <?= (($role['id'] == '1') ? 'disabled="disabled"' : '') ?>><?= $role['name'] ?></label>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="divider">
                        <hr>
                      </div>
                    <?php } ?>
                  </div>

                </div>
              </div>

            </div>

          <?php } ?>

        </div>
      </div>

    </form>
  </div>
</div>
