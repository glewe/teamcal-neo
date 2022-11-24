<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Role Edit View
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
view.roleedit
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
                if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                ?>
                <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg fa-header"></i><?= $LANG['role_edit_title'] . $viewData['name'] . $pageHelp ?></div>
                <div class="card-body">

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_roleUpdate"><?= $LANG['btn_update'] ?></button>
                            <a href="index.php?action=roles" class="btn btn-default float-end" tabindex="<?= $tabindex++; ?>"><?= $LANG['btn_role_list'] ?></a>
                        </div>
                    </div>
                    <div style="height:20px;"></div>

                    <div class="card">
                        <div class="card-body">
                            <?php foreach ($viewData['role'] as $formObject) {
                                echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                            } ?>
                        </div>
                    </div>

                    <div style="height:20px;"></div>
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_roleUpdate"><?= $LANG['btn_update'] ?></button>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>

</div>