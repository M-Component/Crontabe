<?php
namespace Pam;
use Phalcon\Di;

class Member
{
    private $db;

    private $auth;

    public function __construct(){
        $this->db = Di::getDefault()->getShared('db');
        $this->auth =Di::getDefault()->getShared('auth');
    }

    public function mobileLogin($mobile){
        $pamMember = \PamMember::findFirst(array(
            "login_account = :login_account:",
            "bind" => array('login_account' => $mobile)
        ));
        if (!$pamMember) {
            $member = new \Member();
            $pam_member = new \Pam\Member();
            $password = \Utils::generate_password();
            $member->username = $mobile;
            $member->login_password = $password;
            $member->reg_ip = $this->request->getClientAddress();

            $pamMember = new \PamMember();
            $pamMember->login_account = $mobile;
            $pamMember->login_type = 'mobile';
            $member->mobile = $mobile;

            $this->db->begin();
            if ($member->create() === false) {
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
        } else {
            $member = $pamMember->member;
        }
        $this->auth->saveLoginSession($member);
        return $member;
    }

    public function oauthLogin($userinfo ,$type){
        $memberOauth =\MemberOauth::findFirst(array(
            "open_id = :open_id: AND type= :type:",
            "bind" => array('open_id' => $userinfo['openid'] ,'type'=>$type)
        ));
        if(!$memberOauth){
            if($userinfo['unionid']){
                $unionOauth = \MemberOauth::findFirst(array(
                    "union_id = :union_id:",
                    "bind" => array('union_id' => $userinfo['unionid'])
                ));                    
            }

            $this->db->begin();

            if($unionOauth){
                $member =$unionOauth->member;
            }else{
                $member = new \Member();
                $password = \Utils::generate_password();
                $member->username = $userinfo['nickname'];
                $member->login_password = $password;
                $member->nickname = $userinfo['nickname'];
                $member->avatar = $userinfo['headimgurl'];
                $member->sex = $userinfo['sex'] ? :0;
                if ($member->create() === false) {
                    foreach ($member->getMessages() as $message) {
                        $this->db->rollback();
                        throw new \Exception($message);
                    }
                }
            }
            $memberOauth = new \MemberOauth();
            $memberOauth->open_id =$userinfo['openid'];
            $memberOauth->nickname =$userinfo['nickname'];
            $memberOauth->type =$type;
            $memberOauth->union_id =$userinfo['unionid']; 
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

    public function bindMember($open_id ,$type ,$login_account ,$login_type){
        $memberOauth =\MemberOauth::findFirst(array(
            "open_id = :open_id: AND type= :type:",
            "bind"=>array('open_id'=>$open_id ,'type'=>$type)
        ));

        if(!$memberOauth){
            throw new \Exception('无效的openid');
        }
        $member =$memberOauth->member;
        if(\PamMember::count("member_id={$member->id} AND login_type='$login_type'")){
            throw new \Exception('该账号绑定');
        }
        $pamMember = \PamMember::findFirst(array(
            "login_account = :login_account:",
            "bind" => array('login_account' => $login_account)
        ));
        $this->db->begin();
        if(!$pamMember){
            $pamMember =new \PamMember();
            $pamMember->login_account =$login_account;
            $pamMember->login_type =$login_type;
            $pamMember->member_id =$member->id;
            if ($pamMember->create() ===false) {
                foreach ($pamMember->getMessages() as $message) {
                    $this->db->rollback();
                    throw new \Exception($message);
                }
            }
            $member->username =$login_account;
            if($login_type =='mobile'){
                $member->mobile =$login_account;
            }
            if($login_type =='email'){
                $member->email =$login_account;
            }
            $member->save();
            $this->auth->saveLoginSession($member);
        }else{
            $memberOauth->member_id =$pamMember->member_id;
            $merge_member =$pamMember->member;
            $merge_member->nickname = $member->nickname;
            $merge_member->avatar = $member->avatar;
            $merge_member->sex = $member->sex;

            if ($member->delete()===false) {
                foreach ($member->getMessages() as $message) {
                    $this->db->rollback();
                    throw new \Exception($message);
                }
            }

            $memberOauth->save();
            if ($merge_member->save()===false) {
                foreach ($merge_member->getMessages() as $message) {
                    $this->db->rollback();
                    throw new \Exception($message);
                }
            }
            $this->auth->saveLoginSession($merge_member);
        }
        $this->db->commit();
        return $merge_member ? : $member;
    }
}
