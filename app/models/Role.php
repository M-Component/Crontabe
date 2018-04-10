<?php
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Events\Event;

class Role extends \Phalcon\Mvc\Model
{
    public function get_columns(){
        return array(
            'name'=>array(
                'type' => 'text',
                'name' => '角色名',
                'update' => true,
            ),
            'is_super'=>array(
                'type' => array(
                    '0' => '否',
                    '1' => '是',
                ),
                'name' => '超级管理员',
            ),
        );
    }

    public function initialize()
    {
        $this->belongsTo('id','RoleAccount','role_id');

        $this->hasMany(
            "id",
            "RoleAccount",
            "role_id"
        );
    }

    public function finder_extends_columns()
    {
        return array(
            'edit'=>array('label'=>'编辑'),
        );
    }

    public function finder_extends_edit($row)
    {
        return Phalcon\Tag::linkTo('Role/edit/'.$row['id'],'编辑');
    }
}
