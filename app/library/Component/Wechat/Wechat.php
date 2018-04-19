<?php
namespace Component\Wechat;

use Phalcon\Mvc\User\Component;

class Wechat
{
    private $app_id;

    private $app_secret;

    private $token;

    private $key;

    public function __construct($config){
        //parent::__construct();

        $this->app_id =$config['app_id'];
        $this->app_secret =$config['app_secret'];
        $this->token =$config['token'];
        $this->key =$config['key'];

    }

    public function check_sign($signature ,$timestamp ,$nonce)
	{
        		
		$tmpArr = array($this->token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}
        return false;
	}

    public function decrypt_msg($str ='' ,$msg_signature ,$timestamp ,$nonce){
        $pc = new WXBizMsgCrypt($this->token, $this->key, $this->app_id);
        $res = '';
        $errCode = $pc->decryptMsg($msg_signature ,$timestamp, $nonce , $str, $res);
        if ($errCode == 0) {
            return $res;
        }
        //logger errCode
        return false;
    }
    public function  encrypt_msg($str ,$timestamp ,$nonce){

        $pc = new WXBizMsgCrypt($this->token, $this->key, $this->app_id);
        $res = '';
        $errCode = $pc->encryptMsg($str, $timestamp, $nonce, $res);
        if ($errCode == 0) {
            return $res;
        }
        return false;
    }

    public function xml_build($data =array()){
        return XML::build($data);
    }

    public function xml_parse($xml_data =''){
        return XML::parse($xml_data);
    }

    public function get_access_token() {
        $app_id =$this->app_id;
        $app_secret =$this->app_secret;
        $key ='wechat_access_token_'.$app_id;
        if(!$access_token =$this->fileCache->get($key)){
            $access_token_action = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$app_id}&secret={$app_secret}";
            $res =\Utils::curl($access_token_action);
            $res = json_decode($res, 1);
            $access_token = $res['access_token'];
            if($access_token) {
                $this->fileCache->save($key , $res['expires_in']);
            }
        }
        return $access_token;
    }
}
