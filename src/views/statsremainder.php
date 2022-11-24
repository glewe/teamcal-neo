<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Remainder Statistics View
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
view.statsabstype
-->
<div class="container content">

    <?php
    if ($showAlert and $C->read("showAlerts") != "none") {
        if (
            $C->read("showAlerts") == "all" or
            $C->read("showAlerts") == "warnings" and ($alertData['type'] == "warning" or $alertData['type'] == "danger")
        ) {
            echo createAlertBox($alertData);
        }
    }
    $tabindex = 1;
    ?>

    <form class="form-control-horizontal noprint" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

        <div class="page-menu">
            <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" data-bs-toggle="modal" data-bs-target="#modalGroup"><?= $LANG['group'] ?> <span class="badge badge-light"><?= $viewData['groupName'] ?></span></button>
            <?php if (!$C->read('currentYearOnly')) { ?>
                <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" data-bs-toggle="modal" data-bs-target="#modalYear"><?= $LANG['year'] ?> <span class="badge badge-light"><?= $viewData['year'] ?></span></button>
            <?php } ?>
            <button type="button" class="btn btn-warning" tabindex="<?= $tabindex++; ?>" data-bs-toggle="modal" data-bs-target="#modalDiagram"><?= $LANG['diagram'] ?></button>
            <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>"><?= $LANG['btn_reset'] ?></a>
        </div>
        <div style="height:20px;"></div>

        <!-- Modal: Group -->
        <?= createModalTop('modalGroup', $LANG['stats_modalGroupTitle']) ?>
        <span class="text-bold"><?= $LANG['stats_group'] ?></span><br>
        <span class="text-normal"><?= $LANG['stats_group_comment'] ?></span>
        <select id="group" class="form-control" name="sel_group" tabindex="<?= $tabindex++ ?>">
            <option value="all" <?= (($viewData['groupid'] == 'all') ? ' selected="selected"' : '') ?>><?= $LANG['all'] ?></option>
            <?php foreach ($viewData['groups'] as $grp) { ?>
                <option value="<?= $grp['id'] ?>" <?= (($viewData['groupid'] == $grp['id']) ? 'selected="selected"' : '') ?>><?= $grp['name'] ?></option>
            <?php } ?>
        </select><br>
        <?= createModalBottom('btn_apply', 'success', $LANG['btn_apply']) ?>

        <!-- Modal: Year -->
        <?php if (!$C->read('currentYearOnly')) {
            echo createModalTop('modalYear', $LANG['stats_modalYearTitle']); ?>
            <div>
                <span class="text-bold"><?= $LANG['stats_year'] ?></span><br>
                <span class="text-normal"><?= $LANG['stats_year_comment'] ?></span>
                <select id="sel_year" class="form-control" name="sel_year" tabindex="<?= $tabindex++ ?>">
                    <option value="<?= date("Y") - 1 ?>" <?= (($viewData['year'] == date("Y") - 1) ? "selected" : "") ?>><?= date("Y") - 1 ?></option>
                    <option value="<?= date("Y") ?>" <?= (($viewData['year'] == date("Y")) ? "selected" : "") ?>><?= date("Y") ?></option>
                    <option value="<?= date("Y") + 1 ?>" <?= (($viewData['year'] == date("Y") + 1) ? "selected" : "") ?>><?= date("Y") + 1 ?></option>
                </select><br>
            </div>
        <?php echo createModalBottom('btn_apply', 'success', $LANG['btn_apply']);
        } ?>

        <!-- Modal: Diagram -->
        <?= createModalTop('modalDiagram', $LANG['stats_modalDiagramTitle']) ?>
        <div>
            <span class="text-bold"><?= $LANG['stats_color'] ?></span><br>
            <span class="text-normal"><?= $LANG['stats_color_comment'] ?></span>
            <select id="sel_color" class="form-control" name="sel_color" tabindex="<?= $tabindex++ ?>">
                <option value="blue" <?= (($viewData['color'] == 'blue') ? "selected" : "") ?>><?= $LANG['blue'] ?></option>
                <option value="cyan" <?= (($viewData['color'] == 'cyan') ? "selected" : "") ?>><?= $LANG['cyan'] ?></option>
                <option value="green" <?= (($viewData['color'] == 'green') ? "selected" : "") ?>><?= $LANG['green'] ?></option>
                <option value="grey" <?= (($viewData['color'] == 'grey') ? "selected" : "") ?>><?= $LANG['grey'] ?></option>
                <option value="magenta" <?= (($viewData['color'] == 'magenta') ? "selected" : "") ?>><?= $LANG['magenta'] ?></option>
                <option value="orange" <?= (($viewData['color'] == 'orange') ? "selected" : "") ?>><?= $LANG['orange'] ?></option>
                <option value="purple" <?= (($viewData['color'] == 'purple') ? "selected" : "") ?>><?= $LANG['purple'] ?></option>
                <option value="red" <?= (($viewData['color'] == 'red') ? "selected" : "") ?>><?= $LANG['red'] ?></option>
                <option value="yellow" <?= (($viewData['color'] == 'yellow') ? "selected" : "") ?>><?= $LANG['yellow'] ?></option>
            </select><br>
            <br>
        </div>
        <?= createModalBottom('btn_apply', 'success', $LANG['btn_apply']) ?>

    </form>

    <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        ?>
        <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>">
            <i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg fa-header"></i><?= $LANG['stats_title_remainder'] ?>&nbsp;(<?= $viewData['year'] ?>)<span class="badge badge-secondary float-end badge-header-right"><i data-placement="bottom" data-type="info" data-bs-toggle="tooltip" title="<?= $LANG['stats_total'] ?>"><?= $viewData['total'] ?></i></span><?= $pageHelp ?>
        </div>
        <div class="card-body">
            <p><?= $LANG['stats_remainder_desc'] ?></p>
            <canvas id="myChart" height="<?= $viewData['height'] ?>"></canvas>

            <script>
                //
                // Chart.js Bar Chart
                //
                var color = Chart.helpers.color;
                var horizontalBarChartData = {
                    labels: [<?= $viewData['labels'] ?>],
                    datasets: [{
                            label: '<?= $LANG['allowance'] ?>',
                            backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
                            borderColor: window.chartColors.green,
                            borderWidth: 1,
                            data: [<?= $viewData['dataAllowance'] ?>]
                        },
                        {
                            label: '<?= $LANG['remainder'] ?>',
                            backgroundColor: color(window.chartColors.<?= $viewData['color'] ?>).alpha(0.5).rgbString(),
                            borderColor: window.chartColors.<?= $viewData['color'] ?>,
                            borderWidth: 1,
                            data: [<?= $viewData['dataRemainder'] ?>]
                        }
                    ]
                };

                window.onload = function() {
                    var ctx = document.getElementById("myChart").getContext("2d");
                    window.myHorizontalBar = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: horizontalBarChartData,
                        options: {
                            elements: {
                                rectangle: {
                                    borderWidth: 2,
                                }
                            },
                            responsive: true,
                            legend: {
                                display: false
                            },
                            title: {
                                display: false
                            }
                        }
                    });
                };
            </script>

        </div>
    </div>

</div>