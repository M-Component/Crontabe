<?php
namespace Api;
class Member  extends Base{

    private $session_id;

    //用户注册
    public function signup(){
        $data =$this->request->getPost();
        try{
            $validation = new \Validation\Member\Signup();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }
            $member = new \Member();
            $member->username =$data['username'];
            $member->login_password =$data['login_password'];
            $member->reg_ip =$this->request->getClientAddress();
            $pam_members =array();
            $pamMember = new PamMember();
            $pamMember->login_account =$data['username'];
            if(\Utils::isMobile($data['username'])){
                $pamMember->login_type ='mobile';
                $member->mobile =$data['username'];
            }
            if(\Utils::isEmail($this->username)){
                $pamMember->login_type ='email';
                $member->email =$data['username'];
            }
            $pam_members[] =$pamMember;

            $member->pamMember =$pam_members;
            if(false === $member->create()){
                foreach($member->getMessages() as $message){
                    throw new \Exception($message);
                }
            }
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }
    }

    //用户登录
    public function signin(){
        
    }

    //用户注销
    public function logout($member_id){
        
    }

    //用户更新
    public function update(){
        
    }

    //第三方登录绑定
    public function oauth(){
        
    }
}
