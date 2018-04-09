<?php

class MailerController extends BackstageController
{
    public function indexAction()
    {
        $mailer = new Mailer();
        $this->view->mailer_list = $mailer->getAll(); // 单独通道属性整合行属性
    }

    public function settingAction($mailer_name)
    {
        if ($this->request->isPost()) {
            $this->begin();
            $post = $this->request->getPost();
            Setting::setConf($mailer_name, $post['setting']);
            $this->end(true);
        }

        $mailer = new Mailer();
        $this->view->setting = $mailer->getObj($mailer_name)->setting();
        $this->view->mailer = $mailer->getByName($mailer_name);
    }
}