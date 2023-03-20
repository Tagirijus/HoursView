<?php

$times = $tagiTimes($project['id']);
$captions = $this->hoursViewHelper->getLevelCaptions();

?>

<div class="thv-table-board-wrapper">

<table class="thv-table-board">


    <!-- LEVEL 1 -->
    <?php if ($times['level_1']['_has_times']): ?>

        <tr>
            <td>
                <span class="thv-weak-color">
                    <?= $captions['level_1'] ?>:
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_1']['_total']['estimated']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_1']['_total']['spent']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_1']['_total']['remaining']); ?>h
                </span>
            </td>
        </tr>

    <?php endif ?>


    <!-- LEVEL 2 -->
    <?php if ($times['level_2']['_has_times']): ?>

        <tr>
            <td>
                <span class="thv-weak-color">
                    <?= $captions['level_2'] ?>:
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_2']['_total']['estimated']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_2']['_total']['spent']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_2']['_total']['remaining']); ?>h
                </span>
            </td>
        </tr>

    <?php endif ?>


    <!-- LEVEL 3 -->
    <?php if ($times['level_3']['_has_times']): ?>

        <tr>
            <td>
                <span class="thv-weak-color">
                    <?= $captions['level_3'] ?>:
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_3']['_total']['estimated']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_3']['_total']['spent']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_3']['_total']['remaining']); ?>h
                </span>
            </td>
        </tr>

    <?php endif ?>


    <!-- LEVEL 4 -->
    <?php if ($times['level_4']['_has_times']): ?>

        <tr>
            <td>
                <span class="thv-weak-color">
                    <?= $captions['level_4'] ?>:
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_4']['_total']['estimated']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_4']['_total']['spent']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_4']['_total']['remaining']); ?>h
                </span>
            </td>
        </tr>

    <?php endif ?>


    <!-- ALL -->
    <?php if ($times['all']['_has_times']): ?>

        <tr style="opacity: .7; font-size: .7em;">
            <td>
                <span class="thv-weak-color">All:</span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['estimated']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['spent']); ?>h
                </span>
            </td>

            <td>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['remaining']); ?>h
                </span>
            </td>
        </tr>

    <?php endif ?>


</table>
</div>
