<?php
namespace Api;

use Component\Vcode;
use Pam\Member as Pam;

class Member extends Base
{
    // private $session_id;

    //用户注册
    public function signup()
    {
        $data = $this->request->getPost();
        try {
            $validation = new \Validation\Member\Signup();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }
            $pamMember = \PamMember::findFirst(array(
                "login_account = :login_account:",
                "bind" => array('login_account' => $data['username'])
            ));
            if ($pamMember) {
                throw new \Exception('该用户名已存在，不能重复');
            }

            $vcode = new \Component\Vcode();
            if (!$vcode->verify($data['username'], 'signin', $data['vcode'])) {
                throw new \Exception('验证码错误');
            }
            $member = new \Member();
            $pamMember = new \PamMember();

            $member->username = $data['username'];
            $member->login_password = $data['login_password'];
            $member->reg_ip = $this->request->getClientAddress();

            $pamMember->login_account = $data['username'];
            if (\Utils::isMobile($data['username'])) {
                $pamMember->login_type = 'mobile';
                $member->mobile = $data['username'];
            }
            if (\Utils::isEmail($data['username'])) {
                $pamMember->login_type = 'email';
                $member->email = $data['username'];
            }
            $this->db->begin();
            if (false === $member->create()) {
                foreach ($member->getMessages() as $message) {
                    $this->db->rollback();
                    throw new \Exception($message);
                }
            }
            $pamMember->member_id =$member->id;
            if (false === $pamMember->create()) {
                foreach ($pamMember->getMessages() as $message) {
                    $this->db->rollback();
                    throw new \Exception($message);
                }
            }
            $this->db->commit();
            $this->auth->saveLoginSession($member);
            $this->success($member);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }


    //用户登录
    public function signin()
    {
        $data = $this->request->getPost();
        try {
            $validation = new \Validation\Member\Signin();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }

            $pamMember = new \PamMember();
            $member = $pamMember->checkLogin($data['username'], $data['login_password']);
            $this->auth->saveLoginSession($member);
            $this->success($member);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }



    /**
     * string $mobile  手机号码
     * string $vcode   短信验证码
     */
    public function mobileLogin()
    {
        $data = $this->request->getPost();

        try {
            $validation = new \Validation\Member\Mobile();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }

            $vcode = new \Component\Vcode();
            if (!$vcode->verify($data['username'], 'vcode', $data['vcode'])) {
                throw new \Exception('验证码错误');
            }
            $pam =new Pam();
            $member =$pam ->mobileLogin($data['username']);
            $this->success($member);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }


    }

    //用户注销
    public function logout($member_id)
    {
        if ($member_id) {
            $this->auth->removeLoginSession();
        }
        $this->error('操作失败');
    }

    //用户更新
    public function update()
    {

    }

    //第三方登录
    public function oauth()
    {
        $data =$this->request->getPost();
        try{
            $validation = new \Validation\Member\Oauth();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }

            $userinfo =$data['userinfo'];
            $type =$data['type'];
            $userinfo = json_decode($userinfo,1);
            $pam = new Pam();
            $member = $pam->oauthLogin($userinfo ,$type);
            $this->success($member);

        }catch(\Exception  $e){
            $this->error($e->getMessage());
        }
    }

    //绑定手机号
    public function bind(){
        $data =$this->request->getPost();
        try{
            $validation = new \Validation\Member\Bind();
            $messages = $validation->validate($data);
            if (count($messages)) {
                foreach ($messages as $message) {
                    throw new \Exception($message);
                }
            }
            $vcode = new \Component\Vcode();
            if (!$vcode->verify($data['username'], 'vcode', $data['vcode'])) {
                throw new \Exception('验证码错误');
            }
            $pam =new Pam();
            $member =$pam->bindMember($data['openid'],$data['type'],$data['username'] ,'mobile');
            $this->success($member);
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }
    }

    /**
     * @params String  $type  Vcode|signin|signup 模版类型
     */
    public function sendSmsVcode()
    {
        $data = $this->request->getPost();
        //?验证消息类型
        try {
            if (!\Utils::isMobile($data['mobile'])) throw new \Exception('请输入有效的手机号码');

            $template = $data['template'];

            $this->messageSender->setMsgType('sms')->setTemplate($template)->sendVcode(
                array(
                    'target' => $data['mobile']
                )
            );
            $this->success('发送成功');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @params String  $type  Vcode|signin|signup 模版类型
     */
    public function sendEmailVcode()
    {
        $data = $this->request->getPost();
        $vcode = new Vcode();
        try {
            if (!\Utils::isEmail($data['email'])) throw new \Exception('请输入有效的邮箱地址');
            $template = $data['template'];
            $this->messageSender->setMsgType('email')->setTemplate($template)->sendVcode(
                array(
                    'target' => $data['email'],
                )
            );
            $this->success('发送成功');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
