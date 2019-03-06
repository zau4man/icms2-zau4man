<?php

//https://www.stopforumspam.com/usage

class zspam extends cmsFrontend {

    protected $useOptions = true;

    //при проверке с fronta
    public function checkUser($user) {

        $link = $this->getUrl($user);

        $check = $this->getCurl($link);

        //раз массив, значит curl вернул ошибку
        if (is_array($check)) {

            $this->reportError($user, $check['text']);

            return false;
        }

        $answer = unserialize($check);

        /*
          //Array
          //(
          //    [success] => 1
          //    [username] => Array
          //        (
          //            [frequency] => 0
          //            [appears] => 0
          //        )
          //    [email] => Array
          //        (
          //            [lastseen] => 2019-03-01 16:40:21
          //            [frequency] => 255
          //            [appears] => 1
          //            [confidence] => 99.95
          //        )
          //)
         */

        if (empty($answer['success'])) {

            $this->reportError($user, LANG_ZSPAM_ADMIN_ERROR_NOTINFO);

            return false;
        }

        if ($answer['email']['appears'] == 1) {

            $this->reportSpam($user);

            return true;
        }

        //раз дошли до сюда, значит не спамер
        return false;
    }
    
    //при проверке с админки
    public function checkUserFromAdmin($user) {

        $link = $this->getUrl($user);

        $check = $this->getCurl($link);

        //раз массив, значит curl вернул ошибку
        if (is_array($check)) {
            return array(
                'error' => true,
                'errortext' => $check['text']
                    );
        }

        $answer = unserialize($check);

        if (empty($answer['success'])) {
            return array(
                'error' => true,
                'errortext' => $answer
                    );
        }

        if ($answer['email']['appears'] == 1) {
            return true;
        }

        //раз дошли до сюда, значит не спамер
        return false;
    }

    //curl
    public function getCurl($link) {
        $ch = curl_init(); //открытие сеанса
        curl_setopt($ch, CURLOPT_URL, $link); //какой урл откроем
        curl_setopt($ch, CURLOPT_HEADER, 0); //откажемся принимать заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //укажем, что надо вернуть содержимое
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 GTB6");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch); //попросим курл выполнить запрос
        if ($result === false) {
            return array('text' => 'Ошибка curl: ' . curl_error($ch));
        }

        return $result;
    }

    public function reportError($user, $text = false) {

        $messenger = cmsCore::getController('messages');
        $messenger->addRecipient(1);
        $text = "При проверке пользователя с nickname " . html_clean($user['nickname']) . " и e-mail " . html_clean($user['email']) . " была получена ошибка: " . $text;

        $message = array(
            'content' => $text
        );
        $messenger->sendNoticePM($message);
    }

    public function reportSpam($user) {
        $messenger = cmsCore::getController('messages');
        $messenger->addRecipient(1);
        $text = "Попытка регистрации с nickname " . html_clean($user['nickname']) . " и e-mail " . html_clean($user['email']) . " была заблокирована. Пользователь по данным www.stopforumspam.com является спамером.";

        $message = array(
            'content' => $text
        );
        $messenger->sendNoticePM($message);
    }
    
    public function getUrl($user) {
        
        $link = "http://api.stopforumspam.org/api?serial&";
        $data = array(
            'email' => $user['email']
        );
        return $link . http_build_query($data, '', '&');
        
    }

}
