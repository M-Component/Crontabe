<?php

$config = $di->getConfig();
$di ->getLoader()->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libraryDir,
        $config->task->taskDir
    ]
)->register();