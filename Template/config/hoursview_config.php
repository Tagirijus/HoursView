<div class="page-header">
    <h2><?= t('Levels') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('HoursViewController', 'saveConfig', ['plugin' => 'HoursView']) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <p>
        <?= t('Each level can have comma seperated column names. This columns will be used for this levels time calculation. Use lowercase for the column names.') ?>
    </p>
    <div class="task-form-container">

        <div class="task-form-main-column">
            <?= $this->form->label(t('Level 1'), 'level_1') ?>
            <?= $this->form->text('level_1', ['level_1' => $level_1], [], [
                'autofocus',
                'tabindex="1"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label(t('Level 2'), 'level_2') ?>
            <?= $this->form->text('level_2', ['level_2' => $level_2], [], [
                'autofocus',
                'tabindex="2"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label(t('Level 3'), 'level_3') ?>
            <?= $this->form->text('level_3', ['level_3' => $level_3], [], [
                'autofocus',
                'tabindex="3"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label(t('Level 4'), 'level_4') ?>
            <?= $this->form->text('level_4', ['level_4' => $level_4], [], [
                'autofocus',
                'tabindex="4"'
            ]) ?>
        </div>

        <div class="task-form-bottom">
            <?= $this->modal->submitButtons() ?>
        </div>
    </div>
</form>
