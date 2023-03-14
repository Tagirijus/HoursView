<?php

namespace Kanboard\Plugin\TagiHoursView;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
// use Kanboard\Plugin\TagiHoursView\Helper;  // Helper Class and Filename should be exact
// use Kanboard\Helper;  // Add core Helper for using forms etc. inside external templates

class Plugin extends Base
{
    public function initialize()
    {
        // Helper
        $this->helper->register('tagiHoursViewHelper', '\Kanboard\Plugin\TagiHoursView\Helper\TagiHoursViewHelper');

        // CSS - Asset Hook
        $this->hook->on('template:layout:css', array('template' => 'plugins/TagiHoursView/Assets/css/tagi-hours-view.min.css'));

        // Template Override
        $this->template->setTemplateOverride('search/results', 'TagiHoursView:search/results');

        // Views - Template Hook
        $this->template->hook->attach(
            'template:project:header:before', 'TagiHoursView:board/project_head_hours', [
                'tagiTimes' => function ($projectId) {
                    return $this->helper->tagiHoursViewHelper->getTimesByProjectId($projectId);
                }
            ]
        );
        $this->template->hook->attach(
            'template:board:column:header', 'TagiHoursView:board/column_hours', [
                'tagiTimes' => function ($column) {
                    return $this->helper->tagiHoursViewHelper->getTimesForColumn($column);
                }
            ]
        );
        $this->template->hook->attach(
            'template:dashboard:show:after-filter-box', 'TagiHoursView:dashboard/project_times_summary_all', [
                'tagiTimes' => function ($userId) {
                    return $this->helper->tagiHoursViewHelper->getTimesByUserId($userId);
                }
            ]
        );
        $this->template->hook->attach(
            'template:dashboard:project:after-title', 'TagiHoursView:dashboard/project_times_summary_single', [
                'tagiTimes' => function ($projectId) {
                    return $this->helper->tagiHoursViewHelper->getTimesByProjectId($projectId);
                }
            ]
        );
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
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
        return '1.3.0';
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
