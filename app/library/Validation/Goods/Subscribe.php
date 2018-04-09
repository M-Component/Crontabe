<?php
namespace Validation\Goods;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Regex; // 正则
use Phalcon\Validation\Validator\StringLength;
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

        $this->add('value', new PresenceOf(array(
            "message" => "订阅值不能为空",
            'cancelOnFail' =>true
        )));
        
    }
}
