<?php
namespace Validation\Member;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Regex;

class Mobile extends Validation{
  public function initialize(){
    $this->setFilters('username','trim');

    $this->add('username', new PresenceOf(array(
      'message' => '手机号码不能为空',
      'cancelOnFail' => true,
    )));
    $this->add('username',new Regex(array(
      "pattern" => "/^(1[34578]{1}[0-9]{9})$/",
      "message" => "请使用正确的手机号码",
    )));
    $this->add('vcode',new PresenceOf(array(
      'message' => '验证码不能为空',
      'cancelOnFail' => true,
    )));
  }
}

