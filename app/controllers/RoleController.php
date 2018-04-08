<?php

class RoleController extends BackstageController
{
    var $model_name = 'Role';
    var $index_title = '角色及权限';
    var $custom_action = array(
        'use_delete' => true,
    );

    public function addAction()
    {
        $this->add();
        $this->view->acl = $this->access->acl;
        $this->output('role/edit');
    }

    public function editAction($id)
    {
        $condition ='id='.$id;
        $this->edit($condition);
        $role_model = new Role();
        $model_data = $role_model->findFirst($id)->toArray();
        $this->view->model_data_resources = unserialize($model_data['resources']);

        $this->view->acl = $this->access->acl;

        $this->output('role/edit');
    }

    public function saveAction()
    {
        $postData = $this->request->getPost();

        if (!empty($postData['resources']) && is_array($postData['resources'])) {
            $postData['resources'] = serialize($postData['resources']);
        }
        $ctl = $this->dispatcher->getControllerName();
        $url = $postData['_redirect'] ?: $this->url->get($ctl);
        unset($postData['_redirect']);
        try {
            $this->begin($url);
            $this->save($postData);
        } catch (\Exception $e) {
            $this->end(false,$e->getMessage());
        }
        $this->end(true);
    }
}