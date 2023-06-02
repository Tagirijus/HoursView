<?php

$times = $tagiTimes($project['id']);
$captions = $this->hoursViewHelper->getLevelCaptions();

?>

<div class="thv-box-wrapper-board thv-font-small">


    <!-- LEVEL 1 -->
    <?php if ($captions['level_1'] != ''): ?>

        <div class="thv-box-item">

            <div class="thv-weak-color">
                <?= $captions['level_1'] ?>:
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_1']['_total']['estimated']); ?>h
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_1']['_total']['spent']); ?>h
                    <i class="thv-font-weak">(<?= $this->hoursViewHelper->floatToHHMM($times['level_1']['_total']['spent'] - $times['level_1']['_total']['overtime']); ?>h + <?= $this->hoursViewHelper->floatToHHMM($times['level_1']['_total']['overtime']); ?>h)</i>
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_1']['_total']['remaining']); ?>h
                </span>
            </div>

        </div>

    <?php endif ?>


    <!-- LEVEL 2 -->
    <?php if ($captions['level_2'] != ''): ?>

        <div class="thv-box-item">

            <div class="thv-weak-color">
                <?= $captions['level_2'] ?>:
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_2']['_total']['estimated']); ?>h
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_2']['_total']['spent']); ?>h
                    <i class="thv-font-weak">(<?= $this->hoursViewHelper->floatToHHMM($times['level_2']['_total']['spent'] - $times['level_2']['_total']['overtime']); ?>h + <?= $this->hoursViewHelper->floatToHHMM($times['level_2']['_total']['overtime']); ?>h)</i>
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_2']['_total']['remaining']); ?>h
                </span>
            </div>

        </div>

    <?php endif ?>


    <!-- LEVEL 3 -->
    <?php if ($captions['level_3'] != ''): ?>

        <div class="thv-box-item">

            <div class="thv-weak-color">
                <?= $captions['level_3'] ?>:
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_3']['_total']['estimated']); ?>h
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_3']['_total']['spent']); ?>h
                    <i class="thv-font-weak">(<?= $this->hoursViewHelper->floatToHHMM($times['level_3']['_total']['spent'] - $times['level_3']['_total']['overtime']); ?>h + <?= $this->hoursViewHelper->floatToHHMM($times['level_3']['_total']['overtime']); ?>h)</i>
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_3']['_total']['remaining']); ?>h
                </span>
            </div>

        </div>

    <?php endif ?>


    <!-- LEVEL 4 -->
    <?php if ($captions['level_4'] != ''): ?>

        <div class="thv-box-item">

            <div class="thv-weak-color">
                <?= $captions['level_4'] ?>:
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_4']['_total']['estimated']); ?>h
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_4']['_total']['spent']); ?>h
                    <i class="thv-font-weak">(<?= $this->hoursViewHelper->floatToHHMM($times['level_4']['_total']['spent'] - $times['level_4']['_total']['overtime']); ?>h + <?= $this->hoursViewHelper->floatToHHMM($times['level_4']['_total']['overtime']); ?>h)</i>
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['level_4']['_total']['remaining']); ?>h
                </span>
            </div>

        </div>

    <?php endif ?>


    <!-- ALL -->
    <?php if ($captions['all'] != ''): ?>

        <div class="thv-box-item thv-box-all">

            <div class="thv-weak-color">
                All:
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Estimated'); ?>:
                </span>
                <span class="thv-estimated-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['estimated']); ?>h
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Spent'); ?>:
                </span>
                <span class="thv-spent-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['spent']); ?>h
                    <i class="thv-font-weak">(<?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['spent'] - $times['all']['_total']['overtime']); ?>h + <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['overtime']); ?>h)</i>
                </span>
            </div>

            <div>
                <span class="thv-title-color">
                    <?= t('Remaining'); ?>:
                </span>
                <span class="thv-remaining-color">
                    <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['remaining']); ?>h
                </span>
            </div>

        </div>

    <?php endif ?>


</div>
