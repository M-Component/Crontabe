<?php
namespace Pam;

use Phalcon\Security;
use Phalcon\Di;

class Account
{
    public function getLoginType($login_account){
        if (preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $login_account)) {
            return 'email';
        }
        if (preg_match('/^1[34578]{1}[0-9]{9}$/', $login_account)) {
            return 'mobile';
        }
        throw new \Phalcon\Exception('用户名仅支持手机号或邮箱');
        return 'local';
    }

    public function checkLogin($account, $password)
    {
        $pam_account = \PamAccount::findFirst(array(
            "login_account = :login_account:",
            'bind' => array('login_account' => $account)
        ));
        if (!$pam_account) {
            throw new \Phalcon\Exception('用户名或密码错误');
        }
        $account = \Account::findFirst($pam_account->account_id);
        $security = new Security();
        if (!$security->checkHash($password, $account->login_password)) {
            $security->hash(rand());
            throw new \Phalcon\Exception('用户名或密码错误');
        }
        return $account->toArray();
    }

    public function create($account_data = array())
    {
        $account = new \Account();
        $account->username = $account_data['username'];
        $account->mobile = $account_data['mobile'];
        $account->email = $account_data['email'];
        $account->name = $account_data['name'];
        $account->create_time = time();

        $security = new Security();
        $login_password = $security->hash($account_data['login_password']);
        $account->login_password = $login_password;

        $pam_accounts = array();
        $pam_account = new \PamAccount();
        $pam_account->login_account = $account_data['username'];
        $pam_account->login_type = 'local';
        $pam_account->login_password = $login_password;
        $pam_accounts[] = $pam_account;


        if (!empty($account_data['role_id'])) {
            $role_accounts = [];
            foreach ($account_data['role_id'] as $ke => $val) {
                $role_account = new \RoleAccount();
                $role_account->role_id = $val;
                $role_accounts[] = $role_account;
                unset($role_account);
            }
            $account->RoleAccount = $role_accounts;
        } else {
            throw new \Phalcon\Exception('用户必须要有角色');
        }

        if ($account_data['mobile']) {
            $pam_account = new \PamAccount();
            $pam_account->login_account = $account_data['mobile'];
            $pam_account->login_type = 'mobile';
            $pam_accounts[] = $pam_account;
        }
        if ($account_data['email']) {
            $pam_account = new \PamAccount();
            $pam_account->login_account = $account_data['email'];
            $pam_account->login_type = 'email';
            $pam_accounts[] = $pam_account;
        }

        $account->pamAccount = $pam_accounts;


        if (!$account->save()) {
            foreach ($account->getMessages() as $message) {
                throw new \Phalcon\Exception($message);
            }
            throw new \Phalcon\Exception('账户创建失败');
        }
    }


    public function edit($account, $account_data = array())
    {
        $account->name = $account_data['name'];
        $account->mobile = $account_data['mobile'];
        $account->email = $account_data['email'];
        $pam_accounts = array();

        if ($account_data['login_password']) {
            $security = new Security();
            $account->login_password = $security->hash($account_data['login_password']);
        }

        $old_pam_accounts = array();
        foreach ($account->pamAccount as $v) {
            $old_pam_accounts[$v->login_type] = $v;
        }

        $pam_accounts[] = $old_pam_accounts['local'];
        if ($account_data['mobile']) {
            if ($old_pam_accounts['mobile']) {
                $pam_account = $old_pam_accounts['mobile'];
                $pam_account->login_account = $account_data['mobile'];
            } else {
                $pam_account = new \PamAccount();
                $pam_account->login_account = $account_data['mobile'];
                $pam_account->login_type = 'mobile';
            }
            $pam_accounts[] = $pam_account;
        } else {
            $old_pam_accounts['mobile'] && $old_pam_accounts['mobile']->delete();
        }
        if ($account_data['email']) {
            if ($old_pam_accounts['email']) {
                $pam_account = $old_pam_accounts['email'];
                $pam_account->login_account = $account_data['email'];
            } else {
                $pam_account = new \PamAccount();
                $pam_account->login_account = $account_data['email'];
                $pam_account->login_type = 'email';
            }
            $pam_accounts[] = $pam_account;
        } else {
            $old_pam_accounts['email'] && $old_pam_accounts['email']->delete();
        }


        /**
         * 数据库的参数如果不在,传入参数之内,删除数据库数据
         *
         * 传入参数不在数据之内,则插入数据库
         */
        $new_role = $account_data['role_id'];
        if (!empty($new_role)) {
            foreach ($account->RoleAccount as $key => $value) {
                if (!in_array($value->role_id, $new_role)) {
                    \RoleAccount::findFirst("role_id={$value->role_id} AND account_id={$account->id}")->delete();
                } else {
                    unset($new_role[array_search($value->role_id, $new_role)]);
                }
            }

            $role_accounts = [];
            foreach ($new_role as $savRoelId) {
                $role_account = new \RoleAccount();
                $role_account->role_id = $savRoelId;
                $role_accounts[] = $role_account;
            }

            $account->RoleAccount = $role_accounts;
        } else {
            throw new \Phalcon\Exception('用户必须要有角色');
        }

        $account->pamAccount = $pam_accounts;


        if (!$account->save()) {
            foreach ($account->getMessages() as $message) {
                throw new \Phalcon\Exception($message);
            }
            throw new \Phalcon\Exception('账户修改失败');
        }
    }


    public function updateAccountLoginInfo($account_id)
    {
        $account = \Account::findFirst($account_id);
        $account->login_time = time();
        return $account->update();
    }
}
