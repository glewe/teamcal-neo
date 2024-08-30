<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Absence Edit View
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
view.absenceedit
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

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;id=<?= $viewData['id'] ?>" method="post" target="_self" accept-charset="utf-8">

      <input name="hidden_id" type="hidden" class="text" value="<?= $viewData['id'] ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['abs_edit_title'] . $viewData['name'] ?><?= $pageHelp ?></div>

        <div class="card-body">

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                [ 'id' => 'tab-general', 'href' => '#panel-general', 'label' => $LANG['general'], 'active' => true ],
                [ 'id' => 'tab-options', 'href' => '#panel-options', 'label' => $LANG['options'], 'active' => false ],
                [ 'id' => 'tab-groupassignments', 'href' => '#panel-groupassignments', 'label' => $LANG['abs_tab_groups'], 'active' => false ]
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div id="myTabContent" class="tab-content">

                <div class="tab-pane fade show active" id="panel-general" role="tabpanel" aria-labelledby="tab-general">
                  <div class="card">
                    <div class="card-body">

                      <!-- Sample display -->
                      <div class="form-group row">
                        <label class="col-lg-<?= $colsleft ?> control-label">
                          <?= $LANG['abs_sample'] ?><br>
                          <span class="text-normal"><?= $LANG['abs_sample_comment'] ?></span>
                        </label>
                        <div class="col-lg-<?= $colsright ?>">
                          <?php if ($viewData['bgtrans']) {
                            $bgStyle = "";
                          } else {
                            $bgStyle = "background-color: #" . $viewData['bgcolor'];
                          } ?>
                          <div id="sample" style="color: #<?= $viewData['color'] ?>; <?= $bgStyle ?>; border: 1px solid #333333; width: 32px; height: 32px; text-align: center; padding-top: 4px;" class="mb-1">
                            <span class="<?= $viewData['icon'] ?>"></span>
                          </div>
                          <div id="sample" style="color: #<?= $viewData['color'] ?>; <?= $bgStyle ?>; border: 1px solid #333333; width: 32px; height: 32px; text-align: center; padding-top: 4px;">
                            <?php echo $viewData['symbol']; ?>
                          </div>
                        </div>
                      </div>
                      <div class="divider">
                        <hr>
                      </div>

                      <!-- Icon -->
                      <div class="form-group row">
                        <label class="col-lg-<?= $colsleft ?> control-label">
                          <?= $LANG['abs_icon'] ?><br>
                          <span class="text-normal"><?= $LANG['abs_icon_comment'] ?></span>
                        </label>
                        <div class="col-lg-<?= $colsright ?>">
                          <span class="<?= $viewData['icon'] ?> text-<?= $viewData['color'] ?>" style="font-size: 150%; padding-right: 8px; vertical-align: middle;"></span>
                          <a href="index.php?action=absenceicon&amp;id=<?= $viewData['id'] ?>" class="btn btn-primary btn-sm" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_abs_icon'] ?></a>
                        </div>
                      </div>
                      <div class="divider">
                        <hr>
                      </div>

                      <?php foreach ($viewData['general'] as $formObject) {
                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                      } ?>

                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="panel-options" role="tabpanel" aria-labelledby="tab-options">
                  <div class="card">
                    <div class="card-body">
                      <?php foreach ($viewData['options'] as $formObject) {
                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                      } ?>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="panel-groupassignments" role="tabpanel" aria-labelledby="tab-groupassignments">
                  <div class="card">
                    <div class="card-body">
                      <?php foreach ($viewData['groups'] as $formObject) {
                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                      } ?>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>

          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
            <a href="index.php?action=absences" class="btn btn-secondary" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_abs_list'] ?></a>
          </div>

        </div>
      </div>

    </form>

  </div>

</div>
