<?php

/**
 * Absence Icon View
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
view.absenceicon
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
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;id=<?= $viewData['id'] ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">
      <input name="hidden_id" type="hidden" class="text" value="<?= $viewData['id'] ?>">
      <input name="hidden_name" type="hidden" class="text" value="<?= $viewData['name'] ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($viewData['pageHelp']) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['absico_title'] . $viewData['name'] ?><?= $pageHelp ?></div>
        <div class="card-body">

          <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
          <a href="index.php?action=absenceedit&amp;id=<?= $viewData['id'] ?>" class="btn btn-secondary float-end" style="margin-left:8px;" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_abs_edit'] ?></a>

          <div style="height:20px;"></div>

          <div class="row mb-2">
            <div class="col">
              <input id="fa-search" class="form-control form-control-sm" tabindex="<?= ++$tabindex ?>" name="fa_search" type="text" value="" placeholder="<?= $LANG['abs_icon_keyword'] ?>">
            </div>
            <div class="col">
              <button type="submit" class="btn btn-outline-secondary btn-sm" tabindex="<?= ++$tabindex ?>" name="btn_fa_filter"><?= $LANG['btn_filter'] ?></button>
              <a class="btn btn-outline-secondary btn-sm" tabindex="<?= ++$tabindex ?>" href="index.php?action=<?= $controller ?>&amp;id=<?= $viewData['id'] ?>"><?= $LANG['btn_reset'] ?></a>
            </div>
          </div>

          <div class="card">

            <div class="card-header">

              <?php
              if (array_key_exists('fasIcons', $viewData)) {
                $fasIconCount = count($viewData['fasIcons']);
              } else {
                $fasIconCount = 0;
              }
              if (array_key_exists('farIcons', $viewData)) {
                $farIconCount = count($viewData['farIcons']);
              } else {
                $farIconCount = 0;
              }
              if (array_key_exists('fabIcons', $viewData)) {
                $fabIconCount = count($viewData['fabIcons']);
              } else {
                $fabIconCount = 0;
              }
              $pageTabs = [
                ['id' => 'solid-tab', 'href' => '#solid', 'label' => $LANG['absico_tab_solid'] . " (" . $fasIconCount . ")", 'active' => true],
                ['id' => 'regular-tab', 'href' => '#regular', 'label' => $LANG['absico_tab_regular'] . " (" . $farIconCount . ")", 'active' => false],
                ['id' => 'brand-tab', 'href' => '#brand', 'label' => $LANG['absico_tab_brand'] . " (" . $fabIconCount . ")", 'active' => false]
              ];
              echo createPageTabs($pageTabs);
              ?>

            </div>

            <div class="card-body">
              <div id="myTabContent" class="tab-content">

                <!-- Solid Icons -->
                <div class="tab-pane fade show active" id="solid" role="tabpanel" aria-labelledby="solid-tab">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <?php
                        if (array_key_exists('fasIcons', $viewData)) {
                          $count = 0;
                          $totalIcons = count($viewData['fasIcons']);
                          foreach ($viewData['fasIcons'] as $fai) {
                            if ($count % 12 == 0) {
                              echo '</div><div class="row">';
                            }
                            $isSelected = $fai['val'] == $viewData['icon'];
                            $iconTooltip = '<span class=\'' . $fai['val'] . ' fa-5x text-info\' title=\'' . $fai['val'] . '\'></span>';
                            if ($isSelected) {
                              echo '<div class="col-lg-1 p-1 bg-warning-subtle border-warning" style="border: 1px solid;"><div class="radio">';
                            } else {
                              echo '<div class="col-lg-1 p-1"><div class="form-check">';
                            }
                            echo '<label><input class="form-check-input" name="opt_absIcon" value="' . $fai['val'] . '" tabindex="' . ++$tabindex . '" type="radio"' . ($isSelected ? " checked" : "") . '><i data-bs-placement="top" data-bs-type="secondary" data-bs-toggle="tooltip" title="' . $iconTooltip . '"><span class="' . $fai['val'] . ' fa-lg text-secondary" title="' . $fai['val'] . '"></span></i></label>';
                            echo '</div></div>';
                            $count++;
                          }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Regular Icons -->
                <div class="tab-pane fade" id="regular" role="tabpanel" aria-labelledby="regular-tab">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <?php
                        if (array_key_exists('farIcons', $viewData)) {
                          $count = 0;
                          foreach ($viewData['farIcons'] as $fai) {
                            if ($count % 12 == 0) {
                              echo '</div><div class="row">';
                            }
                            $isSelected = $fai['val'] == $viewData['icon'];
                            $iconTooltip = '<span class=\'' . $fai['val'] . ' fa-5x text-info\' title=\'' . $fai['val'] . '\'></span>';
                            echo '<div class="col-lg-1" style="border: ' . ($isSelected ? "1" : "0") . 'px solid #CC0000;"><div class="form-check">';
                            echo '<label><input class="form-check-input" name="opt_absIcon" value="' . $fai['val'] . '" tabindex="' . ++$tabindex . '" type="radio"' . ($isSelected ? " checked" : "") . '><i data-placement="top" data-type="secondary" data-bs-toggle="tooltip" title="' . $iconTooltip . '"><span class="' . $fai['val'] . ' fa-lg text-secondary" title="' . $fai['val'] . '"></span></i></label>';
                            echo '</div></div>';
                            $count++;
                          }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Brand Icons -->
                <div class="tab-pane fade" id="brand" role="tabpanel" aria-labelledby="brand-tab">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <?php
                        if (array_key_exists('fabIcons', $viewData)) {
                          $count = 0;
                          foreach ($viewData['fabIcons'] as $fai) {
                            if ($count % 12 == 0) {
                              echo '</div><div class="row">';
                            }
                            $isSelected = $fai['val'] == $viewData['icon'];
                            $iconTooltip = '<span class=\'' . $fai['val'] . ' fa-5x text-info\' title=\'' . $fai['val'] . '\'></span>';
                            echo '<div class="col-lg-1" style="border: ' . ($isSelected ? "1" : "0") . 'px solid #CC0000;"><div class="form-check">';
                            echo '<label><input class="form-check-input" name="opt_absIcon" value="' . $fai['val'] . '" tabindex="' . ++$tabindex . '" type="radio"' . ($isSelected ? " checked" : "") . '><i data-placement="top" data-type="secondary" data-bs-toggle="tooltip" title="' . $iconTooltip . '"><span class="' . $fai['val'] . ' fa-lg text-secondary" title="' . $fai['val'] . '"></span></i></label>';
                            echo '</div></div>';
                            $count++;
                          }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>

          <div style="height:20px;"></div>

          <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
          <a href="index.php?action=absenceedit&amp;id=<?= $viewData['id'] ?>" class="btn btn-secondary float-end" style="margin-left:8px;" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_abs_edit'] ?></a>

        </div>
      </div>

    </form>
  </div>
</div>