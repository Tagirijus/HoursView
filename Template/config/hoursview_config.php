<div class="page-header">
    <h2><?= t('HoursView configuration') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('HoursViewController', 'saveConfig', ['plugin' => 'HoursView']) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <br>


    <!-- LEVEL COLUMNS -->

    <p>
        <h3><?= t('Levels columns') ?></h3>
    </p>

    <p>
        <?= t('Each level can have comma seperated column names. This columns will be used for this levels time calculation. Use lowercase for the column names.') ?>
    </p>
    <div class="task-form-container">

        <div class="task-form-main-column">
            <?= $this->form->label('Level 1 ' . t('Columns'), 'level_1_columns') ?>
            <?= $this->form->text('level_1_columns', ['level_1_columns' => $level_1_columns], [], [
                'autofocus',
                'tabindex="1"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label('Level 2 ' . t('Columns'), 'level_2_columns') ?>
            <?= $this->form->text('level_2_columns', ['level_2_columns' => $level_2_columns], [], [
                'autofocus',
                'tabindex="2"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label('Level 3 ' . t('Columns'), 'level_3_columns') ?>
            <?= $this->form->text('level_3_columns', ['level_3_columns' => $level_3_columns], [], [
                'autofocus',
                'tabindex="3"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label('Level 4 ' . t('Columns'), 'level_4_columns') ?>
            <?= $this->form->text('level_4_columns', ['level_4_columns' => $level_4_columns], [], [
                'autofocus',
                'tabindex="4"'
            ]) ?>
        </div>

    </div>

    <br>
    <br>


    <!-- LEVEL CAPTIONS -->

    <p>
        <h3><?= t('Levels captions') ?></h3>
    </p>

    <p>
        <?= t('The captions / titles for the columns for the frontend.') ?>
    </p>
    <div class="task-form-container">

        <div class="task-form-main-column">
            <?= $this->form->label('Level 1 ' . t('caption'), 'level_1_caption') ?>
            <?= $this->form->text('level_1_caption', ['level_1_caption' => $level_1_caption], [], [
                'autofocus',
                'tabindex="1"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label('Level 2 ' . t('caption'), 'level_2_caption') ?>
            <?= $this->form->text('level_2_caption', ['level_2_caption' => $level_2_caption], [], [
                'autofocus',
                'tabindex="2"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label('Level 3 ' . t('caption'), 'level_3_caption') ?>
            <?= $this->form->text('level_3_caption', ['level_3_caption' => $level_3_caption], [], [
                'autofocus',
                'tabindex="3"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label('Level 4 ' . t('caption'), 'level_4_caption') ?>
            <?= $this->form->text('level_4_caption', ['level_4_caption' => $level_4_caption], [], [
                'autofocus',
                'tabindex="4"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label('All ' . t('caption'), 'all_caption') ?>
            <?= $this->form->text('all_caption', ['all_caption' => $all_caption], [], [
                'autofocus',
                'tabindex="5"'
            ]) ?>
        </div>

    </div>

    <br>
    <br>


    <!-- PERCENTAGE -->

    <p>
        <h3><?= t('Progress bar') ?></h3>
    </p>

    <p>
        <?= t('Configure the progress bar on the task cards.') ?>
    </p>
    <div class="task-form-container">

        <div class="task-form-main-column">
            <?= $this->form->label(t('Progress bar on board cards'), 'progressbar_enabled') ?>
            <?= $this->form->checkbox('progressbar_enabled', t('enabled'), 1, $progressbar_enabled, '', [
                'autofocus',
                'tabindex="6"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label(t('Progress bar opacity when not started'), 'progressbar_0_opacity') ?>
            <?= $this->form->text('progressbar_0_opacity', ['progressbar_0_opacity' => $progressbar_0_opacity], [], [
                'autofocus',
                'tabindex="7"'
            ]) ?>
        </div>

        <div class="task-form-main-column">
            <?= $this->form->label(t('Progress bar opacity when started'), 'progressbar_opacity') ?>
            <?= $this->form->text('progressbar_opacity', ['progressbar_opacity' => $progressbar_opacity], [], [
                'autofocus',
                'tabindex="8"'
            ]) ?>
        </div>

    </div>



    <div class="task-form-bottom">
        <?= $this->modal->submitButtons() ?>
    </div>

</form>
