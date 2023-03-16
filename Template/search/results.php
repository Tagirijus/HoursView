<?php $times = $this->tagiKPHoursViewHelper->getTimesFromTasks($paginator->getCollection())['all']['_total']; ?>

<div class="thv-dashboard-summary-all thv-dashboard-summary-all-text">

    <span class="thv-title-color">
        <?php echo t('Estimated'); ?>:
    </span>
    <span class="thv-estimated-color">
        <?php echo round($times['estimated'], 2); ?>h
    </span>
    <span></span>
    <span></span>

    <span class="thv-title-color">
        <?php echo t('Spent'); ?>:
    </span>
    <span class="thv-spent-color">
        <?php echo round($times['spent'], 2); ?>h
    </span>
    <span></span>
    <span></span>

    <span class="thv-title-color">
        <?php echo t('Remaining'); ?>:
    </span>
    <span class="thv-remaining-color">
        <?php echo round($times['remaining'], 2); ?>h
    </span>

</div>


<div class="table-list">
    <?= $this->render('task_list/header', array(
        'paginator' => $paginator,
    )) ?>

    <?php foreach ($paginator->getCollection() as $task): ?>
        <div class="table-list-row color-<?= $task['color_id'] ?>">
            <?= $this->render('task_list/task_title', array(
                'task' => $task,
            )) ?>

            <?= $this->render('task_list/task_details', array(
                'task' => $task,
            )) ?>

            <?= $this->render('task_list/task_avatars', array(
                'task' => $task,
            )) ?>

            <?= $this->render('task_list/task_icons', array(
                'task' => $task,
            )) ?>

            <?= $this->render('task_list/task_subtasks', array(
                'task' => $task,
            )) ?>

            <?= $this->hook->render('template:search:task:footer', array('task' => $task)) ?>
        </div>
    <?php endforeach ?>
</div>

<?= $paginator ?>