<?php
namespace Validation\Member;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
class Signin extends Validation{

  public function initialize(){
    $this->setFilters('username','trim');
    $this->setFilters('login_password','trim');

    $this->add('username',new PresenceOf(array(
      'message' => '用户名不能为空',
      'cancelOnFail' => true,
    )));

    $this->add('username',new Regex(array(
      "pattern" => "/^(1[34578]{1}[0-9]{9})|(\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*)$/",
      "message" => "请使用正确的手机号码或邮箱",
    )));

    $this->add('login_password',new PresenceOf(array(
      'message' => '密码不能为空',
      "cancelOnFail" => true,
    )));
    $this->add('login_password',new StringLength(array(
      'messageMinimum' => '密码最短为6位数',
      'min' => 6,
    )));
  }
}
