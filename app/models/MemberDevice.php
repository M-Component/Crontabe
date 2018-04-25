<?php
use Phalcon\Validation;

class MemberDevice extends \Mvc\AdvModel
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
