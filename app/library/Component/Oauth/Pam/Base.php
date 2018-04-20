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

    public function oauth($userinfo ,$type){

        $memberOauth =\MemberOauth::findFirst(array(
            "open_id = :open_id: AND type= :type:",
            "bind" => array('open_id' => $userinfo['openid'] ,'type'=>$type)
        ));
        if(!$memberOauth){
            $member = new \Member();

            $password = \Utils::generate_password();
            $member->username = $userinfo['nickname'];
            $member->login_password = $password;
            $member->reg_ip = $this->request->getClientAddress();

            $member->nickname = $userinfo['nickname'];
            $member->avatar = $userinfo['headimgurl'];
            $member->sex = $userinfo['sex'] ? :0;

            $memberOauth = new \MemberOauth();
            $memberOauth->open_id =$userinfo['openid'];
            $memberOauth->nickname =$userinfo['nickname'];
            $memberOauth->type = $type;
            $memberOauth->union_id =$userinfo['unionid'];

            $this->db->begin();
            if ($member->create() === false) {
                foreach ($member->getMessages() as $message) {
                    $this->db->rollback();
                    throw new \Exception($message);
                }
            }
            $memberOauth->member_id =$member->id;
            if ($memberOauth->create() ===false) {
                foreach ($memberOauth->getMessages() as $message) {
                    $this->db->rollback();
                    throw new \Exception($message);
                }
            }
            $this->db->commit();
        }else{
            $member =$memberOauth->member;
        }
        $this->auth->saveLoginSession($member);
        return $member;
    }
}
