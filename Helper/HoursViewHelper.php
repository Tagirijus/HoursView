<?php

namespace Kanboard\Plugin\HoursView\Helper;

use Kanboard\Core\Base;
use Kanboard\Model\TaskModel;
use Kanboard\Model\ProjectModel;
use Kanboard\Model\SubtaskModel;


class HoursViewHelper extends Base
{
    /**
     * Get the estimated and spent times in the columns for
     * the total (all) and the levels (level_1, level_2, ...).
     *
     * New since v1.2.0: remaining
     * New since v1.13.0: overtime
     *
     *
     * Array output:
     *
     * [
     *     'all' => [
     *         '_total' => [
     *             'estimated' => 8,
     *             'spent' => 6.5,
     *             'remaining' => 1.5,
     *             'overtime' => 0
     *         ],
     *         'column b' => [
     *             'estimated' => 5,
     *             'spent' => 4.5,
     *             'remaining' => 0.5,
     *             'overtime' => 0
     *         ]
     *     ],
     *     'level_1' => [
     *         '_total' => [
     *             'estimated' => 7,
     *             'spent' => 5.5,
     *             'remaining' => 1.5,
     *             'overtime' => 0
     *         ],
     *         'column a' => [
     *             'estimated' => 2,
     *             'spent' => 3,
     *             'remaining' => 0,
     *             'overtime' => 1
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
                'remaining' => 0,
                'overtime' => 0
            ]
        ];
        $level_1 = $all;
        $level_2 = $all;
        $level_3 = $all;
        $level_4 = $all;
        $col_name = 'null';

        foreach ($tasks as $task) {

            // get column name and swimlane
            $col_name = $task['column_name'];
            $swim_name = $task['swimlane_name'];

            // set new column key in the time calc arrays
            $this->setTimeCalcKey($all, $col_name);
            $this->setTimeCalcKey($level_1, $col_name);
            $this->setTimeCalcKey($level_2, $col_name);
            $this->setTimeCalcKey($level_3, $col_name);
            $this->setTimeCalcKey($level_4, $col_name);

            // all: column times
            $all[$col_name]['estimated'] += $task['time_estimated'];
            $all[$col_name]['spent'] += $task['time_spent'];
            $all[$col_name]['remaining'] += $this->getRemainingTimeForTask($task);
            $all[$col_name]['overtime'] += $this->getOvertimeForTask($task);

            // all: total times
            $all['_total']['estimated'] += $task['time_estimated'];
            $all['_total']['spent'] += $task['time_spent'];
            $all['_total']['remaining'] += $this->getRemainingTimeForTask($task);
            $all['_total']['overtime'] += $this->getOvertimeForTask($task);
            $this->modifyHasTimes($all);


            // level times
            $this->addTimesForLevel($level_1, 'level_1', $levels_columns, $col_name, $swim_name, $task);
            $this->addTimesForLevel($level_2, 'level_2', $levels_columns, $col_name, $swim_name, $task);
            $this->addTimesForLevel($level_3, 'level_3', $levels_columns, $col_name, $swim_name, $task);
            $this->addTimesForLevel($level_4, 'level_4', $levels_columns, $col_name, $swim_name, $task);
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
            $arr[$col_name] = ['estimated' => 0, 'spent' => 0, 'remaining' => 0, 'overtime' => 0];
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
     * @param string $swim_name
     * @param array $task
     */
    protected function addTimesForLevel(&$level, $level_key, $levels, $col_name, $swim_name, $task)
    {
        // check if the actual column name and swimlane name
        // are wanted for this level
        $exists = false;
        if (array_key_exists($level_key, $levels)) {
            $config = $levels[$level_key];
            foreach ($config as $col_swim) {
                //            1     2     3
                preg_match('/(.*)\[(.*)\](.*)/', $col_swim, $re);

                // swimlane in bracktes given
                if ($re) {
                    // column check
                    if (trim($re[1]) == $col_name || trim($re[3]) == $col_name) {
                        // and swimlane check
                        if (trim($re[2]) == $swim_name) {
                            $exists = true;
                        }
                    }

                // no swimlane in brackets given
                } else {
                    // column check
                    if (trim($col_swim) == $col_name) {
                        $exists = true;
                    }
                }
            }
        }

        if ($exists) {
            // dashbord: column times
            $level[$col_name]['estimated'] += $task['time_estimated'];
            $level[$col_name]['spent'] += $task['time_spent'];
            $level[$col_name]['remaining'] += $this->getRemainingTimeForTask($task);
            $level[$col_name]['overtime'] += $this->getOvertimeForTask($task);

            // level: total times
            $level['_total']['estimated'] += $task['time_estimated'];
            $level['_total']['spent'] += $task['time_spent'];
            $level['_total']['remaining'] += $this->getRemainingTimeForTask($task);
            $level['_total']['overtime'] += $this->getOvertimeForTask($task);
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
     *     'remaining' => 1,
     *     'overtime' => 0
     * ]
     *
     * @param  array $column
     * @return array
     */
    public function getTimesForColumn($column)
    {
        $out = ['estimated' => 0, 'spent' => 0, 'remaining' => 0, 'overtime' => 0];
        if (isset($column['tasks'])) {
            foreach ($column['tasks'] as $task) {
                $out['estimated'] += $task['time_estimated'];
                $out['spent'] += $task['time_spent'];
                $out['remaining'] += $this->getRemainingTimeForTask($task);
                $out['overtime'] += $this->getOvertimeForTask($task);
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
            'level_1' => $this->configModel->get('hoursview_level_1_caption', ''),
            'level_2' => $this->configModel->get('hoursview_level_2_caption', ''),
            'level_3' => $this->configModel->get('hoursview_level_3_caption', ''),
            'level_4' => $this->configModel->get('hoursview_level_4_caption', ''),
            'all' => $this->configModel->get('hoursview_all_caption', '')
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
     * Get configuration for plugin as array.
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'title' => t('HoursView') . ' &gt; ' . t('Settings'),
            'level_1_columns' => $this->configModel->get('hoursview_level_1_columns', ''),
            'level_2_columns' => $this->configModel->get('hoursview_level_2_columns', ''),
            'level_3_columns' => $this->configModel->get('hoursview_level_3_columns', ''),
            'level_4_columns' => $this->configModel->get('hoursview_level_4_columns', ''),
            'level_1_caption' => $this->configModel->get('hoursview_level_1_caption', ''),
            'level_2_caption' => $this->configModel->get('hoursview_level_2_caption', ''),
            'level_3_caption' => $this->configModel->get('hoursview_level_3_caption', ''),
            'level_4_caption' => $this->configModel->get('hoursview_level_4_caption', ''),
            'all_caption' => $this->configModel->get('hoursview_all_caption', ''),
            'progressbar_enabled' => $this->configModel->get('hoursview_progressbar_enabled', 1),
            'progressbar_opacity' => $this->configModel->get('hoursview_progressbar_opacity', 1),
            'progressbar_0_opacity' => $this->configModel->get('hoursview_progressbar_0_opacity', 0.15),
            'progress_home_project_level' => $this->configModel->get('hoursview_progress_home_project_level', 'all'),
        ];
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

    /**
     * Calculate the percent with the given task.
     * Use the times for this.
     *
     * Future idea:
     *    Maybe use the amount of subtasks, if noe
     *    estimstd times exist at all.
     *
     * @param  array $task
     * @param  bool $overtime
     * @return integer
     */
    public function getPercentForTask($task, $overtime = false)
    {
        $out = 0;

        // calculate percentage from given times
        if (isset($task['time_estimated']) && isset($task['time_spent'])) {
            if ($task['time_estimated'] != 0) {
                $out = round($task['time_spent'] / $task['time_estimated'] * 100, 0);
            } else {
                $out = 100;
            }
        }

        // consider overtime
        if ($overtime) {
            if ($out > 100) {
                $out = $out - 100;
            } else {
                $out = 0;
            }
        }

        return $out;
    }

    /**
     * Get percentage for a task according to its
     * spent time and estimated time (or in the future
     * maybe depending on the subtasks) and render
     * it as a string with percentage symbol.
     *
     * Also there is the option to add additional info like
     * the overtime.
     *
     * @param  array $task
     * @param  string $symbol
     * @param  bool $overtime
     * @return string
     */
    public function getPercentForTaskAsString($task, $symbol = '%', $overtime = false)
    {
        $percent_over = $this->getPercentForTask($task, true);

        if ($overtime && $percent_over > 0) {
            $out = '100' . $symbol . ' (+' . $this->getPercentForTask($task, true) . $symbol . ')';
        } else {
            $out = $this->getPercentForTask($task, false) . $symbol;
        }

        return $out;
    }

    /**
     * According to the wanted levels from the config,
     * sum up all the respecting time values for e.g.
     * the "project_times_summary_single.php".
     *
     * @param  array $times
     * @return array
     */
    public function prepareProjectTimesWithConfig($times)
    {
        $out = [
            'estimated' => 0,
            'spent' => 0,
            'remaining' => 0,
            'overtime' => 0,
        ];

        // Get levels from config
        $levels = explode(',', $this->configModel->get('hoursview_progress_home_project_level', 'all'));

        // iter through levels, while checking if they exist in the $times as key
        foreach ($levels as $level) {
            $level_trimmed = trim($level);
            if (array_key_exists($level, $times)) {
                $out['estimated'] += $times[$level]['_total']['estimated'];
                $out['spent'] += $times[$level]['_total']['spent'];
                $out['remaining'] += $times[$level]['_total']['remaining'];
                $out['overtime'] += $times[$level]['_total']['overtime'];
            }
        }

        return $out;
    }
}
