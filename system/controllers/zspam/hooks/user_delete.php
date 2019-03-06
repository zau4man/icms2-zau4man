<?php

class onZspamUserDelete extends cmsAction {

    public function run($user){

        $this->model->filterEqual('user_id',$user['id'])->deleteFiltered('zspamers');

        return $user;

    }

}
