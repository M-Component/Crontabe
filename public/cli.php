<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

define('APP_PATH', realpath('..'));
// Using the CLI factory default services container
$di = new CliDI();


$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/app/tasks",
        APP_PATH . "/app/library",
    ]
);

$loader->register();



// Load the configuration file (if any)

$configFile = APP_PATH. "/app/config/config.php";

if (is_readable($configFile)) {
    $config = include $configFile;

    $di->set("config", $config);
}



// Create a console application
$console = new ConsoleApp();

$console->setDI($di);



/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments["task"] = $arg;
    } elseif ($k === 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}



try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();

    exit(255);
}
