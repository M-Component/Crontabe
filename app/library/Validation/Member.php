<?php
namespace Validation;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
class Member extends Validation{

    public function initialize()
    {
        $this->setFilters('username', 'trim');
        $this->setFilters('login_password', 'trim');

        $this->add('username', new PresenceOf(array(
            'message' => '用户名不能为空',
            "cancelOnFail" => true,
        )));

        $this->add('username', new Callback(array(
            'callback' => function($data) {
                return \Utils::isMobile($data['username']) || \Utils::isEmail($data['username']);
            },
            'message' => '请使用正确的手机号码或邮箱',
            "cancelOnFail" => true,
        )));

        $this->add('login_password', new PresenceOf(array(
            'message' => '密码不能为空',
            "cancelOnFail" => true,
        )));

        $this->add( 'login_password',new StringLength(array(
            'messageMinimum' => '密码最短为6位数',
            'min'            => 6,
        )));
    }
}
