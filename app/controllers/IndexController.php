<?php

class IndexController extends BackstageController
{
    public function indexAction()
    {
        $this->view->title = '概况';

        $message = new \Member\Message();

        $openid = array('target' => 'oMegI0qjepeExsL_U0x2by4_alv8');
        $prams = array(
            'first' => array(
                'value' => '我爱你你却爱着他',
                'color' => '#484891',
            ),
            'orderMoneySum' => array(
                'value' => '30亿元',
                'color' => '#FF0000'
            ),
            'orderProductName' => array(
                'vaule' => '大保健',
                'color' => '#FF5809',
            ),
            'Remark' => array(
                'value' => '欢迎下次光临',
                'color' => '#A3D1D1'
            )
        );
        $extend_params = array(
            'template_id' => '',
            'url' => 'https://api.pianyijiaowo.com',
            'miniprogram' => array(
                'appid' => '',
                'pagepath' => ''
            )
        );

        //$send = $message->setMsgType('wechat')->send(array($openid), $prams, $extend_params);

        $pa = array(
            array(
                'target'=>'oMegI0qjepeExsL_U0x2by4_alv8',
                'template_id' => 'MjxlycLS-rOSmYoUin_QesDUpo93gAHAukA4VVWbeqg',
                'url' => 'https://api.pianyijiaowo.com',
                'miniprogram' => array(
                    'appid' => '',
                    'pagepath' => ''
                ),
                'data'=>array(
                    'first' => array(
                        'value' => 'Mdvtrw道',
                        'color' => '#484891',
                    ),
                    'orderMoneySum' => array(
                        'value' => '30亿元',
                        'color' => '#FF0000'
                    ),
                    'orderProductName' => array(
                        'value' => '大保健',
                        'color' => '#FF5809',
                    ),
                    'Remark' => array(
                        'value' => '欢迎下次光临',
                        'color' => '#A3D1D1'
                    )
                )
            )
        );

        $send = $message->setMsgType('wechat')->batchSend($pa);
        var_dump($send);
        exit;
    }
}

