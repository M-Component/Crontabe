<?php
namespace Component\Wechat;
class User{

    // 如果用户更新了头像，原有的图片地址将会失效
    public function getUserInfo($access_token ,$openid){
        $action_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $res = \Utils::curl_client($action_url);
        $res = json_decode($res, 1);
        if ($res['errcode'] || !$res['nickname']) {
            $msg = '用户信息获得失败!' . $res['errmsg'];
            return false;
        }
        return $res;
    }

    public function checkToken($access_token ,$openid){
        $action_url = "https://api.weixin.qq.com/sns/auth?access_token={$access_token}&openid={$openid}";
        $res = \Utils::curl_client($action_url);
        $res = json_decode($res,1);
        if ($res['errcode'] != 0){
            $msg = 'access_token已经失效,请从新获取';
            return false;
        }
        return true;
    }
}
