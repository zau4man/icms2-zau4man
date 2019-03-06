<?php

class formZspamOptions extends cmsForm {

    public function init() {

        return array(
            array(
                'type' => 'fieldset',
                'childs' => array(
                    new fieldList('type', array(
                        'title' => 'Момент проверки',
                        'hint' => 'В первом случае пользователь не будет зарегистрирован<br>Во втором будет зарегистрирован и сразу заблокирован',
                        'items' => array(
                            'validation' => 'До регистрации',
                            'registered' => 'После регистрации'
                        )
                            ))
                ))
        );
    }

}
