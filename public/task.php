<?php
use Phalcon\Di\FactoryDefault\Cli;
use Task\ConsoleApp as ConsoleApp;

error_reporting(E_ALL^E_NOTICE);

define('APP_PATH', realpath('..'));

// Compatible with php7
if(!class_exists('Error'))
{
    class Error extends Exception
    {
    }
}

try{
    $di = new Cli();
    include APP_PATH . "/app/config/services.php";
    include APP_PATH . "/app/task_config/services.php";
    $di->getLoader();

    // 创建console应用
    $console = new ConsoleApp();
//    $di->setShared("console", $console);
    $console->setDI($di);
    $console->run();
}catch (Exception $e) {
    echo get_class($e), ": ", $e->getMessage(), "\n";
    echo " File=", $e->getFile(), "\n";
    echo " Line=", $e->getLine(), "\n";
    echo $e->getTraceAsString() . "\n";
}
