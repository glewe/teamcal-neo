<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Attachments View
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
view.attachments
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
    $colsleft = 6;
    $colsright = 6;
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['att_title'] ?><?= $pageHelp ?></div>
        <div class="card-body">


          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                [ 'id' => 'tab-files', 'href' => '#tab_files', 'label' => $LANG['att_tab_files'], 'active' => true ],
                [ 'id' => 'tab-upload', 'href' => '#tab_upload', 'label' => $LANG['att_tab_upload'], 'active' => false ],
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Files -->
                <div class="tab-pane fade show active" id="tab_files" role="tabpanel" aria-labelledby="tab-files">

                  <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                    <div class="col-lg-5"><?= $LANG['att_col_file'] ?></div>
                    <div class="col-lg-2"><?= $LANG['att_col_owner'] ?></div>
                    <div class="col-lg-3"><?= $LANG['att_col_shares'] ?></div>
                    <div class="col-lg-2 text-end"><?= $LANG['action'] ?></div>
                  </div>

                  <?php foreach ($viewData['uplFiles'] as $file) {
                    if ($UL->username != 'admin' && $UL->username != $AT->getUploader($file['fname'])) {
                      $isOwner = false;
                    } else {
                      $isOwner = true;
                    }
                    ?>
                    <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                      <div class="col-lg-5">
                        <input class="form-check-input me-1 mt-2" name="chk_file[]" value="<?= $file['fname'] ?>" tabindex="<?= $tabindex++ ?>" type="checkbox" <?= (!$isOwner) ? "disabled" : ""; ?> class="me-2">
                        <?php if (in_array(getFileExtension($file['fname']), $CONF['imgExtensions'])) { ?>
                          <a class="image-popup" href="<?= APP_UPL_DIR . $file['fname'] ?>" title="<?= $file['fname'] ?>"><img src="<?= APP_UPL_DIR . $file['fname'] ?>" alt="" style="width: 24px; height: 24px;"></a>
                        <?php } else { ?>
                          <a href="<?= APP_UPL_DIR . $file['fname'] ?>"><img src="images/icons/mimetypes/<?= getFileExtension($file['fname']) ?>.png" alt="" style="width: 24px; height: 24px;"></a>
                        <?php } ?>
                        <?= $file['fname'] ?>
                      </div>
                      <div class="col-lg-2">
                        <?= $AT->getUploader($file['fname']) ?>
                      </div>
                      <div class="col-lg-3">
                        <a class="btn btn-secondary btn-sm text-white" data-bs-toggle="collapse" data-bs-target="#shares<?= $file['fid'] ?>"><?= $LANG['btn_shares'] ?></a>
                        <div class="collapse" id="shares<?= $file['fid'] ?>">
                          <select class="form-select" name="sel_shares<?= $file['fid'] ?>[]" multiple="multiple" size="10" tabindex="<?= $tabindex++ ?>" <?= (!$isOwner) ? "disabled" : ""; ?>>
                            <?php
                            foreach ($viewData['users'] as $user) {
                              if ($user['firstname'] != "") {
                                $showname = $user['lastname'] . ", " . $user['firstname'];
                              } else {
                                $showname = $user['lastname'];
                              }
                              ?>
                              <option class="option" value="<?= $user['username'] ?>" <?= ($UAT->hasAccess($user['username'], $file['fid'])) ? "selected" : ""; ?>><?= $showname ?></option>
                            <?php } ?>
                          </select>
                          <p class="small"><?= $LANG['att_owner_access'] ?></p>
                          <?php if ($isOwner) { ?>
                            <button type="submit" class="btn btn-success btn-sm" style="margin-top: 8px;" tabindex="<?= $tabindex++ ?>" name="btn_updateShares<?= $file['fid'] ?>"><?= $LANG['btn_update'] ?></button>
                            <button type="submit" class="btn btn-danger btn-sm" style="margin-top: 8px;" tabindex="<?= $tabindex++ ?>" name="btn_clearShares<?= $file['fid'] ?>"><?= $LANG['btn_clear'] ?></button>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="col-lg-2 text-end">
                        <a href="<?= APP_UPL_DIR . $file['fname'] ?>" class="btn btn-info btn-sm" tabindex="<?= $tabindex++ ?>" title="<?= $file['fname'] ?>"><?= $LANG['btn_download_view'] ?></a>
                      </div>
                    </div>
                  <?php } ?>

                  <div class="mt-4">
                    <button type="button" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteFiles"><?= $LANG['btn_delete_selected'] ?></button>
                  </div>

                </div>

                <!-- Upload File -->
                <div class="tab-pane fade" id="tab_upload" role="tabpanel" aria-labelledby="tab-upload">
                  <div class="form-group row">
                    <label class="col-lg-<?= $colsleft ?> control-label">
                      <?= $LANG['att_file'] ?><br>
                      <span class="text-normal"><?= sprintf($LANG['att_file_comment'], $viewData['upl_maxsize'] / 1024, $viewData['upl_formats'], APP_UPL_DIR) ?></span>
                    </label>
                    <div class="col-lg-<?= $colsright ?>">
                      <input type="hidden" name="MAX_FILE_SIZE" value="<?= $viewData['upl_maxsize'] ?>"><br>
                      <input class="form-control" tabindex="<?= $tabindex++ ?>" name="file_image" type="file"><br>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Share with -->
                  <div class="form-group row">
                    <label class="col-lg-<?= $colsleft ?> control-label">
                      <?= $LANG['att_shareWith'] ?><br>
                      <span class="text-normal"><?= $LANG['att_shareWith_comment'] ?></span>
                    </label>
                    <div class="col-lg-<?= $colsright ?>">
                      <div class="form-check"><label><input class="form-check-input" name="opt_shareWith" value="all" tabindex="<?= $tabindex++ ?>" <?= ($viewData['shareWith'] == 'all') ? "checked" : ""; ?> type="radio"><?= $LANG['att_shareWith_all'] ?></label></div>
                      <div class="form-check mt-2"><label><input class="form-check-input" name="opt_shareWith" value="group" tabindex="<?= $tabindex++ ?>" <?= ($viewData['shareWith'] == 'group') ? "checked" : ""; ?> type="radio"><?= $LANG['att_shareWith_group'] ?></label></div>
                      <select class="form-select" name="sel_shareWithGroup[]" multiple="multiple" size="5" tabindex="<?= $tabindex++ ?>">
                        <?php foreach ($viewData['groups'] as $group) { ?>
                          <option value="<?= $group['id'] ?>" <?= (in_array($group, $viewData['shareWithGroup'])) ? "selected" : ""; ?>><?= $group['name'] ?></option>
                        <?php } ?>
                      </select>

                      <div class="form-check mt-2"><label><input class="form-check-input" name="opt_shareWith" value="role" tabindex="<?= $tabindex++ ?>" <?= ($viewData['shareWith'] == 'role') ? "checked" : ""; ?> type="radio"><?= $LANG['att_shareWith_role'] ?></label></div>
                      <select class="form-select" name="sel_shareWithRole[]" multiple="multiple" size="5" tabindex="<?= $tabindex++ ?>">
                        <?php foreach ($viewData['roles'] as $role) { ?>
                          <option value="<?= $role['id'] ?>" <?= (in_array($group, $viewData['shareWithRole'])) ? "selected" : ""; ?>><?= $role['name'] ?></option>
                        <?php } ?>
                      </select>

                      <div class="form-check mt-2"><label><input class="form-check-input" name="opt_shareWith" value="user" tabindex="<?= $tabindex++ ?>" <?= ($viewData['shareWith'] == 'user') ? "checked" : ""; ?> type="radio"><?= $LANG['att_shareWith_user'] ?></label></div>
                      <select class="form-select" name="sel_shareWithUser[]" multiple="multiple" size="5" tabindex="<?= $tabindex++ ?>">
                        <?php
                        foreach ($viewData['users'] as $user) {
                          if ($user['firstname'] != "") {
                            $showname = $user['lastname'] . ", " . $user['firstname'];
                          } else {
                            $showname = $user['lastname'];
                          }
                          ?>
                          <option class="option" value="<?= $user['username'] ?>" <?= (in_array($user['username'], $viewData['shareWithUser'])) ? "selected" : ""; ?>><?= $showname ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="divider">
                    <hr>
                  </div>
                  <div style="clear: both; padding: 16px 0 0 16px;">
                    <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_uploadFile"><?= $LANG['btn_upload'] ?></button>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal: Delete selected files -->
      <?= createModalTop('modalDeleteFiles', $LANG['modal_confirm']) ?>
      <?= $LANG['att_confirm_delete'] ?>
      <?= createModalBottom('btn_deleteFile', 'danger', $LANG['btn_delete_selected']) ?>

    </form>

  </div>

</div>
