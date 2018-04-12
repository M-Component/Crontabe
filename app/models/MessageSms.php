<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class MessageSms extends \Mvc\AdvModel
{
    public function get_columns(){
        return array(
            'member_id'=>array(
                'type' => 'belongsTo:Member',
                'name' => '用户名',
            ),

            'target'=>array(
                'type'=>'text',
                'name'=>'发送目标',
                'search' =>true
            ),

            'title'=>array(
                'type'=>'text',
                'name'=>'标题'
            ),

            'create_time'=>array(
                'type'=>'datetime',
                'name'=>'发送时间',
            ),
        );
    }



    public function initialize()
    {

    }
}
