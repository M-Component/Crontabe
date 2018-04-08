<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Mvc\Model\Message as Message;

class PamAccount extends \Phalcon\Mvc\Model
{
    public function validation()
    {
        $validator = new Validation();
        $validator->add(array('login_account'), new Uniqueness([
            "message" => "该账号/手机号/邮箱已存在"
        ]));
        return $this->validate($validator);
    }


}
