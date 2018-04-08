<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Message extends \Mvc\AdvModel
{
    public $member_id = array(
        'type' => 'belongsTo:Member',
        'name' => '用户名',
    );

    public $type = array(
        'type' =>array(
            '0'=>'邮箱',
            '1'=>'手机'
        ),
        'name'=>'发送方式',
    );

    public $target = array(
        'type'=>'text',
        'name'=>'手机号码',
        'search' =>true
    );

    public $content = array(
        'type'=>'text',
        'name'=>'发送内容'
    );

    public $create_time = array(
        'type'=>'datetime',
        'name'=>'发送时间',
    );


    public function initialize()
    {
        $this->belongsTo('member_id', 'Member', 'id');
    }
}
