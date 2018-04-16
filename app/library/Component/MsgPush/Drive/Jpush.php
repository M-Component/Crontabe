<?php
namespace Component\MsgPush\Drive;
use Component\MsgPush\MsgPushInterface;

class Jpush extends Base implements MsgPushInterface {
    public $name = '极光推送';

    public function setting()
    {
        return array(
            'display_name' => array(
                'title' => '通道名称',
                'type' => 'text',
                'default' => $this->name
            ),
            'push' => array(
                'title' => '推送地址 API',
                'type' => 'text',
                'default' => ''
            ),
            'cid' => array(
                'title' => 'cid池 API',
                'type' => 'text',
                'default' => ''
            ),
            'group_push' => array(
                'title' => '应用分组推送 API',
                'type' => 'text',
                'default' => ''
            ),
            'validate' => array(
                'title' => '推送校验 API',
                'type' => 'text',
                'default' => ''
            ),
            'app_key' => array(
                'title' => 'APP_KEY',
                'type' => 'text',
                'default' => ''
            ),
            'master_secret' => array(
                'title' => 'Master Secret',
                'type' => 'password',
                'default' => ''
            ),
            'status' => array(
                'title' => '是否启用',
                'type' => 'select',
                'options' => array(
                    'true' => '是',
                    'false' => '否'
                ),
                'default' => 'false'
            ),
            'order_num' => array(
                'title' => '排序',
                'type' => 'number',
                'default' => 0
            ),
        );
    }
    
    public function send(){

    }
    
    public function SendOne(){

    }
    
    public function batchSend(){

    }
}