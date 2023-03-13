<?php $times = $tagiTimes($user['id']); ?>

<div class="thv-dashboard-summary-all thv-dashboard-summary-all-text">

    <span class="thv-title-color">
        <?php echo t('Estimated'); ?>:
    </span>
    <span class="thv-estimated-color">
        <?php echo round($times['estimated'], 2); ?>h
    </span>
    <span></span>
    <span></span>

    <span class="thv-title-color">
        <?php echo t('Spent'); ?>:
    </span>
    <span class="thv-spent-color">
        <?php echo round($times['spent'], 2); ?>h
    </span>
    <span></span>
    <span></span>

    <span class="thv-title-color">
        <?php echo t('Remaining'); ?>:
    </span>
    <span class="thv-remaining-color">
        <?php echo round($times['estimated'] - $times['spent'], 2); ?>h
    </span>
    <span></span>
    <span></span>

</div>
