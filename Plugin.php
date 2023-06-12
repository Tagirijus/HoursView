<?php

namespace Kanboard\Plugin\HoursView;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;


class Plugin extends Base
{
    public function initialize()
    {
        // Helper
        $this->helper->register('hoursViewHelper', '\Kanboard\Plugin\HoursView\Helper\HoursViewHelper');

        // CSS - Asset Hook
        $this->hook->on('template:layout:css', array('template' => 'plugins/HoursView/Assets/css/hours-view.min.css'));

        // JS - Asset Hook
        $this->hook->on('template:layout:js', array('template' => 'plugins/HoursView/Assets/js/subtask-toggle-refresh.min.js'));

        // Template Override
        $this->template->setTemplateOverride('search/results', 'HoursView:search/results');
        $this->template->setTemplateOverride('task/details', 'HoursView:task/details');
        $this->template->setTemplateOverride('board/task_footer', 'HoursView:board/task_footer');
        $this->template->setTemplateOverride('task_list/task_icons', 'HoursView:task_list/task_icons');
        $this->template->setTemplateOverride('subtask/timer', 'HoursView:subtask/timer');
        $this->template->setTemplateOverride('task_internal_link/table', 'HoursView:task_internal_link/table');

        // Views - Template Hook
        $this->template->hook->attach(
            'template:project:header:before', 'HoursView:board/project_head_hours', [
                'tagiTimes' => function ($projectId) {
                    return $this->helper->hoursViewHelper->getTimesByProjectId($projectId);
                }
            ]
        );
        $this->template->hook->attach(
            'template:board:column:header', 'HoursView:board/column_hours', [
                'tagiTimes' => function ($column) {
                    return $this->helper->hoursViewHelper->getTimesForColumn($column);
                }
            ]
        );
        $this->template->hook->attach(
            'template:dashboard:show:after-filter-box', 'HoursView:dashboard/project_times_summary_all', [
                'tagiTimes' => function ($userId) {
                    return $this->helper->hoursViewHelper->getTimesByUserId($userId);
                }
            ]
        );
        $this->template->hook->attach(
            'template:dashboard:project:after-title', 'HoursView:dashboard/project_times_summary_single', [
                'tagiTimes' => function ($projectId) {
                    return $this->helper->hoursViewHelper->getTimesByProjectId($projectId);
                }
            ]
        );
        $this->template->hook->attach(
            'template:config:sidebar', 'HoursView:config/hoursview_config_sidebar');

        // Extra Page - Routes
        $this->route->addRoute('/hoursview/config', 'HoursViewController', 'show', 'HoursView');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'HoursView';
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
        return '1.18.0';
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
        return 'https://github.com/Tagirijus/HoursView';
    }
}
