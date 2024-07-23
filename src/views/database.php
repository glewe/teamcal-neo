<?php
if (!defined('VALID_ROOT')) { exit(''); }
/**
 * Database View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */
?>

<!-- ==================================================================== 
view.database
-->
<div class="container content">

    <div class="col-lg-12">
        <?php
        if ($showAlert and $C->read("showAlerts") != "none") {
            if (
                $C->read("showAlerts") == "all" or
                $C->read("showAlerts") == "warnings" and ($alertData['type'] == "warning" or $alertData['type'] == "danger")
            ) {
                echo createAlertBox($alertData);
            }
        } ?>
        <?php $tabindex = 1;
        $colsleft = 6;
        $colsright = 6; ?>

        <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

            <div class="card">
                <?php
                $pageHelp = '';
                if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                ?>
                <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['db_title'] ?><?= $pageHelp ?></div>
                <div class="card-body">

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab_optimize-tab" href="#tab_optimize" data-bs-toggle="tab" role="tab" aria-controls="tab_optimize" aria-selected="true"><?= $LANG['db_tab_optimize'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_cleanup-tab" href="#tab_cleanup" data-bs-toggle="tab" role="tab" aria-controls="tab_cleanup" aria-selected="false"><?= $LANG['db_tab_cleanup'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_repair-tab" href="#tab_repair" data-bs-toggle="tab" role="tab" aria-controls="tab_repair" aria-selected="false"><?= $LANG['db_tab_repair'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_delete-tab" href="#tab_delete" data-bs-toggle="tab" role="tab" aria-controls="tab_delete" aria-selected="false"><?= $LANG['db_tab_delete'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_admin-tab" href="#tab_admin" data-bs-toggle="tab" role="tab" aria-controls="tab_admin" aria-selected="false"><?= $LANG['db_tab_admin'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_reset-tab" href="#tab_reset" data-bs-toggle="tab" role="tab" aria-controls="tab_reset" aria-selected="false"><?= $LANG['db_tab_reset'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_tcpimp-tab" href="#tab_tcpimp" data-bs-toggle="tab" role="tab" aria-controls="tab_tcpimp" aria-selected="false"><?= $LANG['db_tab_tcpimp'] ?></a></li>
                    </ul>

                    <div id="myTabContent" class="tab-content">

                        <!-- Optimize Tables tab -->
                        <div class="tab-pane fade show active" id="tab_optimize" role="tabpanel" aria-labelledby="tab_optimize-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <strong><?= $LANG['db_optimize'] ?></strong>
                                            <div class="text-normal"><?= $LANG['db_optimize_comment'] ?></div>
                                        </div>
                                    </div>
                                    <div class="divider">
                                        <hr>
                                    </div>
                                    <button type="submit" class="btn btn-success" tabindex="<?= $tabindex++; ?>" name="btn_optimize"><?= $LANG['btn_optimize_tables'] ?></button>

                                </div>
                            </div>
                        </div>

                        <!-- Cleanup tab -->
                        <div class="tab-pane fade" id="tab_cleanup" role="tabpanel" aria-labelledby="tab_cleanup-tab">
                            <div class="card">
                                <div class="card-body">

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
                                            <input id="cleanBefore" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_cleanBefore" maxlength="10" value="<?= $viewData['cleanBefore'] ?>" type="text">
                                            <?php if (isset($inputAlert["cleanBefore"]) and strlen($inputAlert["cleanBefore"])) { ?>
                                                <br>
                                                <div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-bs-dismiss="alert"><i class="far fa-times-circle"></i></button><?= $inputAlert['cleanBefore'] ?></div>
                                            <?php } ?>
                                            <script>
                                                $(function() {
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
                                            <?php if (isset($inputAlert["cleanConfirm"]) and strlen($inputAlert["cleanConfirm"])) { ?>
                                                <br>
                                                <div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-bs-dismiss="alert"><i class="far fa-times-circle"></i></button><?= $inputAlert['cleanConfirm'] ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="divider">
                                        <hr>
                                    </div>

                                    <button type="submit" class="btn btn-warning" tabindex="<?= $tabindex++; ?>" name="btn_cleanup"><?= $LANG['btn_cleanup'] ?></button>

                                </div>
                            </div>
                        </div>

                        <!-- Repair tab -->
                        <div class="tab-pane fade" id="tab_repair" role="tabpanel" aria-labelledby="tab_repair-tab">
                            <div class="card">
                                <div class="card-body">

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
                                            <?php if (isset($inputAlert["repairConfirm"]) and strlen($inputAlert["repairConfirm"])) { ?>
                                                <br>
                                                <div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-bs-dismiss="alert"><i class="far fa-times-circle"></i></button><?= $inputAlert['repairConfirm'] ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="divider">
                                        <hr>
                                    </div>

                                    <button type="submit" class="btn btn-warning" tabindex="<?= $tabindex++; ?>" name="btn_repair"><?= $LANG['btn_repair'] ?></button>

                                </div>
                            </div>
                        </div>

                        <!-- Delete tab -->
                        <div class="tab-pane fade" id="tab_delete" role="tabpanel" aria-labelledby="tab_delete-tab">
                            <div class="card">
                                <div class="card-body">

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
                                            <?php if (isset($inputAlert["deleteConfirm"]) and strlen($inputAlert["deleteConfirm"])) { ?>
                                                <br>
                                                <div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-bs-dismiss="alert"><i class="far fa-times-circle"></i></button><?= $inputAlert['deleteConfirm'] ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="divider">
                                        <hr>
                                    </div>

                                    <button type="submit" class="btn btn-danger" tabindex="<?= $tabindex++; ?>" name="btn_delete"><?= $LANG['btn_delete_records'] ?></button>

                                </div>
                            </div>
                        </div>

                        <!-- Administration tab -->
                        <div class="tab-pane fade" id="tab_admin" role="tabpanel" aria-labelledby="tab_admin-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <strong><?= $LANG['db_dbURL'] ?></strong>
                                            <div class="text-normal"><?= $LANG['db_dbURL_comment'] ?></div>
                                        </div>
                                        <div class="col-lg-12 control-label">
                                            <input id="dbURL" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_dbURL" maxlength="160" value="<?= $viewData['dbURL'] ?>" type="text"><br>
                                            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_saveURL"><?= $LANG['btn_save'] ?></button>
                                        </div>
                                    </div>
                                    <?php if (strlen($viewData['dbURL']) and $viewData['dbURL'] != "#") { ?>
                                        <div class="divider">
                                            <hr>
                                        </div>
                                        <a href="<?= $C->read('dbURL') ?>" class="btn btn-info" tabindex="<?= $tabindex++; ?>" target="_blank"><?= $LANG['db_application'] ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Reset tab -->
                        <div class="tab-pane fade" id="tab_reset" role="tabpanel" aria-labelledby="tab_reset-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-danger"><?= $LANG['db_reset_danger'] ?></div>
                                    <div class="form-group row">
                                        <div class="col-lg-8">
                                            <div class="text-bold"><?= $LANG['db_resetString'] ?></div>
                                            <div class="text-normal"><?= $LANG['db_resetString_comment'] ?></div>
                                        </div>
                                        <div class="col-lg-4">
                                            <input id="dbResetString" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_dbResetString" maxlength="40" value="" type="text"><br>
                                        </div>
                                    </div>
                                    <div class="divider">
                                        <hr>
                                    </div>
                                    <button type="submit" class="btn btn-danger" tabindex="<?= $tabindex++; ?>" name="btn_reset"><?= $LANG['btn_reset_database'] ?></button>
                                </div>
                            </div>
                        </div>

                        <!-- TeamCal Pro Import tab -->
                        <div class="tab-pane fade" id="tab_tcpimp" role="tabpanel" aria-labelledby="tab_tcpimp-tab">
                            <div class="card">
                                <div class="card-body">
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
                                    <a href="index.php?action=tcpimport" class="btn btn-primary" tabindex="<?= $tabindex++; ?>"><?= $LANG['btn_import'] ?></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
