<?php

class onMessagetonewuserUserRegistered extends cmsAction {

    public function run($user) {

        $sender_id = $this->options['user_id'];
        $message = $this->options['message'];

        $this->controller_messages->addRecipient($user['id'])->setSender($sender_id);
        $this->controller_messages->sendMessage($message);

        return $user;
    }

}
