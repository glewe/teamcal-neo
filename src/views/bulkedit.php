<?php

/**
 * Bulk Edit View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.5.0
 */
?>
<!-- ====================================================================
view.bulkedit
-->
<div class="container content">

  <div class="col-lg-12">
    <?php
    if (
      ($showAlert && $viewData['showAlerts'] != "none") &&
      ($viewData['showAlerts'] == "all" || $viewData['showAlerts'] == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
    ) {
      echo createAlertBox($alertData);
    }
    resetTabindex();
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

      <input name="hidden_absid" type="hidden" class="text" value="<?= $viewData['absid'] ?>">
      <input name="hidden_groupid" type="hidden" class="text" value="<?= $viewData['groupid'] ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($viewData['pageHelp']) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-bg-<?= $CONF['controllers'][$controller]->panelColor ?>">
          <i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['bulkedit_title'] . ': ' . $viewData['abs']->name . $pageHelp ?>
        </div>

        <div class="card-body">

          <div class="row mb-4">
            <div class="col-lg-4">
              <label for="sel_absence"><?= $LANG['absencetype'] ?></label>
              <select class="form-select" name="sel_absence" id="sel_absence" tabindex="<?= nextTabindex() ?>">
                <?php foreach ($viewData['absences'] as $absence) { ?>
                  <option value="<?= $absence['id'] ?>" <?= ($absence['id'] == $viewData['absid']) ? ' selected=""' : ''; ?>><?= $absence['name'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-4">
              <label for="sel_group"><?= $LANG['group'] ?></label>
              <select class="form-select" name="sel_group" id="sel_group" tabindex="<?= nextTabindex() ?>">
                <option value="All" <?= ('All' == $viewData['groupid']) ? ' selected=""' : ''; ?>><?= $LANG['all'] ?></option>
                <?php foreach ($viewData['groups'] as $group) { ?>
                  <option value="<?= $group['id'] ?>" <?= ($group['id'] == $viewData['groupid']) ? ' selected=""' : ''; ?>><?= $group['name'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-4">
              <label>&nbsp;</label><br>
              <button type="submit" class="btn btn-info" tabindex="<?= nextTabindex() ?>" name="btn_load" aria-label="<?= $LANG['btn_load'] ?>"><?= $LANG['btn_load'] ?></button>
              <button type="submit" class="btn btn-success" tabindex="<?= nextTabindex() ?>" name="btn_reset" aria-label="<?= $LANG['btn_reset'] ?>"><?= $LANG['btn_reset'] ?></button>
            </div>
          </div>

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                ['id' => 'tab-absences', 'href' => '#panel-absences', 'label' => $LANG['profile_tab_absences'], 'active' => true],
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Absences tab -->
                <div class="tab-pane fade show active" id="panel-absences" role="tabpanel" aria-labelledby="tab-absences">
                  <div class="row">
                    <div class="col-lg-1"><strong><?= $LANG['select'] ?></strong><br>
                      <div class="form-check mt-2"><label><input class="form-check-input" type="checkbox" name="chk_selectAll" id="chk_selectAll"><?= $LANG['all'] ?></label></div>
                    </div>
                    <div class="col-lg-4"><strong><?= $LANG['user'] ?></strong></div>
                    <div class="col-lg-3"><strong><?= $LANG['absence'] ?></strong></div>
                    <div class="col-lg-2 text-center"><strong><?= $LANG['profile_abs_allowance'] ?></strong><br>
                      <div class="text-center">
                        <label for="txt_selected_<?= $viewData['absid'] ?>_allowance" class="text-italic"><?= $LANG['bulkedit_for_selected'] ?></label>
                        <input id="txt_selected_<?= $viewData['absid'] ?>_allowance" class="form-control text-center border-primary" tabindex="<?= nextTabindex() ?>" name="txt_selected_<?= $viewData['absid'] ?>_allowance" maxlength="3" value="">
                      </div>
                    </div>
                    <div class="col-lg-2 text-center"><strong><?= $LANG['profile_abs_carryover'] ?></strong><br>
                      <label class="text-italic"><?= $LANG['bulkedit_for_selected'] ?></label>
                      <div class="text-center"><input id="txt_selected_<?= $viewData['absid'] ?>_carryover" class="form-control text-center border-primary" tabindex="<?= nextTabindex() ?>" name="txt_selected_<?= $viewData['absid'] ?>_carryover" maxlength="3" value=""></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <?php
                  foreach ($viewData['bulkusers'] as $user) {
                    echo renderUserRow($user, $viewData['abs'], $viewData['absid']);
                  }
                  ?>
                </div>

              </div>
            </div>

          </div>

          <div class="mt-4 float-end">
            <button type="submit" class="btn btn-primary" tabindex="<?= nextTabindex() ?>" name="btn_bulkUpdate" aria-label="<?= $LANG['btn_update'] ?>"><?= $LANG['btn_update'] ?></button>
          </div>

        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $('#chk_selectAll').click(function(event) {
    const isChecked = this.checked;
    $(":checkbox[name='chk_userSelected[]']").prop('checked', isChecked);
  });
</script>

<?php
/**
 * Renders a user row for the bulk edit form.
 *
 * @param array $user   The user data array (must contain 'username', 'dispname', 'allowance', 'carryover')
 * @param object $abs   The absence type object (must contain 'name')
 * @param string|int $absid The absence type ID
 * @return string       The HTML for the user row
 */
function renderUserRow($user, $abs, $absid): string {
  ob_start();
?>
  <div class="row">
    <div class="col-lg-1">
      <?php if ($user['username'] != "admin") { ?>
        <input class="form-check-input ms-2" type="checkbox" name="chk_userSelected[]" value="<?= $user['username'] ?>">&nbsp;&nbsp;
      <?php } else { ?>
        <span style="padding-left: 16px;">&nbsp;</span>
      <?php } ?>
    </div>
    <div class="col-lg-4">
      <div class="text-normal"><?= $user['dispname'] ?></div>
    </div>
    <div class="col-lg-3">
      <div class="text-normal"><?= $abs->name ?></div>
    </div>
    <div class="col-lg-2">
      <div class="text-center"><input id="txt_<?= $user['username'] ?>_<?= $absid ?>_allowance" class="form-control text-center" tabindex="<?= nextTabindex() ?>" name="txt_<?= $user['username'] ?>_<?= $absid ?>_allowance" maxlength="3" value="<?= $user['allowance'] ?>"></div>
    </div>
    <div class="col-lg-2">
      <div class="text-center"><input id="txt_<?= $user['username'] ?>_<?= $absid ?>_carryover" class="form-control text-center" tabindex="<?= nextTabindex() ?>" name="txt_<?= $user['username'] ?>_<?= $absid ?>_carryover" maxlength="3" value="<?= $user['carryover'] ?>"></div>
    </div>
  </div>
  <div class="divider">
    <hr>
  </div>
<?php
  return ob_get_clean();
}
?>