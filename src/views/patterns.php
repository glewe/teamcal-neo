<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Patterns View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.0.0
 */
?>

<!-- ====================================================================
view.patterns
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
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['ptn_list_title'] . $pageHelp ?></div>
      <div class="card-body">

        <form class="form-control-horizontal" name="form_create" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
          <div class="row mb-4">
            <div class="col-lg-12 text-end">
              <br>
              <a href="index.php?action=patternadd" class="btn btn-success" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_create_pattern'] ?></a>
            </div>
          </div>

        </form>

        <table id="dataTablePatterns" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
          <thead>
          <tr>
            <th class="text-end">#</th>
            <th><?= $LANG['ptn_name'] ?></th>
            <th><?= $LANG['ptn_description'] ?></th>
            <th><?= $LANG['ptn_pattern'] ?></th>
            <th class="text-center"><?= $LANG['action'] ?></th>
          </tr>
          </thead>
          <tbody>
          <?php
          $i = 1;
          foreach ($viewData['patterns'] as $pattern) : ?>
            <tr>
              <td class="text-end"><?= $i++ ?></td>
              <td><?= $pattern['name'] ?></td>
              <td><?= $pattern['description'] ?></td>
              <td><?= createPatternTable($pattern['id']) ?></td>
              <td class="align-top text-center">
                <form class="form-control-horizontal" name="form_<?= $pattern['name'] ?>" action="index.php?action=patterns" method="post" target="_self" accept-charset="utf-8">
                  <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeletePattern_<?= $pattern['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                  <input name="hidden_id" type="hidden" value="<?= $pattern['id'] ?>">
                  <input name="hidden_name" type="hidden" value="<?= $pattern['name'] ?>">
                  <input name="hidden_description" type="hidden" value="<?= $pattern['description'] ?>">
                  <a href="index.php?action=patternedit&amp;id=<?= $pattern['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_edit'] ?></a>

                  <!-- Modal: Delete Pattern -->
                  <?= createModalTop('modalDeletePattern_' . $pattern['id'], $LANG['modal_confirm']) ?>
                  <?= $LANG['ptn_confirm_delete'] . $pattern['name'] ?> ?
                  <?= createModalBottom('btn_patternDelete', 'danger', $LANG['btn_delete']) ?>

                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <script>
          $(document).ready(function () {
            $('#dataTablePatterns').DataTable({
              paging: true,
              ordering: true,
              info: true,
              pageLength: 50,
              language: {
                url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
              },
              columnDefs: [
                {targets: [0, 3, 4], orderable: false, searchable: false}
              ]
            });
          });
        </script>

      </div>
    </div>
  </div>
</div>
