<?php

class MailerController extends BackstageController
{
    public function indexAction()
    {
        $mailer = new Mailer();
        $this->view->mailer_list = $mailer->getAll(); // 单独通道属性整合行属性
    }

    public function settingAction($driver_name)
    {
        if ($this->request->isPost()) {
            $this->begin();
            $post = $this->request->getPost();
            Setting::setConf($driver_name, $post['setting']);
            $this->end(true);
        }

        $mailer = new Mailer();
        $this->view->setting = $mailer->getObj($driver_name)->setting();
        $this->view->mailer = $mailer->getByName($driver_name);
    }
}