<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Mvc\Model\Message as Message;
use Phalcon\Security;

class MemberEmail extends \Mvc\AdvModel
{

    public function initialize()
    {
    }

    public function validation()
    {
    }

    public function beforeCreate(){
        $this->create_time =time();
    }
}
