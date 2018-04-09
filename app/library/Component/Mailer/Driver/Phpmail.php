<?php
namespace Component\Mailer\Driver;

use Component\Mailer\MailerInterface;
use Component\Mailer\Driver\Base;

class Phpmail extends Base implements \Component\Mailer\MailerInterface{

    public $nameConfig = 'PHP Mail 配置';

    public function setting()
    {
        return array(
            'display_name'=>array(
                'title'=>'驱动器',
                'type'=>'text',
                'default'=>$this->nameConfig,
                'edit'=>'false'
            ),
            'driver'=>array(
                'type'=>'hidden',
                'default'=>'Phpmail',
            ),
            'email'=>array(
                'title'=>'发件人邮箱',
                'type'=>'text',
            ),
            'name'=>array(
                'title'=>'发件人名称',
                'type'=>'text',
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
            'order_num'=>array(
                'title'=>'排序',
                'type'=>'text',
                'default' => 0
            ),
        );
    }
}