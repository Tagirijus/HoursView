<?php

namespace Kanboard\Plugin\HoursView\Controller;




class HoursViewController extends \Kanboard\Controller\PluginController
{
    /**
     * Settins page for the HoursView plugin.
     *
     * @return HTML response
     */
    public function show()
    {
        $this->response->html($this->helper->layout->config('HoursView:config/hoursview_config', [
            'title' => t('HoursView') . ' &gt; ' . t('Settings'),
            'level_1_columns' => $this->configModel->get('hoursview_level_1_columns', ''),
            'level_2_columns' => $this->configModel->get('hoursview_level_2_columns', ''),
            'level_3_columns' => $this->configModel->get('hoursview_level_3_columns', ''),
            'level_4_columns' => $this->configModel->get('hoursview_level_4_columns', ''),
            'level_1_caption' => $this->configModel->get('hoursview_level_1_caption', ''),
            'level_2_caption' => $this->configModel->get('hoursview_level_2_caption', ''),
            'level_3_caption' => $this->configModel->get('hoursview_level_3_caption', ''),
            'level_4_caption' => $this->configModel->get('hoursview_level_4_caption', ''),
            'progressbar_enable' => $this->configModel->get('hoursview_progressbar_enable', 1),
            'progressbar_opacity' => $this->configModel->get('hoursview_progressbar_opacity', 1),
            'progressbar_0_opacity' => $this->configModel->get('hoursview_progressbar_0_opacity', 0.15)
        ]));
    }

    /**
     * Save the setting for HoursView.
     */
    public function saveConfig()
    {
        $form = $this->request->getValues();

        $values = [
            'hoursview_level_1_columns' => $form['level_1_columns'],
            'hoursview_level_2_columns' => $form['level_2_columns'],
            'hoursview_level_3_columns' => $form['level_3_columns'],
            'hoursview_level_4_columns' => $form['level_4_columns'],
            'hoursview_level_1_caption' => $form['level_1_caption'],
            'hoursview_level_2_caption' => $form['level_2_caption'],
            'hoursview_level_3_caption' => $form['level_3_caption'],
            'hoursview_level_4_caption' => $form['level_4_caption'],
            'hoursview_progressbar_enable' => is_null($form['progressbar_enable']) ? 0 : 1,
            'hoursview_progressbar_opacity' => $form['progressbar_opacity'],
            'hoursview_progressbar_0_opacity' => $form['progressbar_0_opacity']
        ];

        $this->languageModel->loadCurrentLanguage();

        if ($this->configModel->save($values)) {
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }

        return $this->response->redirect($this->helper->url->to('HoursViewController', 'show', ['plugin' => 'HoursView']), true);
    }
}