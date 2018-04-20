<?php
namespace Component\Wechat;

use Phalcon\Mvc\User\Component;

class Wechat extends Component
{
    private $app_id;

    private $app_secret;

    private $token;

    private $key;

    public function __construct($config){
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

    public function get_access_token($code =null) {
        $accessToken = new AccessToken($this->app_id ,$this->app_secret,$this->fileCache);
        if($code){
            $res = $accessToken->getOauth2Token($code);
        }else{
            $res = $accessToken->getToken();
        }
    }
}
