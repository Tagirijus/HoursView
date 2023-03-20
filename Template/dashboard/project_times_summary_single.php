<?php $times = $tagiTimes($project['id']); ?>

<div class="thv-dashboard-summary-single">
    &ndash;

    <span class="thv-estimated-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['estimated']); ?>h
    </span>
    <span></span>

    <span class="thv-spent-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['spent']); ?>h
    </span>
    <span></span>

    <span class="thv-remaining-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['remaining']); ?>h
    </span>

</div>
