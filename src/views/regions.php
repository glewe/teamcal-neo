<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Regions View
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
view.regions
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

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['regions_title'] . $pageHelp ?></div>

      <div class="card-body">

        <form class="row form-control-horizontal" name="form_create" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

          <div class="mb-4">
            <button type="button" class="btn btn-success float-end" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalCreateRegion"><?= $LANG['btn_create_region'] ?></button>
          </div>

          <!-- Modal: Create region -->
          <?= createModalTop('modalCreateRegion', $LANG['btn_create_region']) ?>
          <label for="inputName"><?= $LANG['name'] ?></label>
          <input id="inputName" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_name" maxlength="40" value="<?= $viewData['txt_name'] ?>" type="text">
          <?php if (isset($inputAlert["name"]) && strlen($inputAlert["name"])) { ?>
            <br>
            <div class="alert alert-dismissable alert-danger">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert">x</button><?= $inputAlert["name"] ?></div>
          <?php } ?>
          <label for="inputDescription"><?= $LANG['description'] ?></label>
          <input id="inputDescription" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_description" maxlength="100" value="<?= $viewData['txt_description'] ?>" type="text">
          <?php if (isset($inputAlert["description"]) && strlen($inputAlert["description"])) { ?>
            <br>
            <div class="alert alert-dismissable alert-danger">
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert">x</button><?= $inputAlert["description"] ?></div>
          <?php } ?>
          <?= createModalBottom('btn_regionCreate', 'success', $LANG['btn_create_region']) ?>

        </form>

        <div class="card">

          <div class="card-header">
            <?php
            $pageTabs = [
              [ 'id' => 'tab-list', 'href' => '#panel-list', 'label' => $LANG['regions_tab_list'], 'active' => true ],
              [ 'id' => 'tab-ical', 'href' => '#panel-ical', 'label' => $LANG['regions_tab_ical'], 'active' => false ],
              [ 'id' => 'tab-copy', 'href' => '#panel-copy', 'label' => $LANG['regions_tab_transfer'], 'active' => false ]
            ];
            echo createPageTabs($pageTabs);
            ?>
          </div>

          <div class="card-body">
            <div class="tab-content" id="myTabContent">

              <!-- List tab -->
              <div class="tab-pane fade show active" id="panel-list" role="tabpanel" aria-labelledby="tab-list">
                <table id="dataTableRegions" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
                  <thead>
                  <tr>
                    <th class="text-end">#</th>
                    <th><?= $LANG['name'] ?></th>
                    <th><?= $LANG['description'] ?></th>
                    <th class="text-center"><?= $LANG['action'] ?></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $i = 1;
                  foreach ($viewData['regions'] as $region) : ?>
                    <tr>
                      <td class="text-end"><?= $i++ ?></td>
                      <td><?= $region['name'] ?></td>
                      <td><?= $region['description'] ?></td>
                      <td class="align-top text-center">
                        <?php if ($region['id'] != '1') : ?>
                        <form class="form-control-horizontal" name="form_<?= $region['name'] ?>" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
                          <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteRegion_<?= $region['name'] ?>"><?= $LANG['btn_delete'] ?></button>
                          <a href="index.php?action=regionedit&amp;id=<?= $region['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_edit'] ?></a>
                          <input name="hidden_id" type="hidden" value="<?= $region['id'] ?>">
                          <input name="hidden_name" type="hidden" value="<?= $region['name'] ?>">
                          <input name="hidden_description" type="hidden" value="<?= $region['description'] ?>">
                          <!-- Modal: Delete region -->
                          <?= createModalTop('modalDeleteRegion_' . $region['name'], $LANG['modal_confirm']) ?>
                          <?= $LANG['regions_confirm_delete'] . ": " . $region['name'] ?>
                          <?= createModalBottom('btn_regionDelete', 'danger', $LANG['btn_delete']) ?>
                          <?php endif; ?>
                          <a href="index.php?action=monthedit&amp;month=<?= date('Y') . date('m') ?>&amp;region=<?= $region['id'] ?>" class="btn btn-info btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_calendar'] ?></a>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
                <script>
                  $(document).ready(function () {
                    $('#dataTableRegions').DataTable({
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

              <!-- iCal tab -->
              <div class="tab-pane fade" id="panel-ical" role="tabpanel" aria-labelledby="tab-ical">
                <form class="form-control-horizontal" name="form_ical" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8" enctype="multipart/form-data">
                  <?php foreach ($viewData['ical'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                  <div class="form-group row">
                    <label class="col-lg-<?= $colsleft ?> control-label">
                      <?= $LANG['regions_ical_file'] ?><br>
                      <span class="text-normal"><?= $LANG['regions_ical_file_comment'] ?></span>
                    </label>
                    <div class="col-lg-<?= $colsright ?>">
                      <input class="form-control" tabindex="<?= $tabindex++ ?>" accept="text/calendar" name="file_ical" type="file">
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm float-end" tabindex="32" name="btn_uploadIcal"><?= $LANG['btn_upload'] ?></button>
                  </div>
                </form>
              </div>

              <!-- Copy tab -->
              <div class="tab-pane fade" id="panel-copy" role="tabpanel" aria-labelledby="tab-copy">
                <form class="bs-example form-control-horizontal" name="form_merge" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
                  <?php foreach ($viewData['merge'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm float-end" tabindex="32" name="btn_regionTransfer"><?= $LANG['btn_transfer'] ?></button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
