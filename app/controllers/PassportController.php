<?php
use Component\Captcha;
use Component\Account\Auth;
class PassportController extends ControllerBase{
    public function initialize(){
        $this ->auth = new Auth();
    }
    public function loginAction(){
        if($this ->request->isPost()){
            $password = $this->request->getPost('password' ,'trim'  );
            $login_account = $this->request->getPost('username', array('string', 'striptags' ,'trim'));
            $verify_code = $this->request->getPost('verify_code' ,'trim');
            $login_account= strtolower($login_account);
            $pam_account  = new \Pam\Account();
            try{
                $captcha = new Captcha();
                if(!$captcha ->check($verify_code)){
                    throw new \Phalcon\Exception('您输入的验证码有误');
                }
                $account = $pam_account ->checkLogin($login_account ,$password);
                $pam_account->updateAccountLoginInfo($account['id']);
                $this ->auth ->saveLoginSession($account);
                $this ->response->redirect($this ->getForward());
            }catch (\Phalcon\Exception $e){
                $this ->flash ->error($e ->getMessage());
            }

        }
        $this ->checkLogin();
        $this ->view->forward = urlencode($this->getForward());
    }

    public function logoutAction(){
        $this ->auth->removeLoginSession();
        $this->response->redirect( 'passport/login' );
    }

    public function captchaAction(){
        $captcha = new Captcha();
        $captcha->fontSize = 30;
        $captcha->length   = 4;
        $captcha->useNoise = false;
        $captcha ->create();
    }

    private function checkLogin(){
        if($this->auth ->isLogin()){
            $this->response->redirect($this ->getForward());
        }
    }

    private function getForward()
    {
        $forward = $this->request->getQuery('forward');
        if (!$forward) {
            $forward = $this ->request->getHTTPReferer();
        }
        if (strpos($forward, 'passport')) {
            $forward = $this->url->get('index');//?
        }
        return $forward;
    }
}
