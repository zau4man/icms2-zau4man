<?php

class formMessagetonewuserOptions extends cmsForm {

    public function init() {

        return array(
            array(
                'type' => 'fieldset',
                'childs' => array(
                    new fieldString('user_id', array(
                        'title' => LANG_MESSAGETONEWUSER_OPTIONS_USERID,
                        'default' => 1
                            )),
                    new fieldHtml('message', array(
                        'title' => LANG_MESSAGETONEWUSER_OPTIONS_MESSAGE,
                        'default' => 'Здравствуйте, благодарим за регистрацию и проявленный интерес. И т.д. и т.п.',
                        'options' => array('editor' => cmsConfig::get('default_editor'))
                            ))
                )
            )
        );
    }

}
