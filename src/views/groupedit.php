<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Group Edit View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2022 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */
?>

<!-- ==================================================================== 
view.groupedit
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
        $colsleft = 8;
        $colsright = 4; ?>

        <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;id=<?= $viewData['id'] ?>" method="post" target="_self" accept-charset="utf-8">

            <div class="card">
                <?php
                $pageHelp = '';
                if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                ?>
                <div class="card-header panel-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg fa-header"></i><?= $LANG['group_edit_title'] . $viewData['name'] . $pageHelp ?></div>
                <div class="card-body">

                    <div class="card">
                        <div class="card-body">
                            <input name="hidden_id" type="hidden" value="<?= $viewData['id'] ?>">
                            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_groupUpdate"><?= $LANG['btn_update'] ?></button>
                            <a href="index.php?action=groups" class="btn btn-secondary float-right" tabindex="<?= $tabindex++; ?>"><?= $LANG['btn_group_list'] ?></a>
                        </div>
                    </div>
                    <div style="height:20px;"></div>

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab_settings-tab" href="#tab_settings" data-toggle="tab" role="tab" aria-controls="tab_settings" aria-selected="true"><?= $LANG['group_tab_settings'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_members-tab" href="#tab_members" data-toggle="tab" role="tab" aria-controls="tab_members" aria-selected="false"><?= $LANG['group_tab_members'] ?></a></li>
                    </ul>

                    <div id="myTabContent" class="tab-content">

                        <!-- Group Settings -->
                        <div class="tab-pane fade show active" id="tab_settings" role="tabpanel" aria-labelledby="tab_settings-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php foreach ($viewData['group'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Group Members -->
                        <div class="tab-pane fade" id="tab_members" role="tabpanel" aria-labelledby="tab_members-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php foreach ($viewData['members'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                    <?php foreach ($viewData['managers'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div style="height:20px;"></div>
                    <div class="card">
                        <div class="card-body">
                            <input name="hidden_id" type="hidden" value="<?= $viewData['id'] ?>">
                            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_groupUpdate"><?= $LANG['btn_update'] ?></button>
                            <a href="index.php?action=groups" class="btn btn-secondary float-right" tabindex="<?= $tabindex++; ?>"><?= $LANG['btn_group_list'] ?></a>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>

</div>