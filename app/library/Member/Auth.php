<?php
namespace Member;
use Phalcon\Mvc\User\Component;
class Auth extends Component{
    public function  isLogin(){
        $member = $this ->session ->get('member');
        $member_expires =$this ->session ->get('member_expire');
        if(time()<$member_expires && $member){
            $this->updateExpireTime();
            return $member['member_id'];
        }
        $this ->removeLoginSession();
        return false;
    }
    public function saveLoginSession($member_data)
    {
        $expire = time() +3600*24*30;
        $this ->cookies->set('UID' ,$member_data['id'] ,$expire);
        $this ->cookies->set('UNAME' ,$member_data['username'] ,$expire);

        $this->session->set('member', array(
            'member_id' => $member_data['id'],
            'login_account' => $member_data['username'],
        ));

        $this->updateExpireTime();
    }

    public function updateExpireTime()
    {
        $expire = 3600 +time();
        if($_SERVER['HTTP_X_REQUESTED_ISAPP']){
            $expire=3600*24*365+time();
        }
        $this ->session->set('member_expire',$expire);
    }


    public function removeLoginSession()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }
        $this->session->remove('member');
        $this->session->remove('member_expire');
        $this->cookies->get('UID')->delete();
        $this->cookies->get('UNAME')->delete();
    }


    public function rememberMember($member_data)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($member_data['username'] . $member_data['login_password'] . $userAgent);
        $expire = time() + 86400 * 8;
        $this->cookies->set('RMU',$member_data['id'], $expire);
        $this->cookies->set('RMT', $token, $expire);
    }
}
