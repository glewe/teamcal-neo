<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Calendar View Month Header View
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
view.calendarviewmonthheader (<?= $viewData['year'] . $viewData['month'] ?>)
-->

<!-- Row: Month name and day numbers -->
<tr>
    <th class="m-monthname"><?= $viewData['dateInfo']['monthname'] ?> <?= $viewData['dateInfo']['year'] ?></th>

    <?php for ($i = $daystart; $i <= $dayend; $i++) {

        $notestart = '';
        $noteend = '';
        $notestyle = '';

        if ($D->get($viewData['year'] . $viewData['month'] . sprintf("%02d", $i), 'all', $viewData['regionid'], true)) {

            //
            // This is a global daynote
            //
            $notestart = '<div style="width: 100%; height: 100%;" data-placement="top" data-type="' . $D->color . '" data-toggle="tooltip" title="' . $D->daynote . '">';
            $noteend = '</div>';
            $notestyle = 'background-image: url(images/ovl_daynote.gif); background-repeat: no-repeat; background-position: top right;';
        }

        if (isset($viewData['dayStyles'][$i]) and strlen($viewData['dayStyles'][$i])) {

            $dayStyles = ' style="' . $viewData['dayStyles'][$i] . $notestyle . '"';
        } else {

            $dayStyles = ' style="' . $notestyle . '"';
        }
    ?>
        <th class="m-daynumber text-center" <?= $dayStyles ?>><?= $notestart . $i . $noteend ?></th>
    <?php } ?>
</tr>

<!-- Row: Weekdays -->
<tr>
    <th class="m-label">&nbsp;</th>

    <?php for ($i = $daystart; $i <= $dayend; $i++) {

        if (isset($viewData['dayStyles'][$i]) and strlen($viewData['dayStyles'][$i]))

            $dayStyles = ' style="' . $viewData['dayStyles'][$i] . '"';

        else

            $dayStyles = '';

        $prop = 'wday' . $i;
    ?>
        <th class="m-weekday text-center" <?= $dayStyles ?>><?= $LANG['weekdayShort'][$M->$prop] ?></th>
    <?php } ?>
</tr>

<?php if ($viewData['showWeekNumbers']) { ?>
    <!-- Row: Week numbers -->
    <tr>
        <th class="m-label"><?= $LANG['weeknumber'] ?></th>

        <?php for ($i = $daystart; $i <= $dayend; $i++) {

            $prop = 'week' . $i;
            $wprop = 'wday' . $i; ?>
            <th class="m-weeknumber text-center<?= (($M->$wprop == $viewData['firstDayOfWeek']) ? ' first' : ' inner') ?>"><?= (($M->$wprop == $viewData['firstDayOfWeek']) ? $M->$prop : '') ?></th>

        <?php } ?>
    </tr>
<?php } ?>