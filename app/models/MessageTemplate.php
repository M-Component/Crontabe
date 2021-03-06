<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class MessageTemplate extends \Mvc\AdvModel
{

    public function get_columns(){
        return array(
            'name'=>array(
                'type' => 'text',
                'name' => '模版名称',
            ),

            'template'=>array(
                'type' =>array(
                    'vcode'=>'通用验证码',
                    'signin'=>'登录验证码',
                    'signup'=>'注册验证码',
                    'gt'=>'商品价格订阅（高于）',
                    'lt'=>'商品价格订阅（低于）',
                    'up_percent'=>'商品价格订阅（上涨）',
                    'down_percent'=>'商品价格订阅（下降）',
                ),
                'name'=>'模版类型',
            ),

            'msg_type'=>array(
                'type' =>array(
                    'sms'=>'短信',
                    'email'=>'邮件',
                    'app' =>'APP',
                ),
                'name'=>'通知类型',
            ),
            'title'=>array(
                'type' => 'text',
                'name' => '标题',
            ),
            'content'=>array(
                'type'=>'code',
                'name'=>'模板内容'
            ),
        );
    }

    public function finder_extends_columns()
    {
        return array('edit' => array('label' => '编辑'),);
    }

    public function finder_extends_edit($row)
    {
        return Phalcon\Tag::linkTo('message_template/edit/' . $row['id'], '编辑');
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(array('template','msg_type'), new Uniqueness([
            "message" => "已存在相同通知类型、模板类型的记录"
        ]));
        return $this->validate($validator);
    }

}
