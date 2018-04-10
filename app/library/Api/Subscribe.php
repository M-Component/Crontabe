<?php
namespace Api;
use Mvc\Paginator\Adapter\Collection as PaginatorCollectionBuilder;
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
        $data =\Subscribe::findById($id);
        $this->success($data);
    }

    public function getList(){
        $limit =$this->request->getQuery('limit',array('int')) ? :2;
        $page =$this->request->getQuery('page',array('int')) ? : 1;

        $columns =array(
            'member_id'=>true,
            'goods_id'=>true,
            'id'=>true
        );

        $condition =array(

        );

        $orderBy = array(
            'goods_id'=>-1
        );

        $builder =array(
            'columns'=>$columns,
            'from'=>'Subscribe',
            'where'=>$condition,
            'orderBy'=>$orderBy,
        );
        $paginator = new PaginatorCollectionBuilder(
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
