<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

include APP_PATH . "/app/task_config/config.php";
//
include APP_PATH . "/app/task_config/loader.php";

$config = $di->getConfig();


date_default_timezone_set($config->datetime_zone);