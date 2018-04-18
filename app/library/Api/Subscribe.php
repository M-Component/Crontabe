<?php
namespace Api;
use Mvc\Paginator\Adapter\Collection as PaginatorCollectionBuilder;
class Subscribe extends Base
{
    public function __construct(){
        parent::__construct();
        //        $this->checkLogin();
    }

    public function setSubscribe(){

        $data =$this->request->getPost();
        try {
            $validation = new \Validation\Subscribe();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }
            $subscribe =new \Subscribe();
            $subscribe->member_id =$this->member['member_id'];
            $subscribe->goods_id =$data['goods_id'];
            $subscribe->rule =$data['rule'];
            $subscribe->value=$data['value'];
            $subscribe->current_price=$subscribe->price= $data['current_price'];
            $subscribe->from =$data['from'];
            $subscribe->to =$data['to'];
            $subscribe->tag =$data['tag'];
            $subscribe->mobile =$data['mobile'];
            $subscribe->email =$data['email'];

            $subscribe->wechat_notice=$data['wechat_notice'];
            $subscribe->app_notice=data['app_notice'];

            $subscribe->sms_notice= $data['mobile'] ? 1 :0;
            $subscribe->email_notice=$data['email'] ? 1 :0;

            $subscribe->create_time=time();

            if(false ===$subscribe->create()){
                foreach ($subscribe->getMessages() as $message) {
                    throw new \Exception($message);
                }
                throw new \Exception('保存失败');
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
        $status =$this->request->getQuery('status') ? : 0;
        $keyword = $this->request->getQuery('keyword');

        $columns =array(
            'member_id'=>true,
            'goods_id'=>true,
        );

        $condition =array(
            '$where' =>'this.price>this.current_price',

            'goods_id'=>array(

                    '$regex'=>'1111'

            )
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
