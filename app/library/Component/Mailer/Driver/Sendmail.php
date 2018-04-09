<?php
namespace Component\Mailer\Driver;

use Component\Mailer\MailerInterface;

class Sendmail implements \Component\Mailer\MailerInterface{

    public $nameConfig = 'Sendmail配置';

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
                'default'=>'Sendmail',
            ),
            'sendmail'=>array(
                'title'=>'php配置',
                'type'=>'hidden',
                'default'=>'/usr/sbin/sendmail -bs',
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