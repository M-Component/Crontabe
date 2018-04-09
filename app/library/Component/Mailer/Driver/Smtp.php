<?php
namespace Component\Mailer\Driver;

use Component\Mailer\MailerInterface;
use Component\Mailer\Driver\Base;

class Smtp extends Base implements \Component\Mailer\MailerInterface{

    public $nameConfig = 'Smtp配置';

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
               'default'=>'smtp',
           ),
           'host'=>array(
               'title'=>'服务器',
               'type'=>'text',
               'default'=>'smtp.163.com'
           ),
           'port'=>array(
               'title'=>'端口',
               'type'=>'text',
               'default'=>'465'
           ),
           'username'=>array(
               'title'=>'用户名',
               'type'=>'text'
           ),
           'password'=>array(
               'title'=>'密码',
               'type'=>'password'
           ),
           'email'=>array(
               'title'=>'发件人邮箱',
               'type'=>'text',
           ),
           'name'=>array(
               'title'=>'发件人名称',
               'type'=>'text',
           ),
           'encryption'=>array(
               'title'=>'加密方式',
               'type'=>'text',
               'default' => '',
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
            )
       );
    }
}