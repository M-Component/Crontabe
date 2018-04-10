<?php
class Jobs extends \Mvc\AdvModel
{
    public function get_columns(){
        return array(
            'id'=>array(
                'type'=> 'text',
                'name' =>'ID'
            ),
            'queue'=>array(
                'type'=>'text',
                'name'=>'类型',
                'search'=>'has'
            ),
            'attempts'=>array(
                'type'=>'text',
                'name' =>'运行次数'
            ),
            'reserved'=>array(
                'type'=>array(
                    '0'=>'待执行',
                    '1'=>'执行中'
                ),
                'name'=>'执行状态'
            ),
            'reserved_time'=>array(
                'type'=>'datetime',
                'name'=>'开始执行时间'
            ),
            'available_time'=>array(
                'type'=>'datetime',
                'name'=>'可以执行时间'
            ),
            'create_time'=>array(
                'type'=>'create_time',
                'name'=>'入队时间'
            ),
            'sort'=>array(
                'type'=>'text',
                'name'=>'权重'
            ),
        );
    }
}
