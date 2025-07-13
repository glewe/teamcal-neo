<?php
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
    $tabindex = 0;
    ?>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['ptn_list_title'] . $pageHelp ?></div>
      <div class="card-body">

        <form class="form-control-horizontal" name="form_create" action="index.php?action=<?= htmlspecialchars($controller ?? '', ENT_QUOTES, 'UTF-8') ?>" method="post" target="_self" accept-charset="utf-8">
          <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
          <div class="row mb-4">
            <div class="col-lg-12 text-end">
              <br>
              <a href="index.php?action=patternadd" class="btn btn-success" tabindex="<?= ++$tabindex ?>" aria-label="<?= $LANG['btn_create_pattern'] ?>"><?= $LANG['btn_create_pattern'] ?></a>
            </div>
          </div>

        </form>

        <table id="dataTablePatterns" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
          <thead>
          <tr>
            <th><?= $LANG['ptn_name'] ?></th>
            <th><?= $LANG['ptn_description'] ?></th>
            <th><?= $LANG['ptn_pattern'] ?></th>
            <th class="text-center"><?= $LANG['action'] ?></th>
          </tr>
          </thead>
          <tbody>
          <?php
          foreach ($viewData['patterns'] as $pattern) : ?>
            <tr>
              <td><?= htmlspecialchars($pattern['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($pattern['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= createPatternTable($pattern['id']) ?></td>
              <td class="align-top text-center">
                <form class="form-control-horizontal" name="form_<?= htmlspecialchars($pattern['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" action="index.php?action=patterns" method="post" target="_self" accept-charset="utf-8">
                  <button type="button" class="btn btn-danger btn-sm" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDeletePattern_<?= htmlspecialchars($pattern['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= $LANG['btn_delete'] ?></button>
                  <input name="hidden_id" type="hidden" value="<?= htmlspecialchars($pattern['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                  <input name="hidden_name" type="hidden" value="<?= htmlspecialchars($pattern['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                  <input name="hidden_description" type="hidden" value="<?= htmlspecialchars($pattern['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                  <a href="index.php?action=patternedit&amp;id=<?= htmlspecialchars($pattern['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_edit'] ?></a>

                  <!-- Modal: Delete Pattern -->
                  <?= createModalTop('modalDeletePattern_' . htmlspecialchars($pattern['id'] ?? '', ENT_QUOTES, 'UTF-8'), $LANG['modal_confirm']) ?>
                  <?= htmlspecialchars($LANG['ptn_confirm_delete'], ENT_QUOTES, 'UTF-8') . htmlspecialchars($pattern['name'] ?? '', ENT_QUOTES, 'UTF-8') ?> ?
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
                {targets: [2, 3], orderable: false, searchable: false}
              ]
            });
          });
        </script>

      </div>
    </div>
  </div>
</div>
