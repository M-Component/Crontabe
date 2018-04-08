<?php
namespace Component\Account;
use Phalcon\Mvc\User\Component;
class Auth extends Component{
    public function  isLogin(){
        $account = $this ->session ->get('account');
        $account_expires =$this ->session ->get('account_expire');
        if(time()<$account_expires && $account){
            return $account['account_id'];
        }
        $this ->removeLoginSession();
        return false;
    }
    public function saveLoginSession($account_data)
    {
        $expire = 3600 *24* 30 +time();
        $this->session->set('account', array(
            'account_id' => $account_data['id'],
            'login_account' => $account_data['username'],
        ));

        $this ->session->set('account_expire',$expire);
        $this ->cookies->set('ACCOUNT_UID' ,$account_data['id'] ,$expire);
        $this ->cookies->set('ACCOUNT_UNAME' ,$account_data['username'] ,$expire);
    }

    public function removeLoginSession()
    {
        if ($this->cookies->has('ACCOUNT_RMU')) {
            $this->cookies->get('ACCOUNT_RMU')->delete();
        }
        if ($this->cookies->has('ACCOUNT_RMT')) {
            $this->cookies->get('ACCOUNT_RMT')->delete();
        }
        $this->session->remove('account');
        $this->session->remove('account_expire');
        $this->cookies->get('ACCOUNT_UID')->delete();
        $this->cookies->get('ACCOUNT_UNAME')->delete();
    }


    public function rememberMember($account_data)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($account_data['username'] . $account_data['login_password'] . $userAgent);
        $expire = time() + 86400 * 8;
        $this->cookies->set('ACCOUNT_RMU',$account_data['id'], $expire);
        $this->cookies->set('ACCOUNT_RMT', $token, $expire);
    }
}