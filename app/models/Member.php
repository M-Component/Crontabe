<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Mvc\Model\Message as Message;
use Phalcon\Security;
class Member extends \Mvc\AdvModel
{
    public function get_columns(){
        return array(
            'username'=>array(
                'type' => 'text',
                'name' => '用户名',
                'update' =>false,
                'is_title'=>true
            ),
            'name'=>array(
                'type' => 'text',
                'name' => '姓名',
            ),
            'mobile'=>array(
                'type' => 'text',
                'name' => '手机号',
            ),
            'email'=>array(
                'type' => 'text',
                'name' => '邮箱',
            ),
            'reg_time'=>array(
                'type' => 'text',
                'name' => '注册时间',
                'edit' =>false
            ),
        );
    }

    public function initialize()
    {
        $this->useDynamicUpdate(true);
    }

    public function finder_extends_columns()
    {
        return array('edit' => array('label' => '编辑'),);
    }

    public function finder_extends_edit($row)
    {
        return Phalcon\Tag::linkTo('member/edit/' . $row['id'], '编辑');
    }

    //仅支持邮箱或者手机号码注册
    public function beforeCreate(){
        $security =  new Security();
        $this->login_password =$security->hash($this->login_password);
        $this->reg_ip = $this->getDI()->get('request')->getClientAddress();
        $this->reg_time =time();
    }

    public function validation()
    {

    }

}
