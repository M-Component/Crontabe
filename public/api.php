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
        "/api/member/mlogin",
        array( new \Api\Member(),"mobileLogin")
    );

    $app->post(
        "/api/member/oauth",
        array( new \Api\Member(),"oauth")
    );

    $app->post(
        "/api/member/bind",
        array( new \Api\Member(),"bind")
    );

    $app->get(
        "/api/member/{id}/logout",
        array( new \Api\Member(),"logout")
    );

    $app->post(
        "/api/member/sms",
        array(new \Api\Member(),"sendSmsVcode")
    );
    $app->post(
        "/api/member/email",
        array(new \Api\Member(),"sendEmailVcode")
    );

    $app->get(
        "/api/goods",
        array(new \Api\Goods(),"getList")
    );
    $app->get(
        "/api/goods/{id}",
        array(new \Api\Goods(),"getGoodsRow")
    );

    $app->get(
        "/api/goods/{id}/reviews",
        array(new \Api\Goods(),"getReviews")
    );

    $app->get(
        "/api/oauth",
        array( new \Api\Oauth(),"getList")
    );
    $app->get(
        "/api/oauth/{oauth_name}",
        array( new \Api\Oauth(),"getOauth")
    );

    $app->get(
        '/api/subscribe',
        array( new \Api\Subscribe(), "getList")
    );
    $app->get(
        '/api/subscribe/{id}',
        array( new \Api\Subscribe(), "getSubscribe")
    );
    $app->post(
        '/api/subscribe',
        array( new \Api\Subscribe(), "setSubscribe")
    );

    $app->get(
        "/api/version",
        array(new \Api\Version(),'getVersion')
    );

    $app->get(
        "/api/menu",
        array(new \Api\Menu(),'getList')
    );

    $app->handle();
}catch (\Phalcon\Exception $e){
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
