<?php $times = $tagiTimes($project['id']); ?>

<div class="thv-board-column">
    &ndash;

    <span class="thv-estimated-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['estimated']); ?>h
    </span>
    <span></span>

    <span class="thv-spent-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['spent']); ?>h
        <i class="thv-font-small">(<?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['spent'] - $times['all']['_total']['overtime']); ?>h + <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['overtime']); ?>h)</i>
    </span>
    <span></span>

    <span class="thv-remaining-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['all']['_total']['remaining']); ?>h
    </span>
    <span></span>

    <span>
        <?php
            $pseudo_task = [
                'time_estimated' => $times['all']['_total']['estimated'],
                'time_spent' => $times['all']['_total']['spent'],
            ];
        ?>
        <?= $this->hoursViewHelper->getPercentForTaskAsString($pseudo_task, '%', true); ?>
    </span>

</div>
