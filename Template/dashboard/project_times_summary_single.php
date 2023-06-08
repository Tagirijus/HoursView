<?php
    $times = $tagiTimes($project['id']);
    $times = $this->hoursViewHelper->prepareProjectTimesWithConfig($times);
?>

<div class="thv-board-column">
    &ndash;

    <span class="thv-estimated-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['estimated']); ?>h
    </span>
    <span></span>

    <span class="thv-spent-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['spent']); ?>h
        <i class="thv-font-small">(<?= $this->hoursViewHelper->floatToHHMM($times['spent'] - $times['overtime']); ?>h + <?= $this->hoursViewHelper->floatToHHMM($times['overtime']); ?>h)</i>
    </span>
    <span></span>

    <span class="thv-remaining-color">
        <?= $this->hoursViewHelper->floatToHHMM($times['remaining']); ?>h
    </span>
    <span></span>


    <?php
        $pseudo_task = [
            'time_estimated' => $times['estimated'],
            'time_spent' => $times['spent'],
            'time_remaining' => $times['remaining'],
        ];
        $output = $this->hoursViewHelper->getPercentForTaskAsString($pseudo_task, '%', true);
    ?>
    <?php if ($output == '100%'): ?>
    <span class="thv-100-percent">
    <?php else: ?>
    <span>
    <?php endif ?>
        <?= $output; ?>
    </span>

</div>
