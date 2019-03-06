<?php

class onZspamUserRegistered extends cmsAction {

    public function run($user){

        //если проверка после
        if($this->options['type'] == 'registered'){
        
            //вернут true, если спамер
            if($this->checkUser($user)){
                $this->model->update('{users}', $user['id'], array(
                    'is_locked' => 1
                ));
                $this->model->insert('zspamers', array(
                    'user_id' => $user['id']
                ));
                $this->redirectToHome();
            }
        
        }
        
        
        return $user;

    }

}
