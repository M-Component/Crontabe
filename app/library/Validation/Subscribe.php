<?php
namespace Validation;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Regex; // 正则
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
class Subscribe extends Validation{

    public function initialize()
    {

        $this->add('goods_id', new PresenceOf(array(
            'message' => '商品不能为空',
            "cancelOnFail" => true,
        )));

        $this->add('rule', new PresenceOf(array(
            "message" => "规则不能为空",
            'cancelOnFail' =>true
        )));
        $this->add('rule', new InclusionIn([
            'message' => '不支持的规则',
            'domain' => ['gt', 'lt', 'up_percent' ,'down_percent'],
            'cancelOnFail' =>true
        ]));

        $this->add('value', new PresenceOf(array(
            "message" => "订阅值不能为空",
            'cancelOnFail' =>true
        )));

        $this->add('current_price', new PresenceOf(array(
            'message' => '商品价格不能为空',
            "cancelOnFail" => true,
        )));
        $this->add('from_time', new PresenceOf(array(
            'message' => '开始时间不能为空',
            "cancelOnFail" => true,
        )));
        $this->add('to_time', new PresenceOf(array(
            'message' => '结束时间不能为空',
            "cancelOnFail" => true,
        )));
    }
}
