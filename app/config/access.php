<?php

//  多个单词组成多控制器首字母使用大写，不使用下划线
return new \Phalcon\Config([
    'acl' => [
        'user' => [
            'title' => '客户',
            'icon' => 'fa-users',
            'page_index' => [
                'title' => 'Page管理列表',
                'action' => [
                    [
                        'controller' => 'page',
                        'act' => 'index'
                    ]
                ]
            ],
            'page_edit' => [
                'title' => 'Page管理编辑',
                'action' => [
                    [
                        'controller' => 'page',
                        'act' => 'add'
                    ],
                    [
                        'controller' => 'page',
                        'act' => 'edit'
                    ]
                ]
            ],
            'page_delete' => [
                'title' => 'Page管理删除',
                'action' => [
                    [
                        'controller' => 'page',
                        'act' => 'delete'
                    ]
                ]
            ],
            'user_index' => [
                'title' => '品牌管理列表',
                'action' => [
                    [
                        'controller' => 'user',
                        'act' => 'index'
                    ]
                ]
            ],
            'user_edit' => [
                'title' => '品牌管理编辑',
                'action' => [
                    [
                        'controller' => 'user',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'user',
                        'act' => 'add'
                    ]
                ]
            ],
            'user_delete' => [
                'title' => '品牌管理删除',
                'action' => [
                    [
                        'controller' => 'user',
                        'act' => 'delete'
                    ]
                ]
            ],
            'PageConfig_index' => [
                'title' => 'page配置列表',
                'action' => [
                    [
                        'controller' => 'page_config',
                        'act' => 'index'
                    ]
                ]
            ],
            'PageConfig_edit' => [
                'title' => 'page配置编辑',
                'action' => [
                    [
                        'controller' => 'page_config',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'page_config',
                        'act' => 'add'
                    ]
                ]
            ],
            'PageConfig_delete' => [
                'title' => 'page配置删除',
                'action' => [
                    [
                        'controller' => 'page_config',
                        'act' => 'delete'
                    ]
                ]
            ],
            'member_index' => [
                'title' => '客户列表',
                'action' => [
                    [
                        'controller' => 'member',
                        'act' => 'index'
                    ]
                ]
            ],
            'member_edit' => [
                'title' => '客户编辑',
                'action' => [
                    [
                        'controller' => 'member',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'member',
                        'act' => 'add'
                    ]
                ]
            ],
            'member_delete' => [
                'title' => '客户删除',
                'action' => [
                    [
                        'controller' => 'member',
                        'act' => 'delete'
                    ]
                ]
            ],
            'MemberGroup_index'=>[
                'title'=>'用户分组',
                'action'=>[
                    [
                        'controller'=>'member_group',
                        'act'=>'index'
                    ]
                ]
            ],
            'MemberGroup_edit'=>[
                'title'=>'用户分组编辑',
                'action'=>[
                    [
                        'controller'=>'member_group',
                        'act'=>'edit'
                    ],
                    [
                        'controller'=>'member_group',
                        'act'=>'add'
                    ],
                ]
            ],
            'GoodsCategory_index'=>[
                'title'=>'商品分类',
                'action'=>[
                    [
                        'controller'=>'goods_category',
                        'act'=>'index'
                    ]
                ]
            ],
            'GoodsCategory_edit'=>[
                'title'=>'商品分类编辑',
                'action'=>[
                    [
                        'controller'=>'goods_category',
                        'act'=>'edit'
                    ],
                    [
                        'controller'=>'goods_category',
                        'act'=>'add'
                    ],
                ]
            ],
            'RemindUser_index'=>[
                'title'=>'店铺监控',
                'action'=>[
                    [
                        'controller'=>'remind_user',
                        'act'=>'index'
                    ],
                ]
            ],
            'RemindLog_index'=>[
                'title'=>'告警记录',
                'action'=>[
                    [
                        'controller'=>'remind_log',
                        'act'=>'index'
                    ],
                ]
            ],
            'message_index'=>[
                'title'=>'短信消息',
                'action'=>[
                    [
                        'controller'=>'message',
                        'act'=>'index'
                    ],
                ]
            ],
        ],
        'task' => [
            'title' => '任务',
            'icon' => 'fa-bar-chart',
            'task_index' => [
                'title' => '任务管理',
                'action' => [
                    [
                        'controller' => 'task',
                        'act' => 'index'
                    ]
                ]
            ],
            'task_edit' => [
                'title' => '任务编辑',
                'action' => [
                    [
                        'controller' => 'task',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'task',
                        'act' => 'add'
                    ]
                ]
            ],
            'task_delete' => [
                'title' => '任务删除',
                'action' => [
                    [
                        'controller' => 'task',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'task',
                        'act' => 'add'
                    ]
                ]
            ],
            'history_index' => [
                'title' => '历史列表',
                'action' => [
                    [
                        'controller' => 'history',
                        'act' => 'index'
                    ]
                ]
            ],
            'history_edit' => [
                'title' => '历史编辑',
                'action' => [
                    [
                        'controller' => 'history',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'history',
                        'act' => 'add'
                    ],
                ]
            ],
            'history_delete' => [
                'title' => '历史删除',
                'action' => [
                    [
                        'controller' => 'history',
                        'act' => 'delete'
                    ]
                ]
            ],
            'exceptions_index' => [
                'title' => '异常记录列表',
                'action' => [
                    [
                        'controller' => 'exceptions',
                        'act' => 'index'
                    ]
                ]
            ],
            'exceptions_edit' => [
                'title' => '异常记录编辑',
                'action' => [
                    [
                        'controller' => 'exceptions',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'exceptions',
                        'act' => 'add'
                    ],
                ]
            ],
            'exceptions_delete' => [
                'title' => '异常记录删除',
                'action' => [
                    [
                        'controller' => 'exceptions',
                        'act' => 'delete'
                    ]
                ]
            ],
        ],
        'robot' => [
            'title' => '服务器',
            'icon' => 'fa-tv',
            'robot_index' => [
                'title' => 'Robot管理',
                'action' => [
                    [
                        'controller' => 'robot',
                        'act' => 'index'
                    ]
                ]
            ],
            'robot_edit' => [
                'title' => 'Robot列表编辑',
                'action' => [
                    [
                        'controller' => 'robot',
                        'act' => 'edit'
                    ],
                    [
                        'controller'=>'robot',
                        'act'=>'add'
                    ]
                ]
            ],
            'robot_delete' => [
                'title' => 'Robot列表删除',
                'action' => [
                    [
                        'controller' => 'robot',
                        'act' => 'delete'
                    ]
                ]
            ],
            'idc_index' => [
                'title' => 'Idc列表',
                'action' => [
                    [
                        'controller' => 'idc',
                        'act' => 'index'
                    ]
                ]
            ],
            'idc_edit' => [
                'title' => 'Idc列表编辑',
                'action' => [
                    [
                        'controller' => 'idc',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'idc',
                        'act' => 'add'
                    ]
                ]
            ],
            'idc_delete' => [
                'title' => 'Idc列表删除',
                'action' => [
                    [
                        'controller' => 'idc',
                        'act' => 'delete'
                    ]
                ]
            ],
            'server_index' => [
                'title' => 'server列表',
                'action' => [
                    [
                        'controller' => 'server',
                        'act' => 'index'
                    ]
                ]
            ],
            'server_edit' => [
                'title' => 'server列表编辑',
                'action' => [
                    [
                        'controller' => 'server',
                        'act' => 'edit'
                    ],
                    [
                        'controller' => 'server',
                        'act' => 'add'
                    ]
                ]
            ],
            'server_delete' => [
                'title' => 'server列表删除',
                'action' => [
                    [
                        'controller' => 'server',
                        'act' => 'delete'
                    ]
                ]
            ],
        ],
        'seven'=>[
            'title'=>'七扇门',
            'icon' =>'fa-clock-o',
            'SevenDoors_index'=>[
                'title'=>'七扇门',
                'action'=>[
                    [
                        'controller'=>'seven_doors',
                        'act'=>'index'
                    ]
                ]
            ]
        ],
        'setting' => [
            'title' => '控制面板',
            'icon' => 'fa-cogs',
            'role_index' => [
                'title' => '角色及权限',
                'action' => [
                    [
                        'controller' => 'role',
                        'act' => 'index'
                    ]
                ]
            ],
            'role_edit' => [
                'title' => '角色及权限编辑',
                'action' => [
                    [
                        'controller' => 'role',
                        'act' => 'edit'
                    ],
                    [
                        'controller'=>'role',
                        'act'=>'add'
                    ]
                ]
            ],
            'account_index' => [
                'title' => '操作员列表',
                'action' => [
                    [
                        'controller' => 'account',
                        'act' => 'index'
                    ]
                ]
            ],
            'account_edit' => [
                'title' => '操作员编辑',
                'action' => [
                    [
                        'controller' => 'account',
                        'act' => 'edit'
                    ],
                    [
                        'controller'=>'account',
                        'act'=>'add'
                    ]
                ]
            ],
            'crontab_index' => [
                'title' => '计划任务',
                'action' => [
                    [
                        'controller' => 'crontab',
                        'act' => 'index'
                    ]
                ]
            ],
            'crontab_edit' => [
                'title' => '计划任务编辑',
                'action' => [
                    [
                        'controller' => 'crontab',
                        'act' => 'edit'
                    ],
                    [
                        'controller'=>'crontab',
                        'act'=>'add'
                    ]
                ]
            ],
            'jobs_delete' => [
                'title' => 'mysql列队删除',
                'action' => [
                    [
                        'controller' => 'jobs',
                        'act' => 'delete'
                    ]
                ]
            ],
            'setting_index' => [
                'title' => '配置',
                'action' => [
                    [
                        'controller' => 'setting',
                        'act' => 'index'
                    ]
                ]
            ]
        ]
    ]


]);
