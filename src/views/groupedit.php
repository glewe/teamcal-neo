<?php
/**
 * Group Edit View
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
view.groupedit
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
    $tabindex = 0;
    $colsleft = 8;
    $colsright = 4;
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;id=<?= $viewData['id'] ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="card">
        <?php
        // Performance optimization: Cache config values to avoid repeated lookups
        $panelColor = $CONF['controllers'][$controller]->panelColor;
        $faIcon = $CONF['controllers'][$controller]->faIcon;
        $docurl = $CONF['controllers'][$controller]->docurl;
        
        $pageHelp = '';
        if ($allConfig['pageHelp']) {
          $pageHelp = '<a href="' . $docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header panel-<?= $panelColor ?>"><i class="<?= $faIcon ?> fa-lg me-3"></i><?= $LANG['group_edit_title'] . $viewData['name'] . $pageHelp ?></div>
        <div class="card-body">

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                [ 'id' => 'tab-settings', 'href' => '#panel-settings', 'label' => $LANG['group_tab_settings'], 'active' => true ],
                [ 'id' => 'tab-members', 'href' => '#panel-members', 'label' => $LANG['group_tab_members'], 'active' => false ],
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div id="myTabContent" class="tab-content">

                <!-- Group Settings -->
                <div class="tab-pane fade show active" id="panel-settings" role="tabpanel" aria-labelledby="tab-settings">
                  <?php foreach ($viewData['group'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <!-- Group Members -->
                <div class="tab-pane fade" id="panel-members" role="tabpanel" aria-labelledby="tab-members">
                  <?php foreach ($viewData['members'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>
                  <?php foreach ($viewData['managers'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

              </div>
            </div>
          </div>

          <div class="mt-4 float-end">
            <input name="hidden_id" type="hidden" value="<?= $viewData['id'] ?>">
            <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_groupUpdate"><?= $LANG['btn_update'] ?></button>
            <a href="index.php?action=groups" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_group_list'] ?></a>
          </div>

        </div>
      </div>
    </form>
  </div>
</div>
