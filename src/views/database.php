<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Database View
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
view.database
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
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['db_title'] ?><?= $pageHelp ?></div>
        <div class="card-body">

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                ['id' => 'tab-optimize', 'href' => '#panel-optimize', 'label' => $LANG['db_tab_optimize'], 'active' => true],
                ['id' => 'tab-cleanup', 'href' => '#panel-cleanup', 'label' => $LANG['db_tab_cleanup'], 'active' => false],
                ['id' => 'tab-repair', 'href' => '#panel-repair', 'label' => $LANG['db_tab_repair'], 'active' => false],
                ['id' => 'tab-delete', 'href' => '#panel-delete', 'label' => $LANG['db_tab_delete'], 'active' => false],
                ['id' => 'tab-admin', 'href' => '#panel-admin', 'label' => $LANG['db_tab_admin'], 'active' => false],
                ['id' => 'tab-reset', 'href' => '#panel-reset', 'label' => $LANG['db_tab_reset'], 'active' => false],
                ['id' => 'tab-tcpimp', 'href' => '#panel-tcpimp', 'label' => $LANG['db_tab_tcpimp'], 'active' => false],
                ['id' => 'tab-dbinfo', 'href' => '#panel-dbinfo', 'label' => $LANG['db_tab_dbinfo'], 'active' => false],
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Optimize Tables tab -->
                <div class="tab-pane fade show active" id="panel-optimize" role="tabpanel" aria-labelledby="tab-optimize">
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <strong><?= $LANG['db_optimize'] ?></strong>
                      <div class="text-normal"><?= $LANG['db_optimize_comment'] ?></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <button type="submit" class="btn btn-success" tabindex="<?= $tabindex++ ?>" name="btn_optimize"><?= $LANG['btn_optimize_tables'] ?></button>
                </div>

                <!-- Cleanup tab -->
                <div class="tab-pane fade" id="panel-cleanup" role="tabpanel" aria-labelledby="tab-cleanup">
                  <div class="form-group row">
                    <div class="col-lg-<?= $colsleft ?>">
                      <div class="text-bold"><?= $LANG['db_clean_what'] ?></div>
                      <div class="text-normal"><?= $LANG['db_clean_what_comment'] ?></div>
                    </div>
                    <div class="col-lg-<?= $colsright ?>">
                      <div class="checkbox">
                        <label><input name="chk_cleanDaynotes" value="chk_cleanDaynotes" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_clean_daynotes'] ?></label>
                      </div>
                      <div class="checkbox">
                        <label><input name="chk_cleanMonths" value="chk_cleanMonths" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_clean_months'] ?></label>
                      </div>
                      <div class="checkbox">
                        <label><input name="chk_cleanTemplates" value="chk_cleanTemplates" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_clean_templates'] ?></label>
                      </div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-<?= $colsleft ?>">
                      <div class="text-bold"><?= $LANG['db_clean_before'] ?></div>
                      <div class="text-normal"><?= $LANG['db_clean_before_comment'] ?></div>
                    </div>
                    <div class="col-lg-<?= $colsright ?>">
                      <input id="cleanBefore" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_cleanBefore" maxlength="10" value="<?= $viewData['cleanBefore'] ?>" type="text">
                      <?php if (isset($inputAlert["cleanBefore"]) && strlen($inputAlert["cleanBefore"])) { ?>
                        <br>
                        <div class="alert alert-dismissable alert-danger">
                          <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['cleanBefore'] ?></div>
                      <?php } ?>
                      <script>
                        $(function () {
                          $("#cleanBefore").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: "yy-mm-dd"
                          });
                        });
                      </script>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-<?= $colsleft ?>">
                      <div class="text-bold"><?= $LANG['db_clean_confirm'] ?></div>
                      <div class="text-normal"><?= $LANG['db_clean_confirm_comment'] ?></div>
                    </div>
                    <div class="col-lg-<?= $colsright ?>">
                      <input class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_cleanConfirm" maxlength="7" value="" type="text">
                      <?php if (isset($inputAlert["cleanConfirm"]) && strlen($inputAlert["cleanConfirm"])) { ?>
                        <br>
                        <div class="alert alert-dismissable alert-danger">
                          <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['cleanConfirm'] ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <button type="submit" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" name="btn_cleanup"><?= $LANG['btn_cleanup'] ?></button>
                </div>

                <!-- Repair tab -->
                <div class="tab-pane fade" id="panel-repair" role="tabpanel" aria-labelledby="tab-repair">
                  <div class="form-group row">
                    <div class="col-lg-<?= $colsleft ?>">
                      <div class="text-bold"><?= $LANG['db_repair_daynoteRegions'] ?></div>
                      <div class="text-normal"><?= $LANG['db_repair_daynoteRegions_comment'] ?></div>
                    </div>
                    <div class="col-lg-<?= $colsright ?>">
                      <div class="checkbox">
                        <label><input name="chk_daynoteRegions" value="chk_daynoteRegions" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_repair_daynoteRegions'] ?></label>
                      </div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-<?= $colsleft ?>">
                      <div class="text-bold"><?= $LANG['db_repair_confirm'] ?></div>
                      <div class="text-normal"><?= $LANG['db_repair_confirm_comment'] ?></div>
                    </div>
                    <div class="col-lg-<?= $colsright ?>">
                      <input class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_repairConfirm" maxlength="7" value="" type="text">
                      <?php if (isset($inputAlert["repairConfirm"]) && strlen($inputAlert["repairConfirm"])) { ?>
                        <br>
                        <div class="alert alert-dismissable alert-danger">
                          <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['repairConfirm'] ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <button type="submit" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" name="btn_repair"><?= $LANG['btn_repair'] ?></button>
                </div>

                <!-- Delete tab -->
                <div class="tab-pane fade" id="panel-delete" role="tabpanel" aria-labelledby="tab-delete">
                  <div class="form-group row">
                    <div class="col-lg-<?= $colsleft ?>">
                      <div class="text-bold"><?= $LANG['db_del_what'] ?></div>
                      <div class="text-normal"><?= $LANG['db_del_what_comment'] ?></div>
                    </div>
                    <div class="col-lg-<?= $colsright ?>">
                      <div class="checkbox">
                        <label><input name="chk_delUsers" value="chk_delUsers" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_del_users'] ?></label>
                      </div>
                      <div class="checkbox">
                        <label><input name="chk_delGroups" value="chk_delGroups" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_del_groups'] ?></label>
                      </div>
                      <div class="checkbox">
                        <label><input name="chk_delMessages" value="chk_delMessages" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_del_messages'] ?></label>
                      </div>
                      <div class="checkbox">
                        <label><input name="chk_delOrphMessages" value="chk_delOrphMessages" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_del_orphMessages'] ?></label>
                      </div>
                      <div class="checkbox">
                        <label><input name="chk_delPermissions" value="chk_delPermissions" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_del_permissions'] ?></label>
                      </div>
                      <div class="checkbox">
                        <label><input name="chk_delLog" value="chk_delLog" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_del_log'] ?></label>
                      </div>
                      <div class="checkbox">
                        <label><input name="chk_delArchive" value="chk_delArchive" tabindex="<?= $tabindex++ ?>" type="checkbox"><?= $LANG['db_del_archive'] ?></label>
                      </div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-<?= $colsleft ?>">
                      <div class="text-bold"><?= $LANG['db_confirm'] ?></div>
                      <div class="text-normal"><?= $LANG['db_del_confirm_comment'] ?></div>
                    </div>
                    <div class="col-lg-<?= $colsright ?>">
                      <input class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_deleteConfirm" maxlength="6" value="" type="text">
                      <?php if (isset($inputAlert["deleteConfirm"]) && strlen($inputAlert["deleteConfirm"])) { ?>
                        <br>
                        <div class="alert alert-dismissable alert-danger">
                          <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['deleteConfirm'] ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <button type="submit" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" name="btn_delete"><?= $LANG['btn_delete_records'] ?></button>
                </div>

                <!-- Administration tab -->
                <div class="tab-pane fade" id="panel-admin" role="tabpanel" aria-labelledby="tab-admin">
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <strong><?= $LANG['db_dbURL'] ?></strong>
                      <div class="text-normal"><?= $LANG['db_dbURL_comment'] ?></div>
                    </div>
                    <div class="col-lg-12 control-label">
                      <input id="dbURL" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_dbURL" maxlength="160" value="<?= $viewData['dbURL'] ?>" type="text"><br>
                      <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_saveURL"><?= $LANG['btn_save'] ?></button>
                    </div>
                  </div>
                  <?php if (strlen($viewData['dbURL']) && $viewData['dbURL'] != "#") { ?>
                    <div class="divider">
                      <hr>
                    </div>
                    <a href="<?= $C->read('dbURL') ?>" class="btn btn-info" tabindex="<?= $tabindex++ ?>" target="_blank"><?= $LANG['db_application'] ?></a>
                  <?php } ?>
                </div>

                <!-- Reset tab -->
                <div class="tab-pane fade" id="panel-reset" role="tabpanel" aria-labelledby="tab-reset">
                  <div class="alert alert-danger"><?= $LANG['db_reset_danger'] ?></div>
                  <div class="form-group row">
                    <div class="col-lg-8">
                      <div class="text-bold"><?= $LANG['db_resetString'] ?></div>
                      <div class="text-normal"><?= $LANG['db_resetString_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <input id="dbResetString" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_dbResetString" maxlength="40" value="" type="text"><br>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <button type="submit" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" name="btn_reset"><?= $LANG['btn_reset_database'] ?></button>
                </div>

                <!-- TeamCal Pro Import tab -->
                <div class="tab-pane fade" id="panel-tcpimp" role="tabpanel" aria-labelledby="tab-tcpimp">
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <strong><?= $LANG['db_tcpimp'] ?></strong>
                      <div class="text-normal"><?= $LANG['db_tcpimp_comment'] ?></div>
                      <div class="text-normal">&nbsp;</div>
                      <strong><?= $LANG['db_tcpimp2'] ?></strong>
                      <div class="text-normal"><?= $LANG['tcpimp_info'] ?></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <a href="index.php?action=tcpimport" class="btn btn-primary" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_import'] ?></a>
                </div>

                <!-- Database Information tab -->
                <div class="tab-pane fade show" id="panel-dbinfo" role="tabpanel" aria-labelledby="tab-dbinfo">
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <?=$viewData['dbInfo'] ?>
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
