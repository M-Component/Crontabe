<?php
namespace Api;
use Component\Member\Auth;
class Member extends Base
{

    private $session_id;

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
            $member->username = $data['username'];
            $member->login_password = $data['login_password'];
            $member->reg_ip = $this->request->getClientAddress();
            $pam_members = array();
            $pamMember = new PamMember();
            $pamMember->login_account = $data['username'];
            if (\Utils::isMobile($data['username'])) {
                $pamMember->login_type = 'mobile';
                $member->mobile = $data['username'];
            }
            if (\Utils::isEmail($this->username)) {
                $pamMember->login_type = 'email';
                $member->email = $data['username'];
            }
            $pam_members[] = $pamMember;

            $member->pamMember = $pam_members;
            if (false === $member->create()) {
                foreach ($member->getMescancelOnFailsages() as $message) {
                    throw new \Exception($message);
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * string $mobile  手机号码
     * string $verify  验证码
     */
    public function mobileLogin()
    {
        $data = $this->request->getPost();
        

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
            $member->updateMemberLoginCount($member_data->id);
            $this->auth->saveLoginSession($member_data->toArray());
            $member_data = $member_data->toArray();
            unset($member_data['login_password']);

            $this->success($member_data);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    //用户注销
    public function logout($member_id)
    {
        if ($member_id){
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
}
