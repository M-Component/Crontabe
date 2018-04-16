<?php

// 消息推送
class MsgPushController extends BackstageController{
    public function indexAction(){
        $msg_push = new MsgPush();
        $this->view->items = $msg_push->getAll();
    }

    public function settingAction($drive_name)
    {
        if ($this->request->isPost()) {
            $this->begin();
            $post = $this->request->getPost();
            Setting::setConf($drive_name, $post['setting']);
            $this->end(true);
        }
        $msg_push = new MsgPush();
        $this->view->setting = $msg_push->getObj($drive_name)->setting();
        $this->view->items = $msg_push->getByName($drive_name);
    }
}