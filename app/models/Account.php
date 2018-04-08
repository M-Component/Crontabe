<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Mvc\Model\Message as Message;
class Account extends \Phalcon\Mvc\Model
{
    public $username = array(
        'type' => 'text',
        'name' => '用户名',
        'update' =>false
    );
    public $login_password = array(
        'type' => 'password',
        'name' => '密码',
        'hide' => true
    );
    public $name = array(
        'type' => 'text',
        'name' => '姓名',
    );
    public $mobile = array(
        'type' => 'text',
        'name' => '手机号',
    );
    public $email = array(
        'type' => 'text',
        'name' => '邮箱',
    );
    public $create_time = array(
        'type' => 'text',
        'name' => '创建时间',
        'edit' =>false
    );

    public function initialize()
    {
        $this->hasMany(
            'id',
            'PamAccount',
            'account_id'
        );

        $this->hasMany(
            'id',
            'RoleAccount',
            'account_id'
        );
        $this->hasManyToMany(
            'id',
            'RoleAccount',
            'account_id', 'role_id',
            'Role',
            'id'
        );
    }

    public function finder_extends_columns()
    {
        return array('edit' => array('label' => '编辑'),);
    }

    public function finder_extends_edit($row)
    {
        return Phalcon\Tag::linkTo('account/edit/' . $row['id'], '编辑');
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(array('username'), new Uniqueness([
            "message" => "该用户名已存在，不能重复"
        ]));
        return $this->validate($validator);
    }

}
