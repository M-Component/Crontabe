<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

$task_config = new \Phalcon\Config([
    'task' => [
        'configDir'=> APP_PATH . '/app/task_config/',
        'taskDir'  => APP_PATH . '/app/tasks/',
    ],
]);
$di->getConfig() ->merge($task_config);
