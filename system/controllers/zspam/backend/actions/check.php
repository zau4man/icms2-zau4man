<?php

class actionZspamCheck extends cmsAction{
    
    public function run($type = false) {
        
        $last = empty($this->options['last']) ? false : $this->options['last'];
        
        if($this->request->isAjax()){
            
            sleep(1);
            //стартанем с нуля, если надо
            if($type == 'new'){
                $last = false;
            }
            //тут сперва получим пользователя
            if($last){
                $this->model->filterLt('id',$last);
            }
            $user = $this->model->orderBy('id','desc')->getItem('{users}');
            
            if(!$user){
                //тут обновление опций
                $this->updateOptions(false);
                
                return $this->cms_template->renderJSON(array(
                    'success' => false,
                    'text'  => 'Проверка завершена. Проверены все пользователи'
                ));
            }
            
            //тут проверка пользователя
            $check = cmsCore::getController('zspam')->checkUserFromAdmin($user);
            if($check){
                if(!empty($check['error'])){
                    return $this->cms_template->renderJSON(array(
                        'success' => false,
                        'text'  => "<pre>" . print_r($check['errortext'],true) . "</pre>"
                    ));
                }
                //значит пришло true, запомним спамера
                $this->model->insertOrUpdate('zspamers', array(
                    'user_id' => $user['id']
                ),array(
                    'user_id' => $user['id']
                ));
            }            
            
            //тут обновление опций
            $this->updateOptions($user['id']);
            
            return $this->cms_template->renderJSON(array(
                'success' => true,
                'text'  => "<p>Последний проверенный пользователь c id {$user['id']}: <a target='_blank' href='" . href_to('admin', 'users', array('edit', $user['id'])) . "'>{$user['nickname']}</a></p>"
            ));
            
        }
        
        $lastuser = false;
        if($last){
            $lastuser = $this->model->getItemById('{users}',$last);
        }
        $all = $this->model->getCount('{users}');
        
        return $this->cms_template->render('backend/check',array(
            'last' => $last,
            'all' => $all,
            'lastuser' => $lastuser
        ));
        
    }
    
}
