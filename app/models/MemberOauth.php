<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Mvc\Model\Message as Message;
use Phalcon\Security;

class MemberOauth extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        $this->belongsTo(
            'member_id',
            'Member',
            'id'
        );
    }

    public function validation()
    {
        /*
        $validator = new Validation();
        $validator->add(array('login_account'), new Uniqueness([
            "message" => "该账号/手机号/邮箱已存在"
        ]));
        return $this->validate($validator);
        */
    }

    public function beforeCreate(){
        $this->create_time =time();
    }
}
