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

    $app->get(
        "/api/page",
        array( new \Api\Page(),"getAllPage")
    );

    $app->get(
        "/api/page/{id}",
        array( new \Api\Page(),"getPage")
    );
    $app->get(
        "/api/page/name",
        array( new \Api\Page(),"getName")
    );
    $app->post(
        "/api/page",
        array( new \Api\Page(),"setPage")
    );
    $app->get(
        "/api/page/adv",
        array( new \Api\Page(),"getAdv")
    );

    $app->get(
        "/api/page/seckill",
        array( new \Api\Page(),"getSeckill")
    );

    $app->get(
        "/api/page/group",
        array( new \Api\Page(),"getPageGroup")
    );


    $app->post(
        "/api/robot/free",
        array( new \Api\Robot(),"freeRobot")
    );

    $app->post(
        "/api/member",
        array( new \Api\Member(),"setMember")
    );
    $app->patch(
        "/api/member",
        array( new \Api\Member(),"upMember")
    );


    $app->get(
        "/api/user",
        array( new \Api\User(),"getAllUser")
    );

    $app->get(
        "/api/member/{id}/page",
        array( new \Api\Member(),"getPage")
    );
    $app->post(
        "/api/member/{id}/page",
        array( new \Api\Member(),"setPage")
    );


    $app->post(
        "/api/member/{id}/keyword",
        array( new \Api\Member(),"setKeyword")
    );
    $app->get(
        "/api/member/{id}/keyword",
        array( new \Api\Member(),"getKeyword")
    );
    // 删除用户下的关键词
    $app->delete(
        "/api/member/{id}/keyword/{data_id}",
        array( new \Api\Member(),"deleteKeyword")
    );



    $app->post(
        "/api/member/{id}/remind",
        array( new \Api\Member(),"setRemind")
    );
    $app->get(
        "/api/member/{id}/remind",
        array( new \Api\Member(),"getRemind")
    );
    $app->patch(
        "/api/member/{id}/remind",
        array( new \Api\Member(),"updateRemind")
    );
    $app->delete(
        "/api/member/{id}/remind/{rule_id}",
        array( new \Api\Member(),"deleteRemind")
    );



    $app->post(
        "/api/remind/group",
        array( new \Api\Remind(),"setGroup")
    );
    $app->get(
        "/api/remind/group",
        array( new \Api\Remind(),"getGroup")
    );
    $app->patch(
        "/api/remind/group",
        array( new \Api\Remind(),"updateGroup")
    );
    $app->delete(
        "/api/remind/group/{member_id}/{id}",
        array( new \Api\Remind(),"deleteGroup")
    );



    // 获取用户权限
    $app->get(
        "/api/member/access",
        array( new \Api\Member(),"getAccess")
    );
    // 获取用户所有的组
    $app->get(
        "/api/member/{id}/role",
        array( new \Api\Member(),"getRoleList")
    );
    // 获取一个主账户下面的单个组
    $app->get(
        "/api/member/{id}/role/{role_id}",
        array( new \Api\Member(),"getRole")
    );
    // 添加组
    $app->post(
        "/api/member/{id}/role",
        array( new \Api\Member(),"addRole")
    );
    // 删除用户下的组
    $app->delete(
        "/api/member/{id}/role/{role_id}",
        array( new \Api\Member(),"deleteRole")
    );
    $app->patch(
        "/api/member/{id}/role",
        array( new \Api\Member(),"updateRole")
    );


    // 获取所有子账户
    $app->get(
        "/api/member/{id}/sub",
        array(new \Api\Member(),'getSubList')
    );
    // 获取单独一个子账户
    $app->get(
        "/api/member/{id}/sub/{member_id}",
        array(new \Api\Member(),'getSub')
    );
    // 添加一个子账户
    $app->post(
        "/api/member/{id}/sub",
        array(new \Api\Member(),'addSub')
    );
    // 删除用户下的组
    $app->delete(
        "/api/member/{id}/sub/{sub_id}",
        array( new \Api\Member(),"deleteSub")
    );
    $app->patch(
        "/api/member/{id}/sub",
        array( new \Api\Member(),"updateSub")
    );

    $app->get(
        "/api/member/{id}/parent",
        array(new \Api\Member(),'getParent')
    );


    $app->post(
        "/api/exception/add",
        array( new \Api\Exception(),"addException")
    );

    $app->post(
        "/api/server/docker/stop",
        array( new \Api\Server(),"stopDocker")
    );

    $app->get(
        "/api/goods/category",
        array( new \Api\Goods(),"getCategory")
    );
    $app->get(
        "/api/goods/category/children",
        array( new \Api\Goods(),"getCategoryChildren")
    );

    $app->post(
        "/api/goods/monitor",
        array( new \Api\Goods(),"monitor")
    );
    $app->post(
        "/api/goods/adjust",
        array( new \Api\Goods(),"adjust")
    );

    $app->get(
        "/api/goods/warning/{member_id}",
        array( new \Api\Goods(),"getWarning")
    );

    // 删除用户下的告警记录
    $app->delete(
        "/api/goods/warning/{member_id}/{id}",
        array( new \Api\Goods(),"deleteWarning")
    );


    $app->post(
        "/api/adjust",
        array( new \Api\Adjust(),"setAdjust")
    );
    $app->post(
        "/api/adjust/import",
        array( new \Api\Adjust(),"importAdjust")
    );
    $app->get(
        "/api/adjust",
        array( new \Api\Adjust(),"getAdjust")
    );
    $app->patch(
        "/api/adjust",
        array( new \Api\Adjust(),"updateAdjust")
    );
    $app->delete(
        "/api/adjust/{member_id}/{id}",
        array( new \Api\Adjust(),"deleteAdjust")
    );
    $app->post(
        "/api/adjust/rule",
        array( new \Api\Adjust(),"setRule")
    );
    $app->get(
        "/api/adjust/rule",
        array( new \Api\Adjust(),"getRule")
    );
    $app->patch(
        "/api/adjust/rule",
        array( new \Api\Adjust(),"updateRule")
    );
    $app->delete(
        "/api/adjust/rule/{member_id}/{id}",
        array( new \Api\Adjust(),"deleteRule")
    );

    $app->get(
        "/api/adjust/log",
        array( new \Api\Adjust(),"getLog")
    );
    $app->patch(
        "/api/adjust/log",
        array( new \Api\Adjust(),"updateLog")
    );
    $app->delete(
        "/api/adjust/log/{member_id}/{id}",
        array( new \Api\Adjust(),"deleteLog")
    );

    $app->get(
        "/api/store",
        array( new \Api\Store(),"getStores")
    );
    $app->get(
        "/api/store/competition",
        array( new \Api\Store(),"getCompetition")
    );

    $app->handle();
}catch (\Phalcon\Exception $e){
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
