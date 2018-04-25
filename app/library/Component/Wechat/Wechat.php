<?php
namespace Component\Wechat;

use Phalcon\Mvc\User\Component;

class Wechat extends Component
{
    private $app_id;

    private $app_secret;

    private $token;

    private $key;

    public function __construct($config)
    {
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];
        $this->token = $config['token'];
        $this->key = $config['key'];

    }

    public function check_sign($signature, $timestamp, $nonce)
    {

        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        }
        return false;
    }

    public function decrypt_msg($str = '', $msg_signature, $timestamp, $nonce)
    {
        $pc = new WXBizMsgCrypt($this->token, $this->key, $this->app_id);
        $res = '';
        $errCode = $pc->decryptMsg($msg_signature, $timestamp, $nonce, $str, $res);
        if ($errCode == 0) {
            return $res;
        }
        //logger errCode
        return false;
    }

    public function encrypt_msg($str, $timestamp, $nonce)
    {

        $pc = new WXBizMsgCrypt($this->token, $this->key, $this->app_id);
        $res = '';
        $errCode = $pc->encryptMsg($str, $timestamp, $nonce, $res);
        if ($errCode == 0) {
            return $res;
        }
        return false;
    }

    // 构建开发者回复用户信息【xml格式】
    public function xml_build($data = array())
    {
        return XML::build($data);
    }

    public function xml_parse($xml_data = '')
    {
        return XML::parse($xml_data);
    }

    public function get_access_token($code = null)
    {
        $accessToken = new AccessToken($this->app_id, $this->app_secret, $this->fileCache);
        if ($code) {
            $res = $accessToken->getOauth2Token($code);
        } else {
            $res = $accessToken->getToken();
        }
        return $res;
    }

    public function get_userinfo($access_token, $openid)
    {
        $user = new User();
        return $user->getUserInfo($access_token, $openid);
    }

    public function refresh_access_token($refresh_token = null){
        $accessToken = new AccessToken($this->app_id, $this->app_secret, $this->fileCache);
        if ($refresh_token) {
            $res = $accessToken->refreshOauth2Token($refresh_token);
        } else {
            $res = $accessToken->refreshToken();
        }
        return $res;
    }
    public function get_Template_list()
    {
        $messgae_template = new MessageTemplate($this->get_access_token());
        $res = $messgae_template->getTemplateList();
        if(!$res){
            $error =$messgae_template->getError();
            if($error['code']=='40001'){
                $this->refresh_access_token();
            }
        }
        return $res;
    }
}
