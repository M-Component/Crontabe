<?php
namespace Api;
use Mvc\Paginator\Adapter\Collection as PaginatorCollectionBuilder;
class Subscribe extends Base
{

    public function setSubscribe(){
        $this->checkLogin();
        $data =$this->request->getPost();
        try {
            $validation = new \Validation\Subscribe();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }
            //需要验证手机号码或者邮箱
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
            $subscribe->app_notice=$data['app_notice'];

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
        $this->checkLogin();
        $data =\Subscribe::findById($id);
        $this->success($data);
    }

    public function getList(){
        $this->checkLogin();
        $limit =$this->request->getQuery('limit',array('int')) ? :10;
        $page =$this->request->getQuery('page',array('int')) ? : 1;
        $status =$this->request->getQuery('status') ? : 0;
        $tag = $this->request->getQuery('tag' ,array('trim' ,'addslashes'));

        $columns =array(
            //            'goods_id'=>true,
        );
        $condition=array(
            //    'member_id'=>$this->member['member_id']
        );
        if($status==1){
            $condition['$where'] ='this.price>this.current_price';
        }
        if($status==2){
            $condition['$where'] ='this.price<this.current_price';
        }
        if($tag){
            $condition['tag'] =$tag;
        }

        $orderBy = array(
            'create_time'=>-1
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

    public function setNotice(){

        $data = $this->request->getPost();
        $vcode = new \Component\Vcode();
        $member_id =$this->member['member_id'];
        try {
            if (!$vcode->verify($data['target'], 'vcode', $data['vcode'])) {
                throw new \Exception('验证码错误');
            }
            if(!$member_id){
                $this->success('验证成功');
            }
            $type = $data['type'];
            if($type=='mobile'){
                $memberMobile = \MemberMobile::findFirst(array(
                    "member_id= :member_id: AND mobile = :mobile:",
                    "bind" => array('member_id'=>$member_id, 'mobile' => $data['target'])
                ));
                if(!$memberMobile){
                    $memberMobile = new \MemberMobile();
                    $memberMobile->member_id =$member_id;
                    $memberMobile->mobile = $data['target'];
                    $memberMobile->create();
                }

            }
            if($type=='email'){
                $memberEmail = \MemberEmail::findFirst(array(
                    "member_id= :member_id: AND email = :email:",
                    "bind" => array('member_id'=>$member_id, 'email' => $data['email'])
                ));
                if(!$memberEmail){
                    $memberEmail = new \MemberEmail();
                    $memberEmail->member_id =$member_id;
                    $memberEmail->email = $data['target'];
                    $memberEmail->create();
                }
            }

            $this->success('验证成功');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
