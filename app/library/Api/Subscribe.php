<?php
namespace Api;
use Mvc\Paginator\Adapter\Collection as PaginatorCollectionBuilder;
class Subscribe extends Base
{
    // 订阅
    public function setSubscribe(){
        $this->checkLogin();
        $member_id =$this->member['member_id'];
        $data =$this->request->getPost();
        try {
            $validation = new \Validation\Subscribe();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }
            $subscribe = new \Member\Subscribe();
            $res =$subscribe->saveSubscribe($member_id ,$data['goods_id'] ,$data);
            $this->success($res);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // 获取订阅
    public function getSubscribe($goods_id){
        $this->checkLogin();
        $member_id =$this->member['member_id'];
        $data =\Subscribe::findFirst(array(
            'goods_id'=>(string)$goods_id,
            'member_id'=>(int)$member_id
        ));
        $this->success($data);
    }

    // 获取订阅列表
    public function getList(){
        $this->checkLogin();
        $member_id =$this->member['member_id'];
        $limit =$this->request->getQuery('limit',array('int')) ? :10;
        $page =$this->request->getQuery('page',array('int')) ? : 1;
        $status =$this->request->getQuery('status') ? : 0;
        $tag = $this->request->getQuery('tag' ,array('trim' ,'addslashes'));
        
        $columns =array(
            //            'goods_id'=>true,
        );
        $condition=array(
            'member_id'=>(int)$member_id
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

    // 设置通知
    public function setNotice(){
        $this->checkLogin();
        $data = $this->request->getPost();
        $vcode = new \Component\Vcode();
        $member_id =$this->member['member_id'];
        try {
            if (!$vcode->verify($data['target'], 'vcode', $data['vcode'])) {
                throw new \Exception('验证码错误');
            }
            $type = $data['type'];
            if($type=='mobile'){
                $obj =new \MemberMobile();
                $obj->batchUpdate(['default'=>0] ,'member_id='.$member_id);
                $memberMobile = \MemberMobile::findFirst(array(
                    "member_id= :member_id: AND mobile = :mobile:",
                    "bind" => array('member_id'=>$member_id, 'mobile' => $data['target'])
                ));
                if(!$memberMobile){
                    $memberMobile = new \MemberMobile();
                    $memberMobile->member_id =$member_id;
                    $memberMobile->mobile = $data['target'];
                    if(!$memberMobile->create()){
                        foreach($memberMobile ->getMessages() as $v){
                            throw new \Exception($v);
                        }
                    }
                }
                $memberMobile->default =1;
                $memberMobile->save();

            }
            if($type=='email'){
                $obj =new \MemberEmail();
                $obj->batchUpdate(['default'=>0] ,'member_id='.$member_id);
                $memberEmail = \MemberEmail::findFirst(array(
                    "member_id= :member_id: AND email = :email:",
                    "bind" => array('member_id'=>$member_id, 'email' => $data['email'])
                ));
                if(!$memberEmail){
                    $memberEmail = new \MemberEmail();
                    $memberEmail->member_id =$member_id;
                    $memberEmail->email = $data['target'];
                }
                $memberEmail->default =1;
                $memberEmail->save();

            }

            $this->success('验证成功');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // 获取通知
    public function getNotice(){
        $this->checkLogin();
        $member_id =$this->member['member_id'];
        $mobiles =\MemberMobile::find('member_id='.$member_id);
        $emails =\MemberEmail::find('member_id='.$member_id);
        $wechat =\MemberOauth::findFirst('type="wechat" AND member_id='.$member_id);
        $device =\MemberDevice::findFirst('member_id='.$member_id); //?需要考虑是否只有一个
        $res =[
            'mobiles'=>[],
            'emails'=>[],
            'default_mobile'=>$this->member['mobile'],
            'default_email'=>$this->member['email'],
            'open_id'=>null,
            'registration_id'=>null
        ];
        foreach($mobiles as $v){
            $res['mobiles'][] =$v->mobile;
            if($v->default){
                $res['default_mobile'] =$v->mobile;
            }
        }
        foreach($emails as $v){
            $res['emails'][] =$v->email;
            if($v->default){
                $res['default_email'] =$v->email;
            }
        }
        if($wechat){
            $res['open_id']  =$wechat->open_id;            
        }
        if($device){
            $res['registration_id']  =$device->registration_id;
        }
        $this->success($res);

    }
}
