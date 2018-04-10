<?php

use Mvc\AdvCollection;
class Subscribe extends AdvCollection
{
    public $member_id;

    public $goods_id;

    public $rule;

    public $value;

    public $wechat_notice;

    public $app_notice;

    public $sms_notice;

    public $email_notice;

    public $create_time;

    public $_id;

    public function get_columns(){
        return array(
            'goods_id'=>array(
                'name'=>'商品ID',
                'type'=>'text',
            ),
            'rule'=>array(
                'name'=>'规则',
                'type'=>array(
                    'gt'=>'价格高于',
                    'lt'=>'价格低于',
                    'up_percent'=>'价格上涨',
                    'down_percent'=>'价格下降'
                )
            ),
            'value'=>array(
                'name'=>'预警值',
                'type'=>'text'
            ),
            'wechat_notice'=>array(
                'name'=>'微信提醒',
                'type'=>array(
                    '0'=>'否',
                    '1'=>'是'
                )
            ),
            'app_notice'=>array(
                'name'=>'APP提醒',
                'type'=>array(
                    '0'=>'否',
                    '1'=>'是'
                )
            ),
            'sms_notice'=>array(
                'name'=>'短信提醒',
                'type'=>array(
                    '0'=>'否',
                    '1'=>'是'
                )
            ),
            'email_notice'=>array(
                'name'=>'邮件提醒',
                'type'=>array(
                    '0'=>'否',
                    '1'=>'是'
                )
            ),
            'create_time'=>array(
                'name'=>'创建时间',
                'type'=>'create_time',
            )
        );
    }

    public function initialize()
    {

    }
}
