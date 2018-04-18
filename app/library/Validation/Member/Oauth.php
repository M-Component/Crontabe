<?php
namespace Validation\Member;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\InclusionIn;

class Oauth extends Validation{
  public function initialize(){

    $this->add('type', new PresenceOf(array(
      'message' => '账号类型不能为空',
      'cancelOnFail' => true,
    )));
    $this->add('type', new InclusionIn([
        'message' => '不支持的账号类型',
        'domain' => ['wxapp', 'taobao'],
        'cancelOnFail' =>true
    ]));
    $this->add('userinfo',new PresenceOf(array(
        'message' => '账号信息不能为空',
        'cancelOnFail' => true,
    )));
  }
}

