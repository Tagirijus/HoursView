<?php

$times = $tagiTimes($column);
$hover_text = t('Estimated') . ': ' . $this->hoursViewHelper->floatToHHMM($times['estimated']) . 'h';
$hover_text .= "\n";
$hover_text .= t('Spent') . ': ' . $this->hoursViewHelper->floatToHHMM($times['spent']) . 'h';
$hover_text .= "\n";
$hover_text .= t('Remaining') . ': ' . $this->hoursViewHelper->floatToHHMM($times['remaining']) . 'h';

?>


<div class="thv-column-wrapper thv-font-small" title="<?= $hover_text ?>">
    <span class="ui-helper-hidden-accessible"><?= $hover_text ?></span>
    <span class="thv-spent-color"><?= $this->hoursViewHelper->floatToHHMM(round($times['spent'], 2)); ?></span>/<span class="thv-estimated-color"><?= $this->hoursViewHelper->floatToHHMM(round($times['estimated'], 2)); ?></span>h
</div>
