<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libraryDir,
        APP_PATH.'/app/tasks'
    ]
)->register();
require $config->application->vendorDir.'autoload.php';
$loader->registerNamespaces([
    'Phalcon' => APP_PATH.'/app/incubator/Library/Phalcon/'
]);
require $config->application->vendorDir.'autoload.php';
