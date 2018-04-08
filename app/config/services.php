<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as Flash;
use Phalcon\Mvc\Model\Manager as ModelsManager;

use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Component\Plugins\SecurityPlugin;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/app/config/config.php";
});

/**
 * Shared loader service
 */
$di->setShared('loader', function () {
    $config = $this->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/app/config/loader.php';

    return $loader;
});

// 权限系统
$di->setShared('access',function (){
    return include APP_PATH .'/app/config/access.php';
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view, $di) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $di);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            //自定义过滤器
            $compiler = $volt->getCompiler();
            $compiler->addExtension(
                new \FunctionExtension()
            );
            $compiler->addFilter('money', function($resolvedArgs, $exprArgs) {
                if(!strpos($resolvedArgs ,',')){
                    $resolvedArgs .= ',2';
                }
                return "number_format($resolvedArgs ,'.' ,'')";
            });

            $compiler->addFilter('qrcode', function($resolvedArgs, $exprArgs) {
                return "\\Utils::qrcode($resolvedArgs)";
            });

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $dbConfig = $config->database->toArray();
    $adapter = $dbConfig['adapter'];
    unset($dbConfig['adapter']);

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

    return new $class($dbConfig);
});

$di->setShared('modelsManager', function() {
    return new ModelsManager();
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    session_save_path("/tmp");
    ini_set('session.auto_start', 0);
    ini_set('session.gc_maxlifetime', 3600*24);
    ini_set('session.cookie_lifetime',  3600*24);
    $session->start();

    return $session;
});


// redis
$di->setShared('redisDb', function () {
    $config = $this->getConfig();
    if (extension_loaded('redis') && isset($config->redis)) {

        $redis_config = $config->redis->production;
        if ($config->application->debug) {
            $redis_config = $config->redis->development;
        }
        $redis = new \Redis();
        $redis->pconnect($redis_config->host, $redis_config->port, $redis_config->timeout);
        return $redis;
    }
    return false;
});

/**
 * cache
 */
$di->set('cache', function () {
    $frontCache = new \Phalcon\Cache\Frontend\Data(
        array(
            "lifetime" => 172800
        )
    );
    return new \Phalcon\Cache\Backend\File(
        $frontCache,
        array(
            "cacheDir" => $this->getConfig()->application->cacheDir,
        )
    );

});


$di->set('logger', function () {
    $file = $this->getConfig()->application->logDir . date('YmdH') . '.log';
    return new Phalcon\Logger\Adapter\File($file);
});

$di->set('fileCache', function () {
    $frontCache = new \Phalcon\Cache\Frontend\Data(
        array(
            "lifetime" => 172800
        )
    );
    return new \Phalcon\Cache\Backend\File(
        $frontCache,
        array(
            "cacheDir" => $this->getConfig()->application->cacheDir,
        )
    );
});
$di->set('dispatcher',function (){
    // 创建一个事件管理器
    $eventsManager = new EventsManager();

    $config = $this->getConfig();
    $eventsManager->attach('dispatch:beforeDispatch',new SecurityPlugin($config));

    $dispatch = new Dispatcher();
    $dispatch->setEventsManager($eventsManager);

    return $dispatch;


});



$config = $di->getConfig();
date_default_timezone_set($config->datetime_zone);
