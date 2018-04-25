<?php
namespace Api;
use Jenssegers\Agent\Agent;
class Device extends Base
{
    public function setDevice()
    {
        $data =$this->request->getPost();
        try{
            //判断请求来源
            if(!$data['registration_id']){
                throw new \Exception('registration_id 不能为空');
            }
            $device =\MemberDevice::findFirst(array(
                "registration_id=:id:" ,
                'bind'=>array('id'=>$data['registration_id'])
            ));
            if(!$device){
                $device = new \MemberDevice();
                $device ->registration_id =$data['registration_id'];
            }

            $agent = new Agent();
            $device ->platform =$agent->platform();
            $device ->platform_version =$agent->version($device->platform);

            $device ->device =$agent->device();
            $device ->device_version =$agent->version($device->device);
            if($this->member['member_id']){
                $device->member_id =$this->member['member_id'];
            }
            if(false === $device ->save()){
                foreach ($device->getMessages() as $message) {
                    throw new \Exception($message);
                }
                throw new \Exception('保存失败');
            }
            $this->success('Success');
        }catch(\Exception $e){
            $this->error($e ->getMessage());
        }
    }
}
