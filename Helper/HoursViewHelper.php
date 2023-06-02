<?php

namespace Kanboard\Plugin\HoursView\Helper;

use Kanboard\Core\Base;
use Kanboard\Model\TaskModel;
use Kanboard\Model\ProjectModel;
use Kanboard\Model\SubtaskModel;


class HoursViewHelper extends Base
{
    /**
     * Calculates the remaining time for the given estimated
     * and the spent time. In case prevent_negative is True,
     * it returns, for example, no negative values, but
     * 0 then.
     *
     * @param  float  $estimated
     * @param  float  $spent
     * @param  boolean $prevent_negative
     * @return float
     */
    public function calculateRemaining($estimated, $spent, $prevent_negative = True)
    {
        if ($estimated - $spent < 0 && $prevent_negative) {
            return 0;
        } else {
            return $estimated - $spent;
        }
    }

    /**
     * Get the estimated and spent times in the columns for
     * the total (all) and the levels (level_1, level_2, ...).
     *
     * New since v1.2.0: remaining
     *
     *
     * Array output:
     *
     * [
     *     'all' => [
     *         '_total' => [
     *             'estimated' => 8,
     *             'spent' => 6.5,
     *             'remaining' => 1.5
     *         ],
     *         'column b' => [
     *             'estimated' => 5,
     *             'spent' => 4.5,
     *             'remaining' => 0.5
     *         ]
     *     ],
     *     'level_1' => [
     *         '_total' => [
     *             'estimated' => 7,
     *             'spent' => 5.5,
     *             'remaining' => 1.5
     *         ],
     *         'column a' => [
     *             'estimated' => 2,
     *             'spent' => 1,
     *             'remaining' => 1
     *         ]
     *     ],
     *     'level_2' => ...
     * ]
     *
     * @param  array $tasks
     * @return array
     */
    public function getTimesFromTasks($tasks)
    {
        $levels_columns = [
            'level_1' => explode(',', $this->configModel->get('hoursview_level_1_columns', '')),
            'level_2' => explode(',', $this->configModel->get('hoursview_level_2_columns', '')),
            'level_3' => explode(',', $this->configModel->get('hoursview_level_3_columns', '')),
            'level_4' => explode(',', $this->configModel->get('hoursview_level_4_columns', ''))
        ];

        $all = [
            '_has_times' => false,
            '_total' => [
                'estimated' => 0,
                'spent' => 0,
                'remaining' => 0
            ]
        ];
        $level_1 = $all;
        $level_2 = $all;
        $level_3 = $all;
        $level_4 = $all;
        $col_name = 'null';

        foreach ($tasks as $task) {

            // get column name
            $col_name = $task['column_name'];

            // set new column key in the time calc arrays
            $this->setTimeCalcKey($all, $col_name);
            $this->setTimeCalcKey($level_1, $col_name);
            $this->setTimeCalcKey($level_2, $col_name);
            $this->setTimeCalcKey($level_3, $col_name);
            $this->setTimeCalcKey($level_4, $col_name);

            // all: column times
            $all[$col_name]['estimated'] += $task['time_estimated'];
            $all[$col_name]['spent'] += $task['time_spent'];
            $all[$col_name]['remaining'] += $this->calculateRemaining($task['time_estimated'], $task['time_spent']);

            // all: total times
            $all['_total']['estimated'] += $task['time_estimated'];
            $all['_total']['spent'] += $task['time_spent'];
            $all['_total']['remaining'] += $this->calculateRemaining($task['time_estimated'], $task['time_spent']);
            $this->modifyHasTimes($all);


            // level times
            $this->addTimesForLevel($level_1, 'level_1', $levels_columns, $col_name, $task);
            $this->addTimesForLevel($level_2, 'level_2', $levels_columns, $col_name, $task);
            $this->addTimesForLevel($level_3, 'level_3', $levels_columns, $col_name, $task);
            $this->addTimesForLevel($level_4, 'level_4', $levels_columns, $col_name, $task);
        }

        return [
            'all' => $all,
            'level_1' => $level_1,
            'level_2' => $level_2,
            'level_3' => $level_3,
            'level_4' => $level_4
        ];
    }

    /**
     * Check if the given array has any time above 0
     * like estimated, spent or remaining and if so
     * set the _has_times to true.
     *
     * @param  array &$arr
     */
    protected function modifyHasTimes(&$arr)
    {
        if (
            $arr['_total']['estimated'] > 0
            || $arr['_total']['spent'] > 0
            || $arr['_total']['remaining'] > 0
        ) {
            $arr['_has_times'] = true;
        }
    }

    /**
     * Check if the array key exists and add it, if not.
     *
     * @param array &$arr
     * @param string $col_name
     */
    protected function setTimeCalcKey(&$arr, $col_name)
    {
        if (!isset($arr[$col_name])) {
            $arr[$col_name] = ['estimated' => 0, 'spent' => 0, 'remaining' => 0];
        }
    }

    /**
     * Function to add the calculation for each level in the
     * getTimesFromTasks() method.
     *
     * @param array &$level
     * @param string $level_key
     * @param array $levels
     * @param string $col_name
     * @param array $task
     */
    protected function addTimesForLevel(&$level, $level_key, $levels, $col_name, $task)
    {
        if (in_array(strtolower(is_null($col_name) ? '' : $col_name), $levels[$level_key])) {
            // dashbord: column times
            $level[$col_name]['estimated'] += $task['time_estimated'];
            $level[$col_name]['spent'] += $task['time_spent'];
            $level[$col_name]['remaining'] += $this->calculateRemaining($task['time_estimated'], $task['time_spent']);

            // level: total times
            $level['_total']['estimated'] += $task['time_estimated'];
            $level['_total']['spent'] += $task['time_spent'];
            $level['_total']['remaining'] += $this->calculateRemaining($task['time_estimated'], $task['time_spent']);
            $this->modifyHasTimes($level);
        }
    }

    /**
     * Get the estimated and spent times in the columns for
     * all tasks with a given project id.
     *
     * This method wraps basically the getTimesFromTasks()
     * method, but with a given project id to get the
     * linked tasks.
     *
     * @param  integer $projectId
     * @return array
     */
    public function getTimesByProjectId($projectId)
    {
        $tasks = $this->getTasksByProjectId($projectId);

        return $this->getTimesFromTasks($tasks);
    }

    /**
     * Get an array with the calculated times for
     * the given column array.
     *
     * Array output:
     *
     * [
     *     'estimated' => 2,
     *     'spent' => 1,
     *     'remaining' => 1
     * ]
     *
     * @param  array $column
     * @return array
     */
    public function getTimesForColumn($column)
    {
        $out = ['estimated' => 0, 'spent' => 0, 'remaining' => 0];
        if (isset($column['tasks'])) {
            foreach ($column['tasks'] as $task) {
                $out['estimated'] += $task['time_estimated'];
                $out['spent'] += $task['time_spent'];
                $out['remaining'] += $this->calculateRemaining($task['time_estimated'], $task['time_spent']);
            }
        }
        return $out;
    }

    /**
     * Basically some kind of wrapper function for getting
     * the array with all the columns for the project.
     *
     * Thus here the array-keys are the column id.
     *
     * @param  integer $projectId
     * @return array
     */
    protected function getColumnsByProjectId($projectId)
    {
        $out = [];
        $columns = $this->columnModel->getAll($projectId);
        foreach ($columns as $column) {
            $out[$column['id']] = $column;
        }
        return $out;
    }

    /**
     * Basically some kind of wrapper function for getting
     * the array with all the tasks for the project.
     *
     * @param  integer $projectId
     * @return array
     */
    protected function getTasksByProjectId($projectId)
    {
        $project = $this->projectModel->getById($projectId);
        $search = $this->helper->projectHeader->getSearchQuery($project);

        $query = $this->taskFinderModel->getExtendedQuery()
            ->eq(TaskModel::TABLE.'.project_id', $projectId);

        $builder = $this->taskLexer;
        $builder->withQuery($query);
        return $builder->build($search)->toArray();
    }

    /**
     * This one gets all tasks for the user and their
     * respecting times.
     *
     * Array output:
     *
     * [
     *     'estimated' => 2,
     *     'spent' => 1
     * ]
     *
     * @param  integer $userId
     * @return array
     */
    public function getTimesByUserId($userId)
    {
        $tasks = $this->taskFinderModel->getExtendedQuery()
            ->beginOr()
            ->eq(TaskModel::TABLE.'.owner_id', $userId)
            ->addCondition(TaskModel::TABLE.".id IN (SELECT task_id FROM ".SubtaskModel::TABLE." WHERE ".SubtaskModel::TABLE.".user_id='$userId')")
            ->closeOr()
            ->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_OPEN)
            ->eq(ProjectModel::TABLE.'.is_active', ProjectModel::ACTIVE)
            ->findAll();

        return $this->getTimesFromTasks($tasks);
    }

    /**
     * Get level captions from the config.
     *
     * @return array
     */
    public function getLevelCaptions()
    {
        $levels_captions = [
            'level_1' => $this->configModel->get('hoursview_level_1_caption', 'Level 1'),
            'level_2' => $this->configModel->get('hoursview_level_2_caption', 'Level 2'),
            'level_3' => $this->configModel->get('hoursview_level_3_caption', 'Level 3'),
            'level_4' => $this->configModel->get('hoursview_level_4_caption', 'Level 4')
        ];
        return $levels_captions;
    }

    /**
     * Represent the given float as a proper time string.
     *
     * @param  float $time
     * @return string
     */
    public function floatToHHMM($time)
    {
        if ($time < 0) {
            $time = $time * -1;
            $negative = true;
        } else {
            $negative = false;
        }
        $hours = (int) $time;
        $minutes = fmod($time, 1) * 60;
        if ($negative) {
            return sprintf('-%01d:%02d', $hours, $minutes);
        } else {
            return sprintf('%01d:%02d', $hours, $minutes);
        }
    }

    /**
     * Get configuration for progress bar.
     *
     * @return array
     */
    public function getProgressBarConfig()
    {
        $progressbar_config = [
            'enabled' => $this->configModel->get('hoursview_progressbar_enabled', 1),
            '0_opacity' => $this->configModel->get('hoursview_progressbar_0_opacity', 0.15),
            'opacity' => $this->configModel->get('hoursview_progressbar_opacity', 1)
        ];
        return $progressbar_config;
    }

    /**
     * Calculates the remaining or overtime for the given task,
     * depending on the subtasks, if they exist.
     * If not, simply use the task times.
     *
     * @param  array $task
     * @param  bool $considerSubtasks
     * @param  bool $overtime
     * @return float
     */
    public function getRemainingOrOvertimeForTask($task, $considerSubtasks = true, $overtime = false)
    {
        $out = 0.0;
        if (isset($task['id'])) {
            $subtasks = $this->subtaskModel->getAll($task['id']);

            // calculate remaining or overtime based on subtasks
            if (!empty($subtasks) && $considerSubtasks) {
                $tmp = $this->getRemainingOrOvertimeFromSubtasks($subtasks, $overtime);

            // calculate remaining or overtime based only on task itsekf
            } else {
                if ($overtime) {
                    $tmp = (float) $task['time_spent'] - (float) $task['time_estimated'];
                } else {
                    $tmp = (float) $task['time_estimated'] - (float) $task['time_spent'];
                }
            }

            if ($tmp > 0) {
                $out = $tmp;
            }
        }
        return round($out, 2);
    }

    /**
     * Calculate the remaining time from subtasks.
     *
     * @param  array $subtasks
     * @param  bool $overtime
     * @return float
     */
    protected function getRemainingOrOvertimeFromSubtasks($subtasks, $overtime = false)
    {
        $out = 0.0;
        foreach ($subtasks as $subtask) {
            if ($overtime) {
                $tmp = (float) $subtask['time_spent'] - (float) $subtask['time_estimated'];
            } else {
                $tmp = (float) $subtask['time_estimated'] - (float) $subtask['time_spent'];
            }

            // only add time as spending, as long as the spent time of th subtask
            // does not exceed the estimated time, so that in total
            // the remaining time will always represent the actual estimated
            // time throughout all subtasks
            if ($tmp > 0) {
                $out += $tmp;
            }
        }
        return $out;
    }

    /**
     * Wrapper for "remaining time", which will use the
     * getRemainingOrOvertimeForTask method.
     *
     * @param  array  $task
     * @param  boolean $considerSubtasks
     * @return float
     */
    public function getRemainingTimeForTask($task, $considerSubtasks = true)
    {
        return $this->getRemainingOrOvertimeForTask($task, $considerSubtasks, false);
    }

    /**
     * Wrapper for "overtime", which will use the
     * getRemainingOrOvertimeForTask method.
     *
     * @param  array  $task
     * @param  boolean $considerSubtasks
     * @return float
     */
    public function getOvertimeForTask($task, $considerSubtasks = true)
    {
        return $this->getRemainingOrOvertimeForTask($task, $considerSubtasks, true);
    }
}
