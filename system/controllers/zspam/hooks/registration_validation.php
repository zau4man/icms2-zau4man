<?php

class onZspamRegistrationValidation extends cmsAction {

    public function run($data){

        list($errors,$user) = $data;
        
        //если проверка до
        if($this->options['type'] == 'validation'){
        
            //вернут true, если спамер
            if($this->checkUser($user)){
                cmsUser::addSessionMessage(LANG_ZSPAM_USERS_MESSAGE, 'error');
                $errors['email'] = LANG_ZSPAM_USERS_EMAIL;
            }
        
        }
        
        return array($errors,$user);

    }

}
