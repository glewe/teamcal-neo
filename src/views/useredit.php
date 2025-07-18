<?php
/**
 * User Edit View
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
?>
<!-- ====================================================================
view.useredit
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
    $colsleft = 8;
    $colsright = 4;
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;profile=<?= $viewData['profile'] ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['profile_edit_title'] . $viewData['fullname'] . $pageHelp ?></div>
        <div class="card-body">

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                [ 'id' => 'tab-personal', 'href' => '#panel-personal', 'label' => $LANG['profile_tab_personal'], 'active' => true ],
                [ 'id' => 'tab-contact', 'href' => '#panel-contact', 'label' => $LANG['profile_tab_contact'], 'active' => false ],
                [ 'id' => 'tab-passwowrd', 'href' => '#panel-password', 'label' => $LANG['profile_tab_password'], 'active' => false ]
              ];
              if (isAllowed("userabsences") && $viewData['profile'] != "admin") {
                $pageTabs[] = [ 'id' => 'tab-absences', 'href' => '#panel-absences', 'label' => $LANG['profile_tab_absences'], 'active' => false ];
              }
              if (isAllowed("useraccount") && $viewData['profile'] != "admin") {
                $pageTabs[] = [ 'id' => 'tab-account', 'href' => '#panel-account', 'label' => $LANG['profile_tab_account'], 'active' => false ];
              }
              if (isAllowed("useravatar")) {
                $pageTabs[] = [ 'id' => 'tab-avatar', 'href' => '#panel-avatar', 'label' => $LANG['profile_tab_avatar'], 'active' => false ];
              }
              if (isAllowed("usercustom") && $viewData['profile'] != "admin") {
                $pageTabs[] = [ 'id' => 'tab-custom', 'href' => '#panel-custom', 'label' => $LANG['profile_tab_custom'], 'active' => false ];
              }
              if (isAllowed("usergroups") && $viewData['profile'] != "admin") {
                $pageTabs[] = [ 'id' => 'tab-groups', 'href' => '#panel-groups', 'label' => $LANG['profile_tab_groups'], 'active' => false ];
              }
              if (isAllowed("usernotifications")) {
                $pageTabs[] = [ 'id' => 'tab-notifications', 'href' => '#panel-notifications', 'label' => $LANG['profile_tab_notifications'], 'active' => false ];
              }
              if (isAllowed("useroptions")) {
                $pageTabs[] = [ 'id' => 'tab-options', 'href' => '#panel-options', 'label' => $LANG['options'], 'active' => false ];
              }
              if (!$C->read('disableTfa')) {
                $pageTabs[] = [ 'id' => 'tab-tfa', 'href' => '#panel-tfa', 'label' => $LANG['profile_tab_tfa'], 'active' => false ];
              }
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Personal tab -->
                <div class="tab-pane fade show active" id="panel-personal" role="tabpanel" aria-labelledby="tab-personal">
                  <?php foreach ($viewData['personal'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <!-- Contact tab -->
                <div class="tab-pane fade" id="panel-contact" role="tabpanel" aria-labelledby="tab-contact">
                  <?php foreach ($viewData['contact'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <?php if (isAllowed("useroptions")) { ?>
                  <!-- Options tab -->
                  <div class="tab-pane fade" id="panel-options" role="tabpanel" aria-labelledby="tab-options">
                    <?php foreach ($viewData['options'] as $formObject) {
                      echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                    } ?>
                  </div>
                <?php } ?>

                <?php if (isAllowed("useravatar")) { ?>
                  <!-- Avatar tab -->
                  <div class="tab-pane fade" id="panel-avatar" role="tabpanel" aria-labelledby="tab-avatar">

                    <div class="form-group row">
                      <label class="col-lg-<?= $colsleft ?> control-label">
                        <?= $LANG['profile_avatar'] ?><br>
                        <span class="text-normal"><?= $LANG['profile_avatar_comment'] ?></span>
                      </label>
                      <div class="col-lg-<?= $colsright ?>">
                        <img src="<?= APP_AVATAR_DIR . $viewData['avatar'] ?>" alt="" style="width: 80px; height: 80px;"><br>
                        <br>
                        <?php if (substr($viewData['avatar'], 0, 9) != 'default_') { ?>
                          <button type="submit" class="btn btn-primary btn-sm" tabindex="<?= ++$tabindex ?>" name="btn_reset"><?= $LANG['btn_reset'] ?></button>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="divider">
                      <hr>
                    </div>

                    <div class="form-group row">
                      <label class="col-lg-<?= $colsleft ?> control-label">
                        <?= $LANG['profile_avatar_upload'] ?><br>
                        <span class="text-normal">
                          <?= sprintf($LANG['profile_avatar_upload_comment'], $viewData['avatar_maxsize'], $viewData['avatar_formats']) ?></span>
                      </label>
                      <div class="col-lg-<?= $colsright ?>">
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?= $viewData['avatar_maxsize'] ?>"><br>
                        <input class="form-control" tabindex="<?= ++$tabindex ?>" name="file_avatar" type="file"><br>
                        <button type="submit" class="btn btn-primary btn-sm" tabindex="<?= ++$tabindex ?>" name="btn_uploadAvatar"><?= $LANG['btn_upload'] ?></button>
                      </div>
                    </div>
                    <div class="divider">
                      <hr>
                    </div>

                    <div class="form-group row">
                      <label class="col-lg-12 control-label">
                        <?= $LANG['profile_avatar_available'] ?><br>
                        <span class="text-normal">
                          <?= $LANG['profile_avatar_available_comment'] ?></span>
                      </label>
                      <div class="col-lg-12">
                        <?php foreach ($viewData['avatars'] as $avatar) { ?>
                          <div class="float-start" style="border: 1px solid #eeeeee; padding: 4px;">
                            <input class="form-check-input" name="opt_avatar" value="<?= $avatar ?>" tabindex="<?= ++$tabindex ?>" <?= ($viewData['avatar'] == $avatar) ? ' checked="checked" ' : '' ?>type="radio">
                            <img src="<?= APP_AVATAR_DIR . $avatar ?>" alt="" style="width: 80px; height: 80px;">
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="divider">
                      <hr>
                    </div>

                  </div>
                <?php } ?>

                <?php if (isAllowed("useraccount") && $viewData['profile'] != "admin") { ?>
                  <!-- Account tab -->
                  <div class="tab-pane fade" id="panel-account" role="tabpanel" aria-labelledby="tab-account">
                    <?php foreach ($viewData['account'] as $formObject) {
                      echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                    } ?>
                  </div>
                <?php } ?>

                <?php if (isAllowed("usergroups")) { ?>
                  <!-- Groups tab -->
                  <div class="tab-pane fade" id="panel-groups" role="tabpanel" aria-labelledby="tab-groups">
                    <?php foreach ($viewData['groups'] as $formObject) {
                      echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                    } ?>
                  </div>
                <?php } ?>

                <!-- Password tab -->
                <div class="tab-pane fade" id="panel-password" role="tabpanel" aria-labelledby="tab-password">
                  <?php foreach ($viewData['password'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <?php if (isAllowed("userabsences") && $viewData['profile'] != "admin") { ?>
                  <!-- Absences tab -->
                  <div class="tab-pane fade" id="panel-absences" role="tabpanel" aria-labelledby="tab-absences">
                    <div class="form-group row">
                      <div class="col-lg-3"><strong><?= $LANG['profile_abs_name'] ?></strong></div>
                      <div class="col-lg-2">
                        <div class="text-bold text-center"><?= $LANG['profile_abs_allowance'] ?>&nbsp;
                          <?php if (isAllowed("userallowance")) { ?>
                            <?= iconTooltip($LANG['profile_abs_allowance_tt'], $LANG['profile_abs_allowance']) ?>
                          <?php } else { ?>
                            <?= iconTooltip($LANG['profile_abs_allowance_tt2'], $LANG['profile_abs_allowance']) ?>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="col-lg-2">
                        <div class="text-bold text-center"><?= $LANG['profile_abs_carryover'] ?>&nbsp;<?= iconTooltip($LANG['profile_abs_carryover_tt'], $LANG['profile_abs_carryover']) ?></div>
                      </div>
                      <div class="col-lg-2">
                        <div class="text-bold text-center"><?= $LANG['profile_abs_taken'] ?></div>
                      </div>
                      <div class="col-lg-1">
                        <div class="text-bold text-center"><?= $LANG['profile_abs_factor'] ?></div>
                      </div>
                      <div class="col-lg-2">
                        <div class="text-bold text-center"><?= $LANG['profile_abs_remainder'] ?></div>
                      </div>
                    </div>
                    <div class="divider">
                      <hr>
                    </div>
                    <?php foreach ($viewData['abs'] as $abs) { ?>
                      <div class="form-group row">
                        <div class="col-lg-3">
                          <div class="text-normal" style=""><i class="<?= $abs['icon'] ?>" style="color: #<?= $abs['color'] ?>; background-color: #<?= $abs['bgcolor'] ?>; border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 6px 4px 4px 4px; margin-right: 8px;"></i><?= $abs['name'] ?></div>
                        </div>
                        <?php if (isAllowed("userallowance")) { ?>
                          <div class="col-lg-2">
                            <div class="text-center"><input style="width:66%;float:left;" id="txt_<?= $abs['id'] ?>_allowance" class="form-control text-center" tabindex="<?= ++$tabindex ?>" name="txt_<?= $abs['id'] ?>_allowance" maxlength="3" value="<?= $abs['allowance'] ?>"> <span class="small">(<?= $abs['gallowance'] ?>)</span></div>
                          </div>
                        <?php } else { ?>
                          <div class="col-lg-2">
                            <div class="text-center"><?= $abs['allowance'] ?> (<?= $abs['gallowance'] ?>)</div>
                          </div>
                        <?php } ?>
                        <div class="col-lg-2">
                          <div class="text-center"><input id="txt_<?= $abs['id'] ?>_carryover" class="form-control text-center" tabindex="<?= ++$tabindex ?>" name="txt_<?= $abs['id'] ?>_carryover" maxlength="3" value="<?= $abs['carryover'] ?>"></div>
                        </div>
                        <div class="col-lg-2">
                          <div class="text-center"><?= $abs['taken'] ?></div>
                        </div>
                        <div class="col-lg-1">
                          <div class="text-center"><?= $abs['factor'] ?></div>
                        </div>
                        <div class="col-lg-2 <?= ($abs['remainder'] < 0 ? 'text-danger' : 'text-success') ?>">
                          <div class="text-center"><?= $abs['remainder'] ?></div>
                        </div>
                      </div>
                      <div class="divider">
                        <hr>
                      </div>
                    <?php } ?>
                  </div>
                <?php } ?>

                <?php if (isAllowed("usernotifications")) { ?>
                  <!-- Notifications tab -->
                  <div class="tab-pane fade" id="panel-notifications" role="tabpanel" aria-labelledby="tab-notifications">
                    <?php foreach ($viewData['notifications'] as $formObject) {
                      echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                    } ?>
                  </div>
                <?php } ?>

                <?php if (isAllowed("usercustom")) { ?>
                  <!-- Custom tab -->
                  <div class="tab-pane fade" id="panel-custom" role="tabpanel" aria-labelledby="tab-custom">
                    <?php foreach ($viewData['custom'] as $formObject) {
                      echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                    } ?>
                  </div>
                <?php } ?>

                <?php if (!$C->read('disableTfa')) { ?>
                  <!-- 2FA tab -->
                  <div class="tab-pane fade" id="panel-tfa" role="tabpanel" aria-labelledby="tab-tfa">
                    <?php if ($UO->read($UP->username, 'secret')) { ?>
                      <div class="form-group row" id="form-group-activateMessages">
                        <label for="activateMessages" class="col-lg-8 control-label"><?= $LANG['profile_remove2fa'] ?><br>
                          <span class="text-normal"><?= $LANG['profile_remove2fa_comment'] ?></span>
                        </label>
                        <div class="col-lg-4">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remove2fa" name="chk_remove2fa" value="chk_remove2fa" tabindex="<?= ++$tabindex ?>">
                            <label class="form-check-label"><?= $LANG['profile_remove2fa'] ?></label>
                          </div>
                        </div>
                      </div>
                    <?php } else { ?>
                      <div class="alert alert-info">
                        <?= $LANG['profile_2fa_optional'] ?>
                        <div><a href="index.php?action=setup2fa&profile=<?= $UP->username ?>" class="btn btn-secondary mt-2" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_setup2fa'] ?></a></div>
                      </div>
                    <?php } ?>
                  </div>
                <?php } ?>

              </div>
            </div>
          </div>

          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_profileUpdate"><?= $LANG['btn_update'] ?></button>
            <?php if (isAllowed("useraccount") && $viewData['profile'] != "admin") { ?>
              <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalArchiveProfile"><?= $LANG['btn_archive'] ?></button>
              <button type="button" class="btn btn-danger" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteProfile"><?= $LANG['btn_delete'] ?></button>
            <?php } ?>
            <?php if (isAllowed("useraccount")) { ?>
              <a href="index.php?action=users" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_user_list'] ?></a>
            <?php } ?>
          </div>

          <!-- Modal: Archive profile -->
          <?= createModalTop('modalArchiveProfile', $LANG['modal_confirm']) ?>
          <?= $LANG['profile_confirm_archive'] ?>
          <?= createModalBottom('btn_profileArchive', 'warning', $LANG['btn_archive']) ?>

          <!-- Modal: Delete profile -->
          <?= createModalTop('modalDeleteProfile', $LANG['modal_confirm']) ?>
          <?= $LANG['profile_confirm_delete'] ?>
          <?= createModalBottom('btn_profileDelete', 'danger', $LANG['btn_delete']) ?>

        </div>
      </div>

    </form>

  </div>

</div>
