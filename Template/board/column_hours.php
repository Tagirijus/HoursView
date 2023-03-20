<?php $times = $tagiTimes($column); ?>

<div class="thv-column-wrapper">
    <span class="thv-spent-color"><?= round($times['spent'], 2); ?></span>/<span class="thv-estimated-color"><?= round($times['estimated'], 2); ?></span>h
</div>
