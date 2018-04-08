<?php

class AccountController extends BackstageController
{
    var $model_name = 'Account';
    var $index_title = '操作员';

    public function saveAction()
    {
        $account_data = $this->request->getPost();
        $pam_account = new \Pam\Account();
        $ctl = $this->dispatcher->getControllerName();
        $url = $account_data['_redirect'] ?: $this->url->get($ctl);
        $this->begin($url);
        try {
            if ($account_data['id']) {
                $account = \Account::findFirst($account_data['id']);

                $pam_account->edit($account, $account_data);

            } else {
                $pam_account->create($account_data);
            }
        } catch (\Phalcon\Exception $e) {
            $this->end(false, $e->getMessage());
        }
        $this->end(true);
    }


    public function addAction()
    {
        $this->add();
        $role = Role::find();
        $this->view->role = $role;
        $this->output('account/edit');
    }


    public function editAction($id)
    {
        $this->edit($id);
        if (!($id == 1)) {
            $role = Role::find();
            $this->view->role = $role;

            // 当前用户的角色组
            $account = Account::findFirst($id);

            foreach ($account->role->toArray() as $k => $v) {
                $account_role[] = $v['id'];
            }
            $this->view->account_role = $account_role;
        }
        $this->output('account/edit');
    }


    public function roleAccount($row)
    {
        $roleaccount = new RoleAccount();
        $roleaccount->role_id = $row['role_id'];
        if ($row['id']) {
            $roleaccount->account_id = $row['account_id'];
        } else {

        }
        if ($roleaccount->save()) {
            return true;
        } else {
            return false;
        }

    }
}