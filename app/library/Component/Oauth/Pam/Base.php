<?php

namespace Component\Oauth\Pam;
use Component\Member\Pam;
use Phalcon\Exception;
use Phalcon\Mvc\User\Component;
class Base extends Component
{
    public function __construct()
    {

    }

    /**
     * 得到配置参数.
     *
     * @params string key
     * @payment api interface class name
     */
    protected function getConf($key, $pkey)
    {
        $val = \Setting::getConf($pkey);
        return $val[$key];
    }

    protected function doLogin($member_data ,$pam_data){
        $login_type = $pam_data['login_type'];
        $openid = $pam_data['openid'];
        $pam_member = $this ->pam_members ->findFirst(array('openid="'.$openid.'" AND login_type="'.$login_type.'"'));
        if($pam_member){
            $pam_data = $pam_member ->toArray();
        }else{
            $pam_data = $this->create_member($member_data,$pam_data);
        }
        $this ->auth ->saveLoginSession($pam_data);
        return $pam_data['members_id'];
    }

    /**
     * 创建会员
     */
    protected function create_member($member_data,$pam_data){
        $this->db->begin();
        try{
            $pam_data = \Utils::inputFilter($pam_data);
            $this ->pam ->create($pam_data ,$member_data);
            $this->db->commit();
        }catch (Exception $e){
            throw new Exception($e ->getMessage());
        }
        return $pam_data;
    }

}
