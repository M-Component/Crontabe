<?php

class Crontab extends \Phalcon\Mvc\Model
{
    public function get_columns(){
        return array(
            'name'=>array(
                'type' =>'text',
                'name'=>'任务名称'
            ),

            'rule'=>array(
                'type' =>'text',
                'name'=>'规则'
            ),

            'status'=>array(
                'type' =>array(
                    '0' =>'正常',
                    '1' =>'暂停',
                ),
                'name'=>'状态'
            ),

            'job'=>array(
                'type' =>'text',
                'name' =>'任务处理类'
            ),
            'unique'=>array(
                'type' =>'number',
                'name' =>'进程数限制'
            ),
            'create_time'=>array(
                'type' =>'create_time',
                'name' =>'任务创建时间',
                'edit' =>false
            ),
            'last_time'=>array(
                'type' =>'datetime',
                'name' =>'上次执行时间',
                'edit'=>false
            ),
        );
    }

    public function finder_extends_columns(){
        return array(
            'edit' =>array('label'=>'编辑'),
        );
    }
    public function finder_extends_edit($row){
        return Phalcon\Tag::linkTo('crontab/edit/'.$row['id'],'编辑');
    }
}
