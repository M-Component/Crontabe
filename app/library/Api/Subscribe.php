<?php
namespace Api;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
class Subscribe extends Base
{
    public function setSubscribe(){
        $data =$this->request->getPost();
        try {
            $validation = new \Validation\Goods\Subscribe();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }
            $subscribe =new \Subscribe();
            $subscribe->member_id =$data['member_id'];
            $subscribe->goods_id =$data['goods_id'];
            $subscribe->rule ='above';
            $subscribe->value=10;
            $subscribe->wechat_notice=1;
            $subscribe->app_notice=1;
            $subscribe->sms_notice=1;
            $subscribe->email_notice=1;
            $subscribe->create_time=time();

            if(false ===$subscribe->create()){
                foreach ($subscribe->getMessages() as $message) {
                    throw new \Exception($message);
                }
            }
            $this->success($subscribe);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function getSubscribe($id){
        
    }

    public function getList(){
        $limit =$this->request->getQuery('limit') ? :10;
        $page =$this->request->getQuery('page') ? :1;
        $res =\Subscribe::findFirst();
        var_dump($res);exit;
        $filter =array();
        $condition = \Mvc\DbFilter::filter($filter);
        $orderBy = 'create_time desc';
        $builder = $this->modelsManager->createBuilder()
                 ->columns("*")
                 ->from('Subscribe')
                 ->where($condition)
                 ->orderBy($orderBy);

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit" => $limit,
                "page" => $page,
            ]
        );
        $page = $paginator->getPaginate();
        $this->success($page);
    }

}
