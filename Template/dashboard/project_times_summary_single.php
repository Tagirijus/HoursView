<?php $times = $tagiTimes($project['id']); ?>

<div class="thv-dashboard-summary-single">
    &ndash;

    <span class="thv-estimated-color">
        <?php echo round($times['all']['_total']['estimated'], 2); ?>h
    </span>
    <span></span>

    <span class="thv-spent-color">
        <?php echo round($times['all']['_total']['spent'], 2); ?>h
    </span>
    <span></span>

    <span class="thv-remaining-color">
        <?php echo round($times['all']['_total']['remaining'], 2); ?>h
    </span>

</div>
