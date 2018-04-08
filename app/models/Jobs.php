<?php
class Jobs extends \Mvc\AdvModel
{
    public $id = array(
        'type'=> 'text',
        'name' =>'ID'
    );

    public $queue =array(
        'type'=>'text',
        'name'=>'类型',
        'search'=>'has'
    );
    public $attempts=array(
        'type'=>'text',
        'name' =>'运行次数'
    );
    public $reserved =array(
        'type'=>array(
            '0'=>'待执行',
            '1'=>'执行中'
        ),
        'name'=>'执行状态'
    );
    public $reserved_time =array(
        'type'=>'datetime',
        'name'=>'开始执行时间'
    );
    public $available_time =array(
        'type'=>'datetime',
        'name'=>'可以执行时间'
    );
    public $create_time =array(
        'type'=>'create_time',
        'name'=>'入队时间'
    );
    public $sort =array(
        'type'=>'text',
        'name'=>'权重'
    );
}
