<?php
namespace Api;

use Component\Vcode;
use Member\Auth;
use Member\Message;

class Member extends Base
{
   // private $session_id;
    public function __construct()
    {
        $this->auth = new Auth();
    }

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

            $member->pamMember = $pamMember;
            if (false === $member->create()) {
                foreach ($member->getMessages() as $message) {
                    throw new \Exception($message);
                }
            }
            $member->updateMemberLoginCount($member->id);
            $this->auth->saveLoginSession($member->toArray());
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

            if (!$vcode->verify($data['username'], 'signin', $data['vcode'])) {
                throw new \Exception('验证码错误');
            }
            $pamMember = \PamMember::findFirst(array(
                "login_account = :login_account:",
                "bind" => array('login_account' => $data['username'])
            ));

            if (!$pamMember) {
                $member = new \Member();
                $pam_member = new \Pam\Member();
                $password = $pam_member->_generate_password();
                $member->username = $data['username'];
                $member->login_password = $password;
                $member->reg_ip = $this->request->getClientAddress();

                $pamMember = new \PamMember();
                $pamMember->login_account = $data['username'];
                $pamMember->login_type = 'mobile';
                $member->mobile = $data['username'];
                $member->pamMember = $pamMember;

                if ($member->create() === false) {
                    foreach ($member->getMessages() as $message) {
                        throw new \Exception($message);
                    }
                }
            }else{
                $member = $pamMember->member;
            }

            $member->updateMemberLoginCount($member->id);
            $this->auth->saveLoginSession($member->toArray());
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
            $member_data = $pamMember->checkLogin($data['username'], $data['login_password']);

            $member = new \Member();
            $member_data = $member_data->PamMember->toArray();
            $member->updateMemberLoginCount($member_data['member_id']);
            $this->auth->saveLoginSession($member_data);

            $this->success($member_data);

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

    //第三方登录绑定
    public function oauth()
    {

    }


    /**
     * @String 手机号码
     * @String 模版类型
     *
     * 发送短信
     */
    public function sendSms(){
        $data = $this->request->getPost();
        $vcode = new Vcode();
        try{
            if (!\Utils::isMobile($data['mobile'])) throw new \Exception('请输入有效的手机号码');
            $vcode->sendSms($data['mobile'],$data['type']);
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }
    }

    /**
     * @params String 邮箱地址
     * @params String  $type  Vcode|signin|signup 模版类型
     * @params String  $title 标题
     *
     * 发送邮件
     */
    public function sendEmail(){
        $data = $this->request->getPost();
        if ($data[''])

        $data['title'] = a;
        $vcode = new Vcode();
        try{
            if (!\Utils::isEmail($data['email'])) throw new \Exception('请输入有效的邮箱地址');
            $vcode->sendEmail($data['email'],$data['type']);
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }
    }

}
