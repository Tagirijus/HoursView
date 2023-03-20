<?php

namespace Kanboard\Plugin\HoursView\Helper;

use Kanboard\Core\Base;
use Kanboard\Model\TaskModel;
use Kanboard\Model\ProjectModel;
use Kanboard\Model\ColumnModel;
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
        $levels = [
            'level_1' => explode(',', $this->configModel->get('hoursview_level_1', '')),
            'level_2' => explode(',', $this->configModel->get('hoursview_level_2', '')),
            'level_3' => explode(',', $this->configModel->get('hoursview_level_3', '')),
            'level_4' => explode(',', $this->configModel->get('hoursview_level_4', ''))
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
            $this->addTimesForLevel($level_1, 'level_1', $levels, $col_name, $task);
            $this->addTimesForLevel($level_2, 'level_2', $levels, $col_name, $task);
            $this->addTimesForLevel($level_3, 'level_3', $levels, $col_name, $task);
            $this->addTimesForLevel($level_4, 'level_4', $levels, $col_name, $task);
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
        return $this->taskFinderModel->getExtendedQuery()
            ->eq(TaskModel::TABLE.'.project_id', $projectId)
            ->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_OPEN)
            ->asc(TaskModel::TABLE.'.id')
            ->findAll();
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
        // $out = ['estimated' => 0, 'spent' => 0, 'remaining' => 0];

        // $userTasks = $this->taskFinderModel->getUserQuery($userId)->findAll();
        // foreach ($userTasks as $task) {
        //     $this->logger->info(json_encode($task));
        //     $out['estimated'] += $task['time_estimated'];
        //     $out['spent'] += $task['time_spent'];
        //     $out['remaining'] += $this->calculateRemaining($task['time_estimated'], $task['time_spent']);
        // }

        // return $out;

        // $tasks = $this->taskFinderModel->getUserQuery($userId)->findAll();
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
}
