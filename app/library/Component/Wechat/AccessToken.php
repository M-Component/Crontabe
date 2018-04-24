<?php
namespace Component\Wechat;
class AccessToken{
	private $appId;
    private $appSecret;
    private $cache;
    private $cache_key='';

    public function __construct($appId ,$appSecret ,$cache){
        $this->appId =$appId ;
        $this->appSecret =$appSecret;
        $this->cache =$cache;
        $this->cache_access_token = 'wechat_access_token_'.$appId;
        $this->cache_oauth2_access_token = 'wechat_oauth2_access_token_'.$appId;
    }


    //普通access_token
    public function getToken(){

        //并发覆盖问题
        if(!$access_token =$this->cache->get($this->cache_access_token)){
            $action_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}";
            $res =\Utils::curl_client($action_url);
            $res = json_decode($res, 1);
            if ($res['errcode'] || !$res['access_token']) {
                $msg = 'access_token获取失败!' . $res['errmsg'];
                //logger
                return false;
            }
            $this->cache->save($this->cache_access_token , $res['access_token'], $res['expires_in']);
            $access_token = $res['access_token'];
        }
        return $access_token;
    }

    //网页授权access_token
    public function getOauth2Token($code){
	$oauth2Token =$this->cache->get($this->cache_oauth2_access_token); 
        if(!$oauth2Token['access_token']){
            $action_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appId}&secret={$this->appSecret}&code={$code}&grant_type=authorization_code";
            $res = \Utils::curl_client($action_url);
            $res = json_decode($res, 1);
            if ($res['errcode'] || !$res['access_token']) {
                $msg = 'access_token获取失败!' . $res['errmsg'];
                //logger
                return false;
            }
            $oauth2Token =$res;
            $oauth2Token['expires_time'] =time()+ $oauth2Token['expires_in'];
            $this->cache->save($this->cache_oauth2_access_token ,$oauth2Token ,3600*24*30);
        }
        if($oauth2Token['expires_time']>= time()){
            $oauth2Token = $this->refreshToken($oauth2Token['refresh_token']);
        }
        return $oauth2Token;
    }

    //刷新网页授权token
    public function refreshToken($refresh_token){
        $action_url ="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$this->appId}&grant_type=refresh_token&refresh_token={$refresh_token}";
        $res = \Utils::curl_client($action_url);
        $res = json_decode($res, 1);
        if ($res['errcode'] || !$res['access_token']) {
            $msg = 'access_token刷新失败!' . $res['errmsg'];
            //logger
            return false;
        }
        $res['expires_time'] =time()+ $res['expires_in'];
        $this->cache->save($this->cache_oauth2_access_token ,$res);
        return $res;
    }
}
