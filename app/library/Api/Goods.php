<?php
namespace Api;
class Goods extends Base
{
    public function subscribe(){
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

}
