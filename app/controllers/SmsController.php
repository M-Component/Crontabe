<?php

class  SmsController extends BackstageController
{
    public function indexAction()
    {
        /*$new = new \Component\Sms\ShortLink();
        $data = [
            'content'=>"您有3条商品监控告警，请及时查看 ".$new->Post("https://www.dongchaguan.cn/mobile_monitor/warning"),
            'target'=>[
                [
                    'mobile'=>15527543053,
                    'member_id'=>1,
                ],
                [
                    'mobile'=>15821203194,
                    'member_id'=>1,
                ]
            ]
        ];

        $sms = new \Component\Sms\Send();
        var_dump($sms->send($data));exit;*/

        $sms = new Sms();
        $this->view->sms_list = $sms->getAll(); // 单独通道属性整合行属性
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
        $this->view->setting = $sms->getObj($sms_name)->setting(); // 单独通道属性
        $this->view->sms = $sms->getByName($sms_name);   // 单独通道属性整合行属性
    }
}