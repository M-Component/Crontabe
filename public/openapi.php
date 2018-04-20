<?php

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL^E_NOTICE);
define('APP_PATH', realpath('..'));
try{
    $di = new FactoryDefault();
    include APP_PATH . "/app/config/services.php";
    $di->getLoader();
    $app = new Micro();
    $app->setDI($di);
    $app->notFound(
        function () use ($app) {
            $app->response->setStatusCode(404, "Not Found");
            $app->response->sendHeaders();
            echo "This is crazy, but this page was not found!";
        }
    );
   
    $app->map(
        "/openapi/oauth/callback/{oauth_class}",
        array( new \Openapi\Oauth(),"callback")
    )->via(array(
        "GET",
        "POST",
    ));

    // 验证来自于微信服务器授权信息
    $app->get(
        '/openapi/wechat',
        array( new \Openapi\Wechat(), "doRequest")
    );
    /* $app->get(
        "/openapi/qrcode",
        array( new \Openapi\Tools(),"qrcode")
        );*/
    $app->handle();
}catch (\Phalcon\Exception $e){
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
