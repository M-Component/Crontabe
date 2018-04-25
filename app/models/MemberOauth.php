<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Mvc\Model\Message as Message;
use Phalcon\Security;

class MemberOauth extends \Mvc\AdvModel
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
    }

    public function beforeCreate(){
        $this->create_time =time();
    }
}
