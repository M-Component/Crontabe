<?php

class VersionController extends BackstageController{
    var $model_name = 'Version';
    var $index_title = '版本控制管理';
    public function settingAction(){
        if($this ->request ->isPost()){
            $this->begin();
            $post = $this ->request ->getPost();
            Setting::setConf('version' , $post['setting']);
            $this ->end(true);
        }
        $this ->view->setting = Setting::getConf('version');
    }
}