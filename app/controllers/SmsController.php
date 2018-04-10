<?php

class  SmsController extends BackstageController
{
    public function indexAction()
    {
        $sms = new Sms();
        $this->view->sms_list = $sms->getAll();
    }

    public function settingAction($sms_name)
    {
        if ($this->request->isPost()) {
            $this->begin();
            $post = $this->request->getPost();
            Setting::setConf($sms_name, $post['setting']);
            $this->end(true);
        }
        $sms = new Sms();
        $this->view->setting = $sms->getObj($sms_name)->setting();
        $this->view->sms = $sms->getByName($sms_name);
    }
}