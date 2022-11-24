<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Log View
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
view.log
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

        <form class="form-control-horizontal" action="index.php?action=log&amp;sort=<?= $viewData['sort'] ?>" method="post" target="_self" accept-charset="utf-8">

            <div class="card">
                <?php
                $pageHelp = '';
                if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                ?>
                <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg fa-header"></i><?= $LANG['mnu_admin_systemlog'] . ' ( ' . count($viewData['events']) . ' ' . $LANG['log_title_events'] . ' )' . $pageHelp ?></div>
                <div class="card-body">

                    <div class="card">
                        <div class="card-body row">

                            <div class="col-lg-3">
                                <label><?= $LANG['period'] ?></label>
                                <select name="sel_logPeriod" id="sel_logPeriod" class="form-control" tabindex="<?= $tabindex++; ?>">
                                    <option class="option" value="curr_all" <?= (($viewData['logperiod'] == "curr_all") ? 'selected' : '') ?>><?= $LANG['all'] ?></option>
                                    <option class="option" value="curr_month" <?= (($viewData['logperiod'] == "curr_month") ? 'selected' : '') ?>><?= $LANG['period_month'] ?></option>
                                    <option class="option" value="curr_quarter" <?= (($viewData['logperiod'] == "curr_quarter") ? 'selected' : '') ?>><?= $LANG['period_quarter'] ?></option>
                                    <option class="option" value="curr_half" <?= (($viewData['logperiod'] == "curr_half") ? 'selected' : '') ?>><?= $LANG['period_half'] ?></option>
                                    <option class="option" value="curr_year" <?= (($viewData['logperiod'] == "curr_year") ? 'selected' : '') ?>><?= $LANG['period_year'] ?></option>
                                    <option class="option" value="custom" <?= (($viewData['logperiod'] == "custom") ? 'selected' : '') ?>><?= $LANG['period_custom'] ?></option>
                                </select>
                                <label><?= $LANG['log_header_type'] ?></label>
                                <select name="sel_logType" id="sel_logType" class="form-control" tabindex="<?= $tabindex++; ?>">
                                    <option class="option" value="%" <?= (($viewData['logtype'] == "%") ? 'selected' : '') ?>><?= $LANG['all'] ?></option>
                                    <?php foreach ($viewData['types'] as $type) { ?>
                                        <option class="option" value="log<?= $type ?>" <?= (($viewData['logtype'] == "log" . $type) ? 'selected' : '') ?>><?= $type ?></option>
                                    <?php } ?>
                                </select>
                                <label><?= $LANG['search'] . ' ' . $LANG['user'] ?></label>
                                <input id="logSearchUser" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_logSearchUser" maxlength="80" value="<?= $viewData['logSearchUser'] ?>" type="text">
                                <label><?= $LANG['search'] . ' ' . $LANG['event'] ?></label>
                                <input id="logSearchEvent" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_logSearchEvent" maxlength="80" value="<?= $viewData['logSearchEvent'] ?>" type="text">
                            </div>

                            <div class="col-lg-2">
                                <label><?= $LANG['from'] ?></label>
                                <input id="logPeriodFrom" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_logPeriodFrom" maxlength="10" value="<?= $viewData['logfrom'] ?>" type="text" <?= ($viewData['logPeriod'] != 'custom') ? 'disabled="disabled"' : '' ?>>
                                <script>
                                    $(function() {
                                        $("#logPeriodFrom").datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            dateFormat: "yy-mm-dd"
                                        });
                                    });
                                </script>
                            </div>

                            <div class="col-lg-2">
                                <label><?= $LANG['to'] ?></label>
                                <input id="logPeriodTo" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_logPeriodTo" maxlength="10" value="<?= $viewData['logto'] ?>" type="text" <?= ($viewData['logPeriod'] != 'custom') ? 'disabled="disabled"' : '' ?>>
                                <script>
                                    $(function() {
                                        $("#logPeriodTo").datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            dateFormat: "yy-mm-dd"
                                        });
                                    });
                                </script>
                            </div>

                            <div class="col-lg-5 text-end">
                                <br>
                                <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_refresh"><?= $LANG['btn_refresh'] ?></button>
                                <button type="submit" class="btn btn-secondary" tabindex="<?= $tabindex++; ?>" name="btn_reset"><?= $LANG['btn_reset'] ?></button>
                                <button type="button" class="btn btn-danger" tabindex="<?= $tabindex++; ?>" data-bs-toggle="modal" data-bs-target="#modalClear"><?= $LANG['log_clear'] ?></button>
                                <button type="submit" class="btn btn-info" title="<?= ($viewData['sort'] == 'DESC') ? $LANG['log_sort_asc'] : $LANG['log_sort_desc'] ?>" tabindex="<?= $tabindex++; ?>" name="btn_sort"><?= ($viewData['sort'] == 'DESC') ? '<i class="fas fa-arrow-up fa-lg"></i>' : '<i class="fas fa-arrow-down fa-lg"></i>' ?></button>

                                <!-- Modal: Clear -->
                                <?= createModalTop('modalClear', $LANG['modal_confirm']) ?>
                                <?= $LANG['log_clear_confirm'] ?>
                                <?= createModalBottom('btn_clear', 'danger', $LANG['log_clear']) ?>

                            </div>

                        </div>
                    </div>
                    <div style="height:20px;"></div>

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tabLog-tab" href="#tabLog" data-bs-toggle="tab" role="tab" aria-controls="tabLog" aria-selected="true"><?= $LANG['log_title'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tabSettings-tab" href="#tabSettings" data-bs-toggle="tab" role="tab" aria-controls="tabSettings" aria-selected="true"><?= $LANG['log_settings'] ?></a></li>
                    </ul>

                    <div id="myTabContent" class="tab-content">

                        <!-- Log tab -->
                        <div class="tab-pane fade show active" id="tabLog" role="tabpanel" aria-labelledby="tabLog-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    if (count($viewData['events'])) {
                                        $color = 'text-default';
                                        $startEvent = ($viewData['currentPage'] * $viewData['eventsPerPage']) - $viewData['eventsPerPage'];
                                        $endEvent = $startEvent + $viewData['eventsPerPage'];
                                        for ($i = $startEvent; $i < $endEvent; $i++) {
                                            if (isset($viewData['events'][$i])) $event = $viewData['events'][$i];
                                            else break;
                                            $color = "text-" . $C->read("logcolor" . substr($event['type'], 3));
                                    ?>
                                            <div class="row <?= $color ?>" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                                <div class="col-lg-1 small"><?= $i + 1 ?></div>
                                                <div class="col-lg-3 small"><i class="far fa-clock fa-lg fa-menu" title="<?= $LANG['log_header_when'] ?>"></i><?= $event['timestamp'] ?></div>
                                                <div class="col-lg-2 small"><i class="far fa-edit fa-lg fa-menu" title="<?= $LANG['log_header_type'] ?>"></i><?= substr($event['type'], 3) ?></div>
                                                <div class="col-lg-2 small"><i class="far fa-user fa-lg fa-menu" title="<?= $LANG['log_header_user'] ?>"></i><a href="index.php?action=viewprofile&amp;profile=<?= $event['user'] ?>" target="_blank"><?= $event['user'] ?></a></div>
                                                <div class="col-lg-4 small"><i class="far fa-hand-point-right fa-lg fa-menu" title="<?= $LANG['log_header_event'] ?>"></i><?= $event['event'] ?></div>
                                            </div>
                                    <?php }
                                    } ?>

                                    <ul class="pagination">
                                        <?php
                                        $formLink = 'index.php?action=log';
                                        $page = $viewData['currentPage'];
                                        $pages = $viewData['numPages'];
                                        ?>
                                        <!-- First Page Link -->
                                        <?php if ($page == 1) { ?>
                                            <li class="mr-1"><span aria-hidden="true"><i class="fas fa-angle-double-left fa-border text-light"></i></span></li>
                                        <?php } else { ?>
                                            <li class="mr-1"><a href="<?= $formLink ?>&amp;page=1" title="<?= $LANG['page_first'] ?>"><span><i class="fas fa-angle-double-left fa-border"></i></span></a></li>
                                        <?php } ?>

                                        <!-- Previous Page Link -->
                                        <?php if ($page == 1) { ?>
                                            <li><span><span aria-hidden="true"><i class="fas fa-angle-left fa-border text-light"></i></span></span></li>
                                        <?php } else { ?>
                                            <li><a href="<?= $formLink ?>&amp;page=<?= $page - 1 ?>" title="<?= $LANG['page_prev'] ?>"><span><i class="fas fa-angle-left fa-border"></i></span></a></li>
                                        <?php } ?>

                                        <!-- Page Link -->
                                        <?php for ($p = 1; $p <= $pages; $p++) {
                                            if ($p == $page) { ?>
                                                <li class="active pl-3 pr-3"><span><?= $p ?><span class="sr-only">(current)</span></span></li>
                                            <?php } else { ?>
                                                <li class="pl-3 pr-3"><a href="<?= $formLink ?>&amp;page=<?= $p ?>" title="<?= sprintf($LANG['page_page'], $p) ?>"><span><?= $p ?></span></a></li>
                                        <?php }
                                        } ?>

                                        <!-- Next Page Link -->
                                        <?php if ($page == $pages) { ?>
                                            <li class="mr-1"><span><span aria-hidden="true"><i class="fas fa-angle-right fa-border text-light"></i></span></span></li>
                                        <?php } else { ?>
                                            <li class="mr-1"><a href="<?= $formLink ?>&amp;page=<?= $page + 1 ?>" title="<?= $LANG['page_next'] ?>"><span><i class="fas fa-angle-right fa-border"></i></span></a></li>
                                        <?php } ?>

                                        <!-- Last Page Link -->
                                        <?php if ($page == $pages) { ?>
                                            <li><span><span aria-hidden="true"><i class="fas fa-angle-double-right fa-border text-light"></i></span></span></li>
                                        <?php } else { ?>
                                            <li><a href="<?= $formLink ?>&amp;page=<?= $pages ?>" title="<?= $LANG['page_last'] ?>"><span><i class="fas fa-angle-double-right fa-border"></i></span></a></li>
                                        <?php } ?>
                                    </ul>

                                </div>
                            </div>
                        </div>

                        <!-- Log settings -->
                        <div class="tab-pane fade" id="tabSettings" role="tabpanel" aria-labelledby="tabSettings-tab">

                            <div style="height:20px;"></div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_logSave"><?= $LANG['btn_save'] ?></button>
                                </div>
                            </div>
                            <div style="height:20px;"></div>

                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    foreach ($viewData['types'] as $type) {
                                        $color = "text-" . $C->read("logcolor" . $type);
                                    ?>
                                        <div class="row" style="border-bottom: 1px dotted; padding-top: 10px; padding-bottom: 10px;">
                                            <div class="col-lg-3 <?= $color ?>"><label><i class="fas fa-tag fa-lg fa-menu"></i><?= $type ?></label></div>
                                            <div class="col-lg-3">
                                                <input style="margin-right: 10px;" name="chk_log<?= $type ?>" value="chk_log<?= $type ?>" type="checkbox" <?= ($C->read("log" . $type)) ? ' checked=""' : '' ?>><?= $LANG['log_settings_log'] ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <input style="margin-right: 10px;" name="chk_logfilter<?= $type ?>" value="chk_logfilter<?= $type ?>" type="checkbox" <?= ($C->read("logfilter" . $type)) ? ' checked=""' : '' ?>><?= $LANG['log_settings_show'] ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="radio"><label><input name="opt_logcolor<?= $type ?>" value="default" tabindex="<?= $tabindex++; ?>" type="radio" <?= ($C->read("logcolor" . $type) == "default") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-default"></i></label></div>
                                                <div class="radio"><label><input name="opt_logcolor<?= $type ?>" value="primary" tabindex="<?= $tabindex++; ?>" type="radio" <?= ($C->read("logcolor" . $type) == "primary") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-primary"></i></label></div>
                                                <div class="radio"><label><input name="opt_logcolor<?= $type ?>" value="info" tabindex="<?= $tabindex++; ?>" type="radio" <?= ($C->read("logcolor" . $type) == "info") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-info"></i></label></div>
                                                <div class="radio"><label><input name="opt_logcolor<?= $type ?>" value="success" tabindex="<?= $tabindex++; ?>" type="radio" <?= ($C->read("logcolor" . $type) == "success") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-success"></i></label></div>
                                                <div class="radio"><label><input name="opt_logcolor<?= $type ?>" value="warning" tabindex="<?= $tabindex++; ?>" type="radio" <?= ($C->read("logcolor" . $type) == "warning") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-warning"></i></label></div>
                                                <div class="radio"><label><input name="opt_logcolor<?= $type ?>" value="danger" tabindex="<?= $tabindex++; ?>" type="radio" <?= ($C->read("logcolor" . $type) == "danger") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-danger"></i></label></div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div style="height:20px;"></div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_logSave"><?= $LANG['btn_save'] ?></button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </form>
    </div>
</div>

<script>
    $('#sel_logPeriod').change(function() {
        if (this.value == "custom") {
            $("#logPeriodFrom").prop('disabled', false);
            $("#logPeriodTo").prop('disabled', false);
        } else {
            $("#logPeriodFrom").prop('disabled', true);
            $("#logPeriodTo").prop('disabled', true);
        }
    });
</script>