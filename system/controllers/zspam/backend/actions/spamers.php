<?php

class actionZspamSpamers extends cmsAction {

    public function run() {

        if ($this->request->isAjax()) {

            $grid = $this->loadDataGrid('spamers', false, 'spamers');
            $filter = array();
            $filter_str = $this->request->get('filter', '');
            $filter_str = cmsUser::getUPSActual('spamers', $filter_str);
            if ($filter_str) {
                parse_str($filter_str, $filter);
                $this->model->applyGridFilter($grid, $filter);
            }
            $total = $this->model->getCount('zspamers');
            $perpage = isset($filter['perpage']) ? $filter['perpage'] : admin::perpage;
            $pages = ceil($total / $perpage);
            $this->model->setPerPage($perpage);
            $spamers = $this->model->joinUser('user_id',array(
                'nickname' => 'nickname',
                'email' => 'email'
            ))->get('zspamers');            
            $template = cmsTemplate::getInstance();
            $template->renderGridRowsJSON($grid, $spamers, $total, $pages);
            $this->halt();
        }

        $grid = $this->loadDataGrid('spamers');
        
        return $this->cms_template->render('backend/spamers', array(
                    'grid' => $grid
        ));
    }

}
