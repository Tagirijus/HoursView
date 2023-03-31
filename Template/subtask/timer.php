<span class="subtask-time-tracking">
    <?php if (! empty($subtask['time_spent'])): ?>
        <?= $this->hoursViewHelper->floatToHHMM($subtask['time_spent']) . 'h ' . t('spent') ?>
    <?php endif ?>

    <?php if (! empty($subtask['time_spent']) && ! empty($subtask['time_estimated'])): ?>/<?php endif ?>

    <?php if (! empty($subtask['time_estimated'])): ?>
        <?= $this->hoursViewHelper->floatToHHMM($subtask['time_estimated']) . 'h ' . t('estimated') ?>
    <?php endif ?>

    <?php if ($this->user->hasProjectAccess('SubtaskController', 'edit', $task['project_id']) && $subtask['user_id'] == $this->user->getId()): ?>
        <?= $this->subtask->renderTimer($task, $subtask) ?>
    <?php endif ?>
</span>
