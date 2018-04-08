<?php
namespace Component\Plugins;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

class SecurityPlugin extends Plugin
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getAcl()
    {
        $acl = new AclList();

        // 默认行为是 DENY(拒绝)访问
        $acl->setDefaultAction(Acl::DENY);

        $roles = [
            'havepermission' => new Role(
                'HavePermission',
                '有权限访问'
            ),
            'nopermission' => new Role(
                'NoPermission',
                '抱歉您没有权限访问'
            )
        ];

        // 两个对象
        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        // 获取当前用户的资源列表,用户只能访问自己列表里面的资源

        $privateResources = array();
        $auth = $this->session->get('account');
        if ($auth) {
           // 获取到用户可以访问的控制器和动作
            $privateResources = $this->accountAcl($auth);
        }

        foreach ($privateResources as $resource => $actions) {
            $acl->addResource(new Resource($resource), $actions);
        }

        $publicResources = array(
            'index' => array('index'),
            'passport' => array('login', 'index', 'initialize'),
            'errors' => array('show401', 'show404', 'show500'),
            'session' => array('index', 'register', 'start', 'end'),
        );
        foreach ($publicResources as $resource => $actions) {
            $acl->addResource(new Resource($resource), $actions);
        }

        // 允许 用户和游客 访问公共区域
        foreach ($roles as $role) {
            foreach ($publicResources as $resource => $actions) {
                foreach ($actions as $action) {
                    // allow() 允许访问资源上的角色，您可以使用“*”作为通配符示例：或者使用 $action($action这里面已经包含了当前所有的方法了)
                    $acl->allow($role->getName(), $resource, $action);
                }
            }
        }

        foreach ($privateResources as $resource => $actions) {
            foreach ($actions as $action) {
                $acl->allow('HavePermission', $resource, $action);
            }
        }

        // acl存储在会话中，APC在这里也很有用
        return $acl;
    }


    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $auth = $this->session->get('account');
        if ($auth){
            $account = \Account::findFirst($auth['account_id']);
            // 超级管理员是不存在 $account->Role
            if (count($account->Role) >=1){
                // 如果不是超级管理人员,才需要验证
                if (!($account->Role[0]->is_super || $account->username == 'admin')) {
                    if ($auth) {
                        $role = 'HavePermission';
                    } else {
                        $role = 'NoPermission';
                    }

                    $controller = $dispatcher->getControllerName();
                    $action = $dispatcher->getActionName();

                    // 获取资源列表
                    $acl = $this->getAcl();

                    if (!$acl->isResource($controller)) {
                        return false;
                    }

                    // 判断是否能操作,控制器上的动作
                    $allowed = $acl->isAllowed($role, $controller, $action);
                    if (!$allowed) {
                        $this->flash->error('您无权访问此模块');
                        //$this->session->destroy(); // 销毁会话
                        return false;
                    }
                }
            }
        }
        return true;
    }

    // 处理用户资源
    public function accountAcl($auth)
    {
        // 所有资源列表
        $resources = $this->access->acl;
        // 个人资源列表
        $account = \Account::findFirst($auth['account_id']);
        $role = $account->Role;
        $roleToArray = $role->toArray();
        foreach ($roleToArray as $key => $value) {
            if ($value['resources']) {
                foreach (unserialize($value['resources']) as $rek => $val) {
                    $res[] = $val;
                };
            }
        }
        $res = array_unique($res); // 重复我权限剔除掉
        $action = array();

        // 拿到个人资源里面的所有控制器和方法
        foreach ($resources as $noek => $noe) {
            foreach ($noe as $towk => $tow) {
                // 用户权限
                foreach ($res as $r => $s) {
                    if ($s == $towk) {
                        // 把同一个控制器里面的方法全部集中放到当前控制器下面
                        foreach ($tow['action'] as $kk => $contact) {
                            $action[$contact['controller']][] = $contact['act'];
                        }
                    }
                }
            }

        }
        return $action;
    }
}
