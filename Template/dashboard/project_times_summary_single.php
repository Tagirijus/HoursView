<?php
    $times = $tagiTimes($project['id']);
    $progress_level = $this->hoursViewHelper->getConfig()['progress_home_project_level'];
    if (!array_key_exists($progress_level, $times)) {
        $progress_level = 'all';
    }
?>

<div class="thv-board-column">
    &ndash;

    <span class="thv-estimated-color">
        <?= $this->hoursViewHelper->floatToHHMM($times[$progress_level]['_total']['estimated']); ?>h
    </span>
    <span></span>

    <span class="thv-spent-color">
        <?= $this->hoursViewHelper->floatToHHMM($times[$progress_level]['_total']['spent']); ?>h
        <i class="thv-font-small">(<?= $this->hoursViewHelper->floatToHHMM($times[$progress_level]['_total']['spent'] - $times[$progress_level]['_total']['overtime']); ?>h + <?= $this->hoursViewHelper->floatToHHMM($times[$progress_level]['_total']['overtime']); ?>h)</i>
    </span>
    <span></span>

    <span class="thv-remaining-color">
        <?= $this->hoursViewHelper->floatToHHMM($times[$progress_level]['_total']['remaining']); ?>h
    </span>
    <span></span>

    <span>
        <?php
            $pseudo_task = [
                'time_estimated' => $times[$progress_level]['_total']['estimated'],
                'time_spent' => $times[$progress_level]['_total']['spent'],
            ];
        ?>
        <?= $this->hoursViewHelper->getPercentForTaskAsString($pseudo_task, '%', true); ?>
    </span>

</div>
