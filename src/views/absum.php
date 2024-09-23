<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Absence Summary View
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
view.absum
-->
<div class="container content">

  <?php
  $tabindex = 1;
  $colsleft = 1;
  $colsright = 4;
  ?>

  <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;user=<?= $viewData['username'] ?>" method="post" target="_self" accept-charset="utf-8">

    <div class="page-menu">
      <button type="button" class="btn btn-success" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalSelectUser"><?= $LANG['user'] ?> <span class="badge text-bg-light"><?= $viewData['fullname'] ?></span></button>
      <?php if (!$C->read('currentYearOnly')) { ?>
        <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalYear"><?= $LANG['year'] ?> <span class="badge text-bg-light"><?= $viewData['year'] ?></span></button>
      <?php } ?>
    </div>
    <div style="height:20px;"></div>

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= sprintf($LANG['absum_title'], $viewData['year'], $viewData['fullname']) ?><?= $pageHelp ?></div>
      <div class="card-body">

        <table id="dataTableAbsenceSummary" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
          <thead>
          <tr>
            <th><?= $LANG['absum_absencetype'] ?></th>
            <th class="text-end"><?= $LANG['absum_contingent'] ?>&nbsp;<?= iconTooltip($LANG['absum_contingent_tt'], $LANG['absum_contingent'], 'bottom') ?></th>
            <th class="text-end"><?= $LANG['absum_taken'] ?></th>
            <th class="text-end"><?= $LANG['absum_remainder'] ?></th>
          </tr>
          </thead>
          <tbody>
          <?php if (count($viewData['absences'])) {
            foreach ($viewData['absences'] as $abs) {
              if (!$abs['counts_as']) { ?>
                <tr>
                  <td><i class="<?= $abs['icon'] ?>" style="color: #<?= $abs['color'] ?>; background-color: #<?= $abs['bgcolor'] ?>; border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 6px 4px 3px 4px; margin-right: 8px;"></i><?= $abs['name'] ?></td>
                  <td class="text-end"><?= $abs['contingent'] ?></td>
                  <td class="text-end <?= (is_int($abs['allowance']) && intval($abs['taken']) > intval($abs['allowance'])) ? 'text-warning' : ''; ?>"><?= $abs['taken'] ?></td>
                  <td class="text-end <?= (is_int($abs['allowance']) && intval($abs['remainder']) < 0) ? 'text-danger' : 'text-success'; ?>"><?= $abs['remainder'] ?></td>
                </tr>
              <?php }
              $subabsences = $A->getAllSub($abs['id']);
              foreach ($subabsences as $subabs) {
                $summary = getAbsenceSummary($caluser, $subabs['id'], $viewData['year']);
                $subabs['contingent'] = $summary['totalallowance'];
                $subabs['taken'] = $summary['taken'];
                $subabs['remainder'] = $summary['remainder'];
                ?>
                <tr>
                  <td>
                    <i class="<?= $abs['icon'] ?>" style="color: #<?= $abs['color'] ?>; background-color: #<?= $abs['bgcolor'] ?>; border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 6px 4px 3px 4px; margin-right: 8px;"></i>
                    <?= $abs['name'] ?>
                    <i class="fas fa-angle-double-right mx-2"></i>
                    <i class="<?= $subabs['icon'] ?>" style="color: #<?= $subabs['color'] ?>; background-color: #<?= $subabs['bgcolor'] ?>; border: 1px solid #333333; width: 30px; height: 30px; text-align: center; padding: 6px 4px 3px 4px; margin-right: 8px;"></i>
                    <?= $subabs['name'] ?>
                  </td>
                  <td class="text-end text-italic"><?= $subabs['contingent'] ?></td>
                  <td class="text-end text-italic <?= (is_int($subabs['allowance']) && intval($subabs['taken']) > intval($subabs['allowance'])) ? 'text-warning' : ''; ?>"><?= $subabs['taken'] ?></td>
                  <td class="text-end text-italic <?= (is_int($subabs['allowance']) && intval($subabs['remainder']) < 0) ? 'text-danger' : 'text-success'; ?>"><?= $subabs['remainder'] ?></td>
                </tr>
              <?php }
            }
          } ?>
          </tbody>
        </table>
        <script>
          $(document).ready(function () {
            $('#dataTableAbsenceSummary').DataTable({
              paging: true,
              ordering: true,
              info: true,
              pageLength: 50,
              language: {
                url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
              },
            });
          });
        </script>

      </div>
    </div>

    <!-- Modal: Select User -->
    <?= createModalTop('modalSelectUser', $LANG['caledit_selUser']) ?>
    <select class="form-select" name="sel_user" tabindex="<?= $tabindex++ ?>">
      <?php foreach ($viewData['users'] as $usr) { ?>
        <option value="<?= $usr['username'] ?>" <?= (($viewData['username'] == $usr['username']) ? ' selected="selected"' : '') ?>><?= $usr['lastfirst'] ?></option>
      <?php } ?>
    </select>
    <?= createModalBottom('btn_user', 'success', $LANG['btn_select']) ?>

    <!-- Modal: Year -->
    <?= createModalTop('modalYear', $LANG['absum_modalYearTitle']) ?>
    <div>
      <span class="text-bold"><?= $LANG['absum_year'] ?></span><br>
      <span class="text-normal"><?= $LANG['absum_year_comment'] ?></span>
      <select class="form-select" id="sel_year" name="sel_year" tabindex="<?= $tabindex++ ?>">
        <option value="<?= date("Y") - 1 ?>" <?= (($viewData['year'] == date("Y") - 1) ? "selected" : "") ?>><?= date("Y") - 1 ?></option>
        <option value="<?= date("Y") ?>" <?= (($viewData['year'] == date("Y")) ? "selected" : "") ?>><?= date("Y") ?></option>
        <option value="<?= date("Y") + 1 ?>" <?= (($viewData['year'] == date("Y") + 1) ? "selected" : "") ?>><?= date("Y") + 1 ?></option>
      </select><br>
    </div>
    <?= createModalBottom('btn_year', 'success', $LANG['btn_select']) ?>

  </form>

</div>
