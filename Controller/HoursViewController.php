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
            'level_1' => $this->configModel->get('hoursview_level_1', ''),
            'level_2' => $this->configModel->get('hoursview_level_2', ''),
            'level_3' => $this->configModel->get('hoursview_level_3', ''),
            'level_4' => $this->configModel->get('hoursview_level_4', '')
        ]));
    }

    /**
     * Save the setting for HoursView.
     */
    public function saveConfig()
    {
        $form = $this->request->getValues();

        $values = [
            'hoursview_level_1' => $form['level_1'],
            'hoursview_level_2' => $form['level_2'],
            'hoursview_level_3' => $form['level_3'],
            'hoursview_level_4' => $form['level_4']
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