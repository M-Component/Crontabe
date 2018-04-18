<?php

class  SmsController extends BackstageController
{
    public function indexAction()
    {
        $sms = new Sms();
        $this->view->sms_list = $sms->getAll();
    }

    public function settingAction($driver_name)
    {
        if ($this->request->isPost()) {
            $this->begin();
            $post = $this->request->getPost();
            Setting::setConf($driver_name, $post['setting']);
            $this->end(true);
        }
        $sms = new Sms();
        $this->view->setting = $sms->getObj($driver_name)->setting();
        $this->view->sms = $sms->getByName($driver_name);
    }
}