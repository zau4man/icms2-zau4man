<?php

class onZlazyloadHtmlFilter extends cmsAction {

    public function run($data) {
        
        $template = cmsTemplate::getInstance();
        $template->addJS($template->getTplFilePath('js/zlazyload.js',false));

        $result = array();
        preg_match_all('!<img(.*?)>!', $data, $result, PREG_SET_ORDER);

        foreach ($result as $item) {
            $data = str_replace($item[0], "<img" . $item[1] . " srcset='/templates/default/images/spacer.gif'>", $data);
        }

        return $data;
    }

}
