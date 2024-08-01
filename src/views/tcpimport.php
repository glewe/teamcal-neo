<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * TCPro Import View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */
?>

<!-- ====================================================================
view.tcpimport
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

    <form class="form-control-horizontal" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['tcpimp_title'] . $pageHelp ?></div>

        <div class="card-body">


          <div class="card">

            <div class="card-header">
              <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation"><a class="nav-link active" id="tab_info-tab" href="#tab_info" data-bs-toggle="tab" role="tab" aria-controls="tab_info" aria-selected="true"><?= $LANG['tcpimp_tab_info'] ?></a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="tab_tcpdb-tab" href="#tab_tcpdb" data-bs-toggle="tab" role="tab" aria-controls="tab_tcpdb" aria-selected="false"><?= $LANG['tcpimp_tab_tcpdb'] ?></a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="tab_import-tab" href="#tab_import" data-bs-toggle="tab" role="tab" aria-controls="tab_import" aria-selected="false"><?= $LANG['tcpimp_tab_import'] ?></a></li>
              </ul>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Information tab -->
                <div class="tab-pane fade show active" id="tab_info" role="tabpanel" aria-labelledby="tab_info-tab">
                  <div class="alert alert-danger" role="alert">
                    <span class="fas fa-exclamation-circle" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    <?= $LANG['attention'] ?>
                  </div>
                  <div class="text-bold"><?= $LANG['tcpimp_title'] ?></div>
                  <div class="text-normal"><?= $LANG['tcpimp_info'] ?></div>
                </div>

                <!-- TeamCal Pro database tab -->
                <div class="tab-pane fade" id="tab_tcpdb" role="tabpanel" aria-labelledby="tab_tcpdb-tab">

                  <!-- DB Server -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_tcp_dbServer'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_tcp_dbServer_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <input id="txt_tcpDbServer" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_tcpDbServer" type="text" maxlength="160" value="<?= $viewData['tcpDbServer'] ?>">
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- DB Name -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_tcp_dbName'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_tcp_dbName_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <input id="txt_tcpDbName" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_tcpDbName" type="text" maxlength="40" value="<?= $viewData['tcpDbName'] ?>">
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- DB User -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_tcp_dbUser'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_tcp_dbUser_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <input id="txt_tcpDbUser" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_tcpDbUser" type="text" maxlength="40" value="<?= $viewData['tcpDbUser'] ?>">
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- DB Password -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_tcp_dbPassword'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_tcp_dbPassword_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <input id="txt_tcpDbPassword" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_tcpDbPassword" type="password" maxlength="40" value="">
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- DB Table Prefix -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_tcp_dbPrefix'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_tcp_dbPrefix_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <input id="txt_tcpDbPrefix" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_tcpDbPrefix" type="text" maxlength="40" value="<?= $viewData['tcpDbPrefix'] ?>">
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Test Database -->
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <p><a class="btn btn-secondary text-white" tabindex="<?= $tabindex++ ?>" id="btn_testDb" onclick="javascript:checkTcpDB();"><?= $LANG['btn_testdb'] ?></a></p>
                      <p><span id="checkTcpDbOutput"></span></p>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                </div>

                <!-- Import tab -->
                <div class="tab-pane fade" id="tab_import" role="tabpanel" aria-labelledby="tab_import-tab">

                  <!-- Select buttonss -->
                  <div class="form-group row">
                    <div class="col-lg-12 text-end">
                      <a class="btn btn-secondary text-white" tabindex="<?= $tabindex++ ?>" onclick="javascript:resetAll();"><?= $LANG['btn_reset'] ?></a>
                      <a class="btn btn-secondary text-white" tabindex="<?= $tabindex++ ?>" onclick="javascript:replaceAll();"><?= $LANG['tcpimp_btn_replace_all'] ?></a>
                      <a class="btn btn-secondary text-white" tabindex="<?= $tabindex++ ?>" onclick="javascript:addAll();"><?= $LANG['tcpimp_btn_add_all'] ?></a>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Absence types -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_abs'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_abs_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_absImport" id="opt_absImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_absImport" id="opt_absImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_absImport" id="opt_absImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Allowances -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_allo'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_allo_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_alloImport" id="opt_alloImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_alloImport" id="opt_alloImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_alloImport" id="opt_alloImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Daynotes -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_dayn'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_dayn_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_daynImport" id="opt_daynImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_daynImport" id="opt_daynImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_daynImport" id="opt_daynImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Groups -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_groups'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_groups_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_groupsImport" id="opt_groupsImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_groupsImport" id="opt_groupsImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_groupsImport" id="opt_groupsImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Group Memberships -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_ugr'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_ugr_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_ugrImport" id="opt_ugrImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_ugrImport" id="opt_ugrImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_ugrImport" id="opt_ugrImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Holidays -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_hols'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_hols_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_holsImport" id="opt_holsImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_holsImport" id="opt_holsImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_holsImport" id="opt_holsImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Regions -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_regs'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_regs_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_regsImport" id="opt_regsImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_regsImport" id="opt_regsImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_regsImport" id="opt_regsImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Region Calendars -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_mtpl'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_mtpl_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_mtplImport" id="opt_mtplImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_mtplImport" id="opt_mtplImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_mtplImport" id="opt_mtplImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Roles -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_roles'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_roles_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_rolesImport" id="opt_rolesImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_rolesImport" id="opt_rolesImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- User Accounts -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_users'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_users_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_usersImport" id="opt_usersImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_usersImport" id="opt_usersImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_usersImport" id="opt_usersImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- User Calendars -->
                  <div class="form-group row">
                    <div class="col-lg-8 control-label">
                      <div class="text-bold"><?= $LANG['tcpimp_utpl'] ?></div>
                      <div class="text-normal"><?= $LANG['tcpimp_utpl_comment'] ?></div>
                    </div>
                    <div class="col-lg-4">
                      <div class="radio"><label><input name="opt_utplImport" id="opt_utplImport_no" value="no" tabindex="<?= $tabindex++ ?>" checked type="radio"><?= $LANG['tcpimp_no'] ?></label></div>
                      <div class="radio"><label><input name="opt_utplImport" id="opt_utplImport_replace" value="replace" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_replace'] ?></label></div>
                      <div class="radio"><label><input name="opt_utplImport" id="opt_utplImport_add" value="add" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['tcpimp_add'] ?></label></div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <button type="button" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalImportTCP"><?= $LANG['btn_import'] ?></button>

                  <!-- Modal: Delete selected -->
                  <?= createModalTop('modalImportTCP', $LANG['modal_confirm']) ?>
                  <?= $LANG['tcpimp_confirm_import'] ?>
                  <?= createModalBottom('btn_import', 'danger', $LANG['btn_import']) ?>

                </div>

              </div>
            </div>
          </div>

        </div>
      </div>

    </form>

  </div>
</div>

<script src="js/ajax.js"></script>
<script>
  function checkTcpDB() {
    var myDbServer = document.getElementById('txt_tcpDbServer');
    var myDbUser = document.getElementById('txt_tcpDbUser');
    var myDbPass = document.getElementById('txt_tcpDbPassword');
    var myDbName = document.getElementById('txt_tcpDbName');
    var myDbPrefix = document.getElementById('txt_tcpDbPrefix');
    ajaxCheckDB(myDbServer.value, myDbUser.value, myDbPass.value, myDbName.value, myDbPrefix.value, 'checkTcpDbOutput');
  }

  function resetAll() {
    document.getElementById('opt_absImport_no').checked = true;
    document.getElementById('opt_alloImport_no').checked = true;
    document.getElementById('opt_daynImport_no').checked = true;
    document.getElementById('opt_groupsImport_no').checked = true;
    document.getElementById('opt_ugrImport_no').checked = true;
    document.getElementById('opt_holsImport_no').checked = true;
    document.getElementById('opt_regsImport_no').checked = true;
    document.getElementById('opt_mtplImport_no').checked = true;
    document.getElementById('opt_rolesImport_no').checked = true;
    document.getElementById('opt_usersImport_no').checked = true;
    document.getElementById('opt_utplImport_no').checked = true;
  }

  function addAll() {
    document.getElementById('opt_absImport_add').checked = true;
    document.getElementById('opt_alloImport_add').checked = true;
    document.getElementById('opt_daynImport_add').checked = true;
    document.getElementById('opt_groupsImport_add').checked = true;
    document.getElementById('opt_ugrImport_add').checked = true;
    document.getElementById('opt_holsImport_add').checked = true;
    document.getElementById('opt_regsImport_add').checked = true;
    document.getElementById('opt_mtplImport_add').checked = true;
    document.getElementById('opt_rolesImport_add').checked = true;
    document.getElementById('opt_usersImport_add').checked = true;
    document.getElementById('opt_utplImport_add').checked = true;
  }

  function replaceAll() {
    document.getElementById('opt_absImport_replace').checked = true;
    document.getElementById('opt_alloImport_replace').checked = true;
    document.getElementById('opt_daynImport_replace').checked = true;
    document.getElementById('opt_groupsImport_replace').checked = true;
    document.getElementById('opt_ugrImport_replace').checked = true;
    document.getElementById('opt_holsImport_replace').checked = true;
    document.getElementById('opt_regsImport_replace').checked = true;
    document.getElementById('opt_mtplImport_replace').checked = true;
    document.getElementById('opt_rolesImport_add').checked = true;
    document.getElementById('opt_usersImport_replace').checked = true;
    document.getElementById('opt_utplImport_replace').checked = true;
  }
</script>
