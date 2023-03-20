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

        // Template Override
        $this->template->setTemplateOverride('search/results', 'HoursView:search/results');

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
        // Plugin Name MUST be identical to namespace for Plugin Directory to detect updated versions
        // Do not translate the plugin name here
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
        return '1.5.0';
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
