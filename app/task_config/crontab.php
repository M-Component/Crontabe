<?php
return [
    'TestCron' =>
        [
            'disable'=>true,
            'name' => 'Demo',  //任务名称
            'rule'     => '7 * * * * *',//定时规则
            'job'  => 'Demo',//命令处理类
            'params'     =>[],
        ],
    [
        'name' => 'Crawl',  //任务名称
        'rule'     => '0 0 0 */1 * *',//定时规则
        'job'  => 'Crawl',//命令处理类
        'params'     =>[],
    ],
];