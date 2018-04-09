<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Mvc\Model\Message as Message;
use Phalcon\Security;

class PamMember extends \Phalcon\Mvc\Model
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
        $validator = new Validation();
        $validator->add(array('login_account'), new Uniqueness([
            "message" => "该账号/手机号/邮箱已存在"
        ]));
        return $this->validate($validator);
    }


    public function checkLogin($account ,$password){
      $account = self::findFirst(array(
            "login_account = :login_account:",
            'bind' => array('login_account' => $account)
        ));
        if(!$account){
            throw new \Exception('用户名或密码错误');
        }
        $member = $account->member;
        $security =  new Security();
        if (!$security->checkHash($password, $member->login_password)) {
            $security->hash(rand());
            throw new \Exception('用户名或密码错误');
        }
        if($member->disabled){
            throw new \Exception('该账号已禁止登录');
        }
        return $member;
    }


}
