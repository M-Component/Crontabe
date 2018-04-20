<?php

use Robot\Remote;
use Page\Consumer as Consumer;
class TaskController extends BackstageController
{
    var $model_name = 'Task';
    var $index_title = '任务列表';

    var $custom_action = array(
        'use_delete'=>true,
        'use_add'=>true
    );

    public function saveAction(){
        $postData = $this->request->getPost();
        $ctl = $this->dispatcher->getControllerName();
        $url = $postData['_redirect'] ? :$this->url->get($ctl);
        unset($postData['_redirect'] );
        try{
            $this ->begin($url);
            if($postData['id']){
                $task = \Task::findFirst($postData['id']);
                $crontab = \Crontab::findFirst($task->crontab_id);
            }
            if(!$crontab){
                $crontab = new \Crontab();
                $crontab ->create_time = time();
            }
            $crontab->name = $postData['name'];
            $crontab->rule = $postData['rule'];
            $crontab->job ='Crawl';

            if(!$crontab->save()){
                foreach($crontab->getMessages() as $message){
                    $this->end(false ,$message->getMessage());
                }
            }
            $postData['crontab_id']=$crontab->id;
            $task = $this->save($postData);
            if(!$postData['id']){
                $crontab = \Crontab::findFirst($crontab->id);
                $crontab->data = json_encode(array('task_id' =>$task->id));

                if(!$crontab->save()){
                    foreach($crontab->getMessages() as $message){
                        $this->end(false ,$message->getMessage());
                    }
                }
            }

        }catch (\Phalcon\Exception $e){
            $this ->end(false ,$e->getMessage());
        }
        $this->end(true);
    }

    public function execAction($id){
        try{
            $task =\Task::findFirst($id);
            $consumer = new Consumer();
            $task_pages = $task->taskPage;
            foreach ($task_pages as $task_page) {
                $config =\Page::getPageConfig($task_page->page_id);
                if (false == $config) {
                    continue;
                    //                    throw new \Phalcon\Exception($task_page->page_id.'配置信息不合法');
                }
                $config['taskId'] = time ();
                $config['is_main'] =true;
                $sort = 0;
                $consumer->pushQueue($config,$sort,null ,$task->urgent);
            }
            $this->success();
        }catch (Exception $e){
            $this->error($e ->getMessage());
        }
    }

    public function pageAction($task_id){
        $base_filter = array('filter_sql' =>'id in (select page_id from TaskPage where task_id ='.$task_id.')');
        $filter = $this->request->get('filter') ? : array();
        $filter = is_array($filter) ? $filter :json_decode($filter ,1);
        $filter = array_merge($filter ,$base_filter);
        $this->finder(array(
            'title' =>'Page管理',
            'model' =>'Page',
            'list_action' => array(
                'use_add'=>false,
                'use_delete'=>$this->url->get('task/pagedelete/'.$task_id),
            ),
            'filter' =>$filter,
        ));
        $this->view->add_filter = json_encode($filter);
        $this->view->task_id = $task_id;
        $this->view->object_select_model='Page';

        $this->output('task/page');
    }

    public function pageaddAction($task_id=0){
        $ids = $this ->request->getPost('id');
        $this->begin();
        $task = Task::findFirst($task_id);
        if(!$task){
            $this ->end(false ,'无效的任务ID');
        }
        $task_page = array();
        foreach($ids as $page_id){
            if(\TaskPage::count(\Mvc\DbFilter::filter(array('task_id'=>$task_id ,'page_id'=>$page_id)))){
                continue;
            }
            $task_page_obj = new \TaskPage();
            $task_page_obj->task_id = $task_id;
            $task_page_obj->page_id = $page_id;
            $task_page[] = $task_page_obj;
        }
        if(empty($task_page)){
            $this ->end(true);
        }
        $task->taskPage = $task_page;
        if(!$task->save()){
            $this ->end(false);
        }

        $this ->end(true);
    }

    public function pagedeleteAction($task_id){
        $this ->begin();
        $postData = $this->request->getPost();
        $task_page = new \TaskPage();
        $ids = $postData['id'];
        $task = \Task::findFirst($task_id);

        foreach ($ids as $id) {
            $obj = $task_page::find("task_id =$task_id AND page_id=$id");
            if ($obj->delete() === false) {
                $messages = $obj->getMessages();
                foreach ($messages as $message) {
                    $this ->end(false ,$message);
                }
            }
        }
        $this->end(true);
    }

    //测试队列
    public function testAction ()
    {
        $queue = new Queue();
        $queue->setAsGlobal ();
        $data = array();
        for ($i = 0; $i < 3; $i++) {
            $data[] = array();
        }
        Queue::push ('Test', $data);
    }


    //测试计划任务
    public function retryAction(){
        $obj = new \PriceMonitor;
        $obj->exec();
        exit;
    }


    public function repeatAction(){
        $jobs = \PagePool::find("type='list'")->toArray();
        $result = array();
        foreach($jobs as $job){
            $payload = json_decode($job['data'] ,1);
            $url = $payload['url'];
            $result[$url] =$job['id'];
        }
        $ids = array_values($result);
        $job_list =\PagePool::find(\Mvc\DbFilter::filter(array(
            'id|notin' =>$ids,
            'type'=>'list'
        )));
        var_dump(count($job_list),count($ids));
        foreach($job_list as $v){
        //    $v->delete();
        }
        echo 'success';
        exit;

    }


    public  function get_pageAction(){
        $platform= \Platform::findFirst(1) ;
        $url ='http:\/\/mall.jd.com\/view_search-407708-0-99-1-24-1.html';
        $page_num=2 ;
        $total_page=3;
        $url = \Page::getPageUrl($platform ,$url ,$page_num ,$total_page);
        var_dump($url);exit;
    }

    public function newAction(){
        $jobs =\Jobs::find();
        foreach($jobs as $job){
            $payload = json_decode($job->payload ,1);
            $job_pool = new \PagePool();
            $job_pool ->data =json_encode($payload['data']);
            $job_pool ->status=0;
            $job_pool ->type = $job->queue;
            $job_pool ->create_time = $job->create_time;
            $job_pool ->sort =0;
            $job_pool ->save();
        }
    }

    public  function test_httpAction(){
        $client = new \GuzzleHttp\Client();

        $requests = function ($robot_list) {
            $uri = 'https://www.gzpblog.com/20170731/1175.html';
            foreach ($robot_list as $robot) {
                yield new \GuzzleHttp\Psr7\Request('GET', $uri);
            }
        };


        $robot_list =array();
        for ($i=0;$i<10 ; $i++) {
            $robot_list[] =array(
                array(
                    'ip'=>'127.0.01',
                    'port'=>8080+$i
                )
            );
        }


        $start = time();
        $pool = new \GuzzleHttp\Pool($client, $requests($robot_list), [
            'concurrency' => 10,
            'fulfilled' => function ($response, $index) use($robot_list ,$start){
                // this is delivered each successful response
                echo "<pre>";
                $end =time();
                var_dump($end-$start);
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
            },
        ]);

// Initiate the transfers and create a promise
        $promise = $pool->promise();

// Force the pool of requests to complete.
        $promise->wait();
        echo ('success');exit;
    }


    public function test_goodsAction(){
        $monitor = new \GoodsMonitor();
        $monitor ->exec();
    }

    public function test_execAction($config_id){
        $pages = \Page::find('config_id='.$config_id);
        $consumer = new \Page\Consumer();
        foreach($pages as $page){
            $config =\Page::getPageConfig($page->id);
            if (false == $config) {
                continue;
            }
            $config['taskId'] = time ();
            $config['is_main'] =true;
            $sort = 9999999;
            $consumer->pushQueue($config,$sort  );
        }
    }

    public function test_commentAction(){

        $obj = new \CommentCrawl();
        $obj->exec();
        exit;
    }

    public function test_yieldAction(){
        \Recoil\React\ReactKernel::start(function(){
            for($i=0 ;$i<100 ;$i++){
                $this->request('');
            }
        });
    }

    public function request($url){
        yield;
        $res = \Utils::curl_client('http://seven.dongchaguan.cn/');
        return $res;
    }

    public function json_testAction(){
        $str ='{"test":"value哈哈"}';
        $jsonObj = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        var_dump($jsonObj->decode($str));exit;
    }

    public function jpushAction(){
        $sender = new \Sender\App();
        $targets =array(
            'target'=>'1a0018970a8b3045795'
        );
        $res =$sender->sendOne($target , '' ,'' ,array(
            'message' =>'hello world !'
        ));
        var_dump($res);
        exit;

    }
}
