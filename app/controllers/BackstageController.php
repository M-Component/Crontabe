<?php
use Phalcon\Mvc\Dispatcher;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class BackstageController extends ControllerBase
{

    protected $account;

    var $show_finder_detail = false;
    var $custom_action = array();
    var $group_action =array();
    var $list_action = array(
        'use_add' => true,
        'use_delete' => false,
        'custom_actions' => array()
    );
    var $allow_action = true;
    var $base_filter = array();
    var $time_format = array('datetime', 'date');
    var $redirect_url = '';

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $auth = new \Component\Account\Auth();
        if (!$auth->isLogin()) {
            $dispatcher->forward(array('controller' => 'passport', 'action' => 'login'));
            return false;
        }
        $this ->account = $this ->session ->get('account');
        $this ->view->_account = $this->account;
    }

    public function initialize()
    {
        $action = $this->view->action = $this->dispatcher->getActionName();
        $this->view->controller = $this->dispatcher->getControllerName();
        $title = $action . '_title';
        $this->view->title = isset($this->$title) ? $this->$title : '';
        if (isset($this->model_name) && $this->model_name) {
            $this->model = new $this->model_name;
        } else {
            $this->model = (object)array();
        }

        if ($this->request->isAjax()) {
            $this->view->setLayout('ajax');
        } else {
            $this->view->setLayout('backstage');
        }
    }

    /**
     * 对象选择器选择数据的时候的页面
     */
    public function finderAction()
    {
        $params = $this->request->get();
        $this->base_filter = $params['base_filter'] ?  (is_array($params['base_filter']) ?$params['base_filter'] :json_decode($params['base_filter'] ,1) ): array();
        $filter = $params['filter'] ? : array();
        $this->finder(array(
            'title' => '请选择',
            'model' => $params['model'],
            'filter' =>$filter,
            'limit' => $params['limit'] ?: 10,
            'allow_action' => false,
        ));

        $this->view->multiple = $params['multiple'];
        $this->view->in_modal = $params['in_modal'];
        $in_page = $params['in_page'];
        if ($in_page) {
            $this->view->pick('backstage/finder_body');
        } else {
            $this->view->pick('backstage/finder');
        }

    }

    public function indexAction()
    {
        $this->finder(array(
            'show_finder_detail' => $this->show_finder_detail,
            'list_action' => array_merge($this->list_action, $this->custom_action),
            'allow_action' => $this->allow_action,
            'model' => $this->model_name
        ));
        $this->output('backstage/list');
    }


    /*新建数据*/
    public function addAction()
    {
        $this->add();
        $this->output('backstage/edit');
    }

    /*编辑数据*/
    public function editAction($id)
    {
        $condition = 'id=' . $id;
        $this->edit($condition);
        $this->output('backstage/edit');
    }

    /*保存数据*/
    public function saveAction()
    {
        $postData = $this->request->getPost();
        $ctl = $this->dispatcher->getControllerName();
        $url = $postData['_redirect'] ?: $this->url->get($ctl);
        unset($postData['_redirect']);
        try {
            $this->begin($url);
            $this->save($postData);
        } catch (\Exception $e) {
            $this->end(false, $e->getMessage());
        }
        $this->end(true);
    }

    public function deleteAction()
    {
        $this->begin();
        $postData = $this->request->getPost();
        $model = $this->model;
        $ids = $postData['id'];
        foreach ($ids as $id) {
            $obj = $model->findFirst($id);
            if ($obj->delete() === false) {
                $messages = $obj->getMessages();
                foreach ($messages as $message) {
                    $this->end(false, $message->getMessage());
                }
            }
        }
        $this->end(true);
    }

    public function upload_imageAction()
    {
        try {
            $upload_files = $this->upload();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('上传成功', '', $upload_files);
    }

    /*上传文件*/
    protected function upload()
    {

        $upload_config = $this->config->upload->toArray();
        $driver = $upload_config['default'];
        $config = $upload_config['drivers'][$driver];
        $driverConfig = $config['driverConfig'];
        $upload = new \Component\Upload\Upload($config, $driver, $driverConfig);
        $upload_files = $upload->upload($_FILES);

        if ($upload_files) {
            foreach ($upload_files as &$file) {
                $upload_model = new \Upload();
                $upload_model->md5 = $file['md5'];
                $upload_model->sha1 = $file['sha1'];
                $upload_model->name = $file['name'];
                $upload_model->save_path = $file['save_path'];
                $upload_model->save_name = $file['save_name'];
                $upload_model->size = $file['size'];
                $upload_model->ext = $file['ext'];
                $upload_model->type = $file['type'];
                $upload_model->driver = $driver;
                $upload_model->create_time = time();
                $upload_model->save();
                $file['url'] = $config['domain'] . $file['save_path'] . $file['save_name'];
            }

            return $upload_files;
        }
        throw new \Exception($upload->getError());

    }


    protected function finder($params = array())
    {
        $this->view->title = $params['title'] ?: $this->view->title;
        $this->view->show_finder_detail = $params['show_finder_detail'] ?: $this->show_finder_detail;
        $this->view->list_action = $params['list_action'] ?: $this->list_action;
        $this->view->allow_action = isset($params['allow_action']) ? $params['allow_action'] : $this->allow_action;
        $this->view->group_action = $params['group_action'] ? : $this->group_action;
        $this->view->multiple = true;
        $this->view->dom_id = uniqid();
        $model_name = $params['model'];
        $model = $params['model'] ? new $params['model'] : $this->model;
        $orderBy = $params['orderBy'] ?: 'id desc';
        $this->view->orderBy = $orderBy;

        $current_page = $this->request->getQuery("page", "int") ?: 1;
        $limit = $params['limit'] ?: ($this->request->getQuery("limit", "int") ?: 20);
        $this->view->limit = $limit;

        $filter = $params['filter'] ?: $this->request->getQuery("filter");
        $filter = is_array($filter) ? $filter : json_decode($filter, 1);
        $this->view->filter = $filter;

        $columns = $model->get_columns();
        $search = array();
        foreach ($columns as $key => $column) {
            if (!is_array($column) || empty($column)) {
                unset($columns[$key]);
                continue;
            }
            if ($column['search']) {
                $search[] = array(
                    'label' => $column['name'],
                    'name' => $column['search'] === true ? $key : ($key . '|' . $column['search'])
                );
            }
            if ($column['filter']) {
                $filter_columns[] = array(
                    'label' => $column['name'],
                    'name' => $column['filter'] === true ? $key : ($key . '|' . $column['filter']),
                    'type' => $column['type']
                );
            }
        }
        $this->view->search = $search;
        if (!empty($search)) {
            foreach ($search as $v) {
                if (isset($filter[$v['name']])) {
                    $v['value'] = $filter[$v['name']];
                    $current_search = $v;
                }
            }
            $this->view->current_search = $current_search ?: $search[0];
        }

        $this->view->filter_columns = $filter_columns;

        $filter = array_merge($this->base_filter, (array)$filter);
        $this->_parseFilter($filter);
        if (method_exists($model, 'parseFilter')) {
            $model->parseFilter($filter);// 引用传递
        }
        $condition = \Mvc\DbFilter::filter($filter);
        $builder = $this->modelsManager->createBuilder()
            ->columns("*")
            ->from($model_name)
            ->where($condition)
            ->orderBy($orderBy);

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit" => $limit,
                "page" => $current_page,
            ]
        );
        $page = $paginator->getPaginate();
        $finder = $page->items->toArray();
        $count = count($finder);
        $extendColumns = array();
        if (method_exists($model, 'finder_extends_columns')) {
            $extendColumns = $model->finder_extends_columns();
        }
        if ($count > 0) {
            foreach ($finder as $key => $value) {
                foreach ($extendColumns as $func => $label) {
                    if (method_exists($model, 'finder_extends_' . $func)) {
                        $real_func = 'finder_extends_' . $func;
                        $extendColumns[$func]['value'][$value['id']] = $model->$real_func($value);
                    }
                }
                //$this->customColumns($value['id']);
            }
            if (method_exists($model, 'modify_finder')) {
                $model->modify_finder($finder);
            }
            foreach ($columns as $column => $attr) {
                if (is_string($attr['type'])) {
                    $type = explode(':', $attr['type']);
                    if ($type[0] == 'belongsTo') {
                        $this->finderBelongsTo($finder, $column, $type[1]);
                    }
                }
            }
        }
        $this->getPageUrl($page);
        $page->items = $finder;
        $page->limit = $limit;
        $this->view->extend_columns = $extendColumns;
        $this->view->columns = $columns;
        $this->view->page = $page;
    }


    protected function add($model_name = null)
    {
        $model = $model_name ? new $model_name : $this->model;
        $this->view->belongsTo = $this->modelsManager->getBelongsTo($model);
        $this->view->hasMany = $this->modelsManager->getHasMany($model);
        $columns = $model->get_columns();
        foreach ($columns as $key => $column) {
            if (!is_array($column) || empty($column)) {
                unset($columns[$key]);
            }
        }
        $this->view->columns = $columns;
        $this->view->_redirect = $this->request->getHTTPReferer();
    }

    protected function edit($condition, $model_name = null)
    {
        $model = $model_name ? new $model_name : $this->model;
        $this->view->belongsTo = $this->modelsManager->getBelongsTo($model);
        $this->view->hasMany = $this->modelsManager->getHasMany($model);
        $columns = $model->get_columns();
        foreach ($columns as $key => $column ) {
            if (!is_array($column)|| empty($column)) {
                unset($columns[$key]);
            }
        }
        $this->view->columns = $columns;
        $this->view->model_data = $model->findFirst($condition);
        $this->view->_redirect = $this->request->getHTTPReferer();
    }

    protected function save($postData, $model_name = null)
    {
        $model = $model_name ? new $model_name : $this->model;
        $columns = $model->get_columns();
        if ($postData['id']) {
            $model = $model->findFirst($postData['id']);
        }

        foreach ($columns as $key => $column) {
            if (!is_array($column) || !isset($column['type'])) {
                unset($columns[$key]);
                continue;
            }
            if (!isset($postData[$key]) && is_array($model->$key)) {
                $model->$key = null;
            }
            if (isset($postData[$key]) && in_array($column['type'], $this->time_format)) {
                if (!$postData[$key]) {
                    $postData[$key] = time();
                } else {
                    $postData[$key] = (is_string($postData[$key]) && !is_numeric($postData[$key])) ? strtotime($postData[$key]) : $postData[$key];
                }
            }
            if (!$postData['id'] && $column['type'] == 'create_time') {
                $postData[$key] = time();
            }
            if ($column['type'] == 'update_time') {
                $postData[$key] = time();
            }
        }
        foreach ($postData as $_k => $_v) {
            $model->$_k = $_v;
        }

        $flag = $model->save();
        foreach ($model->getMessages() as $message) {
            throw new \Phalcon\Exception($message->getMessage());
        }
        return $model;

    }

    private function finderBelongsTo(&$finder, $column, $model)
    {
        $data = $finder;
        $ids = array_keys(\Utils::array_change_key($data, $column));
        $belongsMdl = new $model;
        $belongsColumns = $belongsMdl->get_columns();
        foreach ($belongsColumns as $k => $v) {
            if (!is_array($v) || empty($v)) {
                unset($belongsColumns[$k]);
                continue;
            }
            if ($v['is_title']) {
                $title = $k;
                break;
            }
        }
        if (!$title) {
            return false;
        }
        $belongsData = $belongsMdl->find(array('id in (\'' . implode($ids, "','") . '\')', 'columns' => 'id ,' . $title))->toArray();
        if (empty($belongsData)) {
            return false;
        }
        $belongsData = \Utils::array_change_key($belongsData, 'id');
        foreach ($finder as $k => $v) {
            $finder[$k][$column] = $belongsData[$v[$column]][$title];
        }
        unset($v);
    }


    private function getPageUrl(&$page)
    {
        $params = $this->request->getQuery();
        $core_url = $params['_url'];
        unset($params['_url']);
        $page->core_url = $this->url->get($core_url);
        $page->current_url = $this->url->get($core_url, $params);
        unset($params['page']);
        $page->first_url = $this->url->get($core_url, $params);
        $params['page'] = $page->before;
        $page->before_url = $this->url->get($core_url, $params);
        $params['page'] = $page->next;
        $page->next_url = $this->url->get($core_url, $params);
        $params['page'] = $page->last;
        $page->last_url = $this->url->get($core_url, $params);
    }

    private function _parseFilter(&$filter)
    {
        if (empty($filter)) {
            return true;
        }
        foreach ($filter as $k => $v) {
            $v = trim($v);
            if (preg_match("/^\d{4}-\d{2}-\d{2}(((\s+\d{2})?)|((\s+\d{2}\:\d{2})?)|((\s+\d{2}\:\d{2}:\d{2})?))/iUs", $v)) {
                $v = strtotime($v);
            }
            $filter[$k] = $v;
        }
    }
}
