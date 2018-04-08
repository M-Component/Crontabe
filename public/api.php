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

    $app->before(
        new \Api\Middleware\Auth()
    );

    $app->post(
        "/api/member",
        array( new \Api\Member(),"signup")
    );
    $app->patch(
        "/api/member",
        array( new \Api\Member(),"update")
    );

    $app->post(
        "/api/member/signin",
        array( new \Api\Member(),"signin")
    );

    $app->post(
        "/api/member/oauth",
        array( new \Api\Member(),"oauth")
    );

    $app->get(
        "/api/member/{id}/logout",
        array( new \Api\Member(),"logout")
    );


    $app->handle();
}catch (\Phalcon\Exception $e){
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
