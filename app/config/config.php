<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'pianyijiaowo',
        'charset'     => 'utf8',
        "persistent"  => true
    ],
    'application' => [
        'controllersDir' => APP_PATH . '/app/controllers/',
        'modelsDir'      => APP_PATH . '/app/models/',
        'migrationsDir'  => APP_PATH . '/app/migrations/',
        'viewsDir'       => APP_PATH . '/app/views/',
        'pluginsDir'     => APP_PATH . '/app/plugins/',
        'libraryDir'     => APP_PATH . '/app/library/',
        'vendorDir'     => APP_PATH . '/app/vendor/',
        'cacheDir'       => APP_PATH . '/app/cache/',
        'logDir'         => APP_PATH . '/app/logs/',
        'baseUri'        => '/',
    ],

    // REDIS
    'redis'         => [
        'development' => [
            'host'       => '127.0.0.1',
            'port'       => 6379,
            'timeout'    => 60,
            'auth'       => '',
            'persistent' => false,
        ],
        'production'  => [
            'host'       => '192.168.71.169',
            'port'       => 6379,
            'timeout'    => 60,
            'auth'       => '',
            'persistent' => false,
        ],
    ],
    // mongo
    'mongo'         => [
        'development' => [
            'host'       => '127.0.0.1',
            'username'   => null,
            'password'   => null,
            'port'       => 27017,
            'timeout'    => 60,
            'persistent' => false,
            'dbname'     =>'pianyijiaowo'
        ],
        'production'  => [
            'host'       => '192.168.71.169',
            'username'   => null,
            'password'   => null,
            'port'       => 27017,
            'timeout'    => 60,
            'persistent' => false,
            'dbname'     =>'pianyijiaowo'
        ],
    ],
    // 上传
    'upload' =>[
        'default' =>'local',
        'drivers' =>[
            'local'=>[
                'rootPath'=> APP_PATH.'/public/upload/',
                'subName' => ['date' ,'Ymd'],
                'domain'  => '/public/upload/',
                'exts'    => ['jpg', 'gif', 'png', 'jpeg'],
                'driverConfig' =>null
            ]
        ]
    ],
    // 时区
    'datetime_zone' => 'Asia/Shanghai',

    'queue' =>[
        'default' => 'database',
        'connections' => [
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
                'queue' => 'default',
                'expire' => 60,
            ],
            'database' => [
                'driver' => 'database',
                'table' => 'jobs',
                'queue' => 'default',
                'expire' => 60,
            ],
        ],
        'process'=>[
            'quick' =>20
        ]
    ],
    'cron' =>[
        'default' => 'database',
        'provider' => [
            'file' => [
                'driver' => 'file',
                'file' => APP_PATH . '/app/task_config/crontab.php',
            ],
            'database' => [
                'driver' => 'database',
                'table' => 'crontab',
            ],
        ]
    ],
    'api'=>[
        //'getPageGroup'=>'http://120.132.20.241:8833/api/member/{member_id}/page_group',
        'getPageGroupList'=>'http://prism/api/member/{member_id}/page_group',
        //'getPageGroup'=>'http://120.132.20.241:8833/api/page_group/{id}',
        'getPageGroup'=>'http://prism/api/page_group/{id}',
        //'delePageGroup'=>'http://120.132.20.241:8833/api/page_group/{id}',
        'delePageGroup'=>'http://prism/api/page_group',
        //'upPageGroup'=>'http://120.132.20.241:8833/api/member/page_group',
        'upPageGroup'=>'http://prism/api/member/page_group',
    ]
]);
