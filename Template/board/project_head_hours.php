<?php $times = $tagiTimes($project['id']); ?>

<div class="thv-over-board-wrapper">

    <div class="thv-over-board-line">
        <span class="thv-title-color">
            <?php echo t('All') . ':'; ?>
        </span>
        <span class="thv-over-board-line-caption">
            <?php echo t('Estimated') . ':'; ?>
        </span>
        <span class="thv-estimated-color">
            <?php echo round($times['all']['_total']['estimated'], 2) . 'h'; ?>
        </span>
        <span class="thv-over-board-line-caption">
            <?php echo t('Spent') . ':'; ?>
        </span>
        <span class="thv-spent-color">
            <?php echo round($times['all']['_total']['spent'], 2) . 'h'; ?>
        </span>
        <span class="thv-over-board-line-caption">
            <?php echo t('Remaining') . ':'; ?>
        </span>
        <span class="thv-remaining-color">
            <?php echo round($times['all']['_total']['estimated'] - $times['all']['_total']['spent'], 2) . 'h'; ?>
        </span>
    </div>

    <div class="thv-over-board-line">
        <span class="thv-title-color">
            <?php echo t('Dashboard only') . ':'; ?>
        </span>
        <span class="thv-over-board-line-caption">
            <?php echo t('Estimated') . ':'; ?>
        </span>
        <span class="thv-estimated-color">
            <?php echo round($times['dashboard']['_total']['estimated'], 2) . 'h'; ?>
        </span>
        <span class="thv-over-board-line-caption">
            <?php echo t('Spent') . ':'; ?>
        </span>
        <span class="thv-spent-color">
            <?php echo round($times['dashboard']['_total']['spent'], 2) . 'h'; ?>
        </span>
        <span class="thv-over-board-line-caption">
            <?php echo t('Remaining') . ':'; ?>
        </span>
        <span class="thv-remaining-color">
            <?php echo round($times['dashboard']['_total']['estimated'] - $times['dashboard']['_total']['spent'], 2) . 'h'; ?>
        </span>
    </div>

</div>
