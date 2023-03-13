<?php $times = $tagiTimes($column); ?>

<div class="thv-column-wrapper">
    <span class="thv-spent-color"><?php echo round($times['spent'], 2); ?></span>/<span class="thv-estimated-color"><?php echo round($times['estimated'], 2); ?></span>h
</div>
