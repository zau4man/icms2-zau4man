<?php

class backendZspam extends cmsBackend {

    public $useDefaultOptionsAction = true;
    protected $useOptions = true;

    public function actionIndex() {

        $this->redirectToAction('options');
    }

    public function getBackendMenu() {

        return array(
            array(
                'title' => LANG_OPTIONS,
                'url' => href_to($this->root_url, 'options')
            ),
            array(
                'title' => 'Спамеры',
                'url' => href_to($this->root_url, 'spamers')
            ),
            array(
                'title' => 'Проверка пользователей',
                'url' => href_to($this->root_url, 'check')
            )
        );
    }

    public function updateOptions($user_id) {
        $options = $this->options;
        $options['last'] = $user_id;
        $this->model->filterEqual('name', 'zspam')->updateFiltered('controllers', array(
            'options' => $options
        ));
    }

}
