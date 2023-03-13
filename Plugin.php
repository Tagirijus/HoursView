<?php

namespace Kanboard\Plugin\TagiHoursView;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
// use Kanboard\Plugin\TagiHoursView\AgeHelper;  // Helper Class and Filename should be exact
// use Kanboard\Helper;  // Add core Helper for using forms etc. inside external templates

class Plugin extends Base
{
    public function initialize()
    {
        // CSS - Asset Hook
        $this->hook->on('template:layout:css', array('template' => 'plugins/TagiHoursView/Assets/css/tagi-hours-view.min.css'));

        // Template Override
        // $this->template->setTemplateOverride('board/table_column', 'TagiHoursView:board/table_column');

        // Views - Template Hook
        $this->template->hook->attach(
            'template:project:header:before', 'TagiHoursView:board/project_head_hours', [
                'tagiTimes' => function ($projectId) { return $this->getTimesByProjectId($projectId); }
            ]
        );
        $this->template->hook->attach(
            'template:board:column:header', 'TagiHoursView:board/column_hours', [
                'tagiTimes' => function ($column) {
                    return $this->getTimesForColumn($column);
                }
            ]
        );
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    /**
     * Get the estimated and spent times in the columns for
     * all tasks with a given project id. The method also
     * returns the data categorized into the columns, which
     * are either visible on the dashboard and not (additionally).
     *
     * Array output:
     *
     * [
     *     'all' => [
     *         '_total' => [
     *             'estimated' => 8,
     *             'spent' => 6.5
     *         ],
     *         'column a' => [
     *             'estimated' => 2,
     *             'spent' => 1
     *         ],
     *         'column b' => [
     *             'estimated' => 5,
     *             'spent' => 4.5
     *         ],
     *         'column not-dashboard' => [
     *             'estimated' => 1,
     *             'spent' => 1
     *         ]
     *     ],
     *     'dashboard' => [
     *         '_total' => [
     *             'estimated' => 7,
     *             'spent' => 5.5
     *         ],
     *         'column a' => [
     *             'estimated' => 2,
     *             'spent' => 1
     *         ],
     *         'column b' => [
     *             'estimated' => 5,
     *             'spent' => 4.5
     *         ]
     *     ]
     * ]
     *
     * @param  integer $projectId
     * @return array
     */
    public function getTimesByProjectId($projectId)
    {
        $columns = $this->getColumnsByProjectId($projectId);
        $tasks = $this->getTasksByProjectId($projectId);

        $all = [
            '_total' => [
                'estimated' => 0,
                'spent' => 0
            ]
        ];
        $dashboard = $all;

        foreach ($tasks as $task) {
            if (isset($columns[$task['column_id']])) {
                $col_name = $columns[$task['column_id']]['title'];
            } else {
                continue;
            }

            if (!isset($all[$col_name])) {
                $all[$col_name] = ['estimated' => 0, 'spent' => 0];
                if ($columns[$task['column_id']]['hide_in_dashboard'] != 1) {
                    $dashboard[$col_name] = ['estimated' => 0, 'spent' => 0];
                }
            }

            $all[$col_name]['estimated'] += $task['time_estimated'];
            $all[$col_name]['spent'] += $task['time_spent'];
            $all['_total']['estimated'] += $task['time_estimated'];
            $all['_total']['spent'] += $task['time_spent'];

            if ($columns[$task['column_id']]['hide_in_dashboard'] != 1) {
                $dashboard[$col_name]['estimated'] += $task['time_estimated'];
                $dashboard[$col_name]['spent'] += $task['time_spent'];
                $dashboard['_total']['estimated'] += $task['time_estimated'];
                $dashboard['_total']['spent'] += $task['time_spent'];
            }
        }

        return [
            'all' => $all,
            'dashboard' => $dashboard
        ];
    }

    /**
     * Get an array with the calculated times for
     * the given column array.
     *
     * Array output:
     *
     * [
     *     'estimated' => 2,
     *     'spent' => 1
     * ]
     *
     * @param  array $column
     * @return array
     */
    public function getTimesForColumn($column)
    {
        $out = ['estimated' => 0, 'spent' => 0];
        if (isset($column['tasks'])) {
            foreach ($column['tasks'] as $task) {
                $out['estimated'] += $task['time_estimated'];
                $out['spent'] += $task['time_spent'];
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
    private function getColumnsByProjectId($projectId)
    {
        $out = [];
        $columns = $this->container['columnModel']->getAll($projectId);
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
    private function getTasksByProjectId($projectId)
    {
        return $this->container['taskFinderModel']->getAll($projectId);
    }

    public function getPluginName()
    {
        // Plugin Name MUST be identical to namespace for Plugin Directory to detect updated versions
        // Do not translate the plugin name here
        return 'TagiHoursView';
    }

    public function getPluginDescription()
    {
        return t('Show total hours in different places in the Kanboard app');
    }

    public function getPluginAuthor()
    {
        return 'Tagirijus';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getCompatibleVersion()
    {
        // Examples:
        // >=1.0.37
        // <1.0.37
        // <=1.0.37
        return '>=1.2.27';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/Tagirijus/kanboard-TagiHoursView';
    }
}
