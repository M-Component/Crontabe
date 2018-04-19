<?php

namespace Component\Oauth\Pam;

use Component\Oauth\OauthInterface;
use Phalcon\Exception;

final class Wechat extends Base implements OauthInterface
{
    public $login_type = 'wechat';
    public $name = '微信信任登录';
    public $version = '';

    public $platform = array(
        'pc', 'h5'
    );

    public function __construct()
    {
        parent::__construct();
        $base_url = \Utils::base_url();
        $this->callback_url = $base_url . '/openapi/oauth/callback/wechat';
    }

    /**
     * 后台配置参数设置.
     *
     * @param null
     *
     * @return array 配置参数列表
     */
    public function setting()
    {
        return array(
            'display_name' => array(
                'title' => '信任登录名称',
                'type' => 'text',
                'default' => '微信信任登录',
            ),
            'order_num' => array(
                'title' => '排序',
                'type' => 'number',
                'default' => 0,
            ),
            /*个性化字段开始*/
            'app_id' => array(
                'title' => 'APPID',
                'type' => 'text',
            ),
            'app_secret' => array(
                'title' => 'APPSECRET',
                'type' => 'text',
            ),
            'redirect_uri' => array(
                'title' => 'redirect_uri(回调地址)',
                'type' => 'textarea',
                'default' => $this->callback_url
            ),
            /*个性化字段结束*/
            'status' => array(
                'title' => '是否开启',
                'type' => 'radio',
                'options' => array(
                    'true' => '是',
                    'false' => '否',
                ),
                'default' => 'true',
            ),

        );
    }

    public function authorize_url()
    {
        $app_id = $this->getConf('app_id', 'Wechat');
        $app_secret = $this->getConf('app_secret', 'Wechat');
        $redirect_uri = urlencode($this->getConf('redirect_uri', 'Wechat'));
        //state=STATE 在前台会被跟踪替换成state=$forward;

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$app_id&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        //微信里
        if (\Utils::is_wechat()) {
            return $url;
        }
        //手机中，微信外
        if (\Utils::is_mobile()) {
            return false;
        }
        //适合扫码登录
        $url = "https://open.weixin.qq.com/connect/qrconnect?appid=$app_id&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
        return $url;
    }

    /**
     * 同步跳转处理.
     *
     * @see /applications/toauth/lib/api.php
     * @params array - 所有第三方回调参数，包括POST和GET
     */
    public function callback(&$params)
    {   // 如果用户同意授权，回调的时候会带上 code,state 这两个参数
        $code = $params['code'];
        $forward = $params['state']; //最终转向目标
        //获得token
        $token = $this->get_token($code, $error_msg);
        if ($error_msg) {
            die($error_msg);
        }
        // 如果用户取消授权 code 则不存在
        if (!$code){
            throw new Exception('用户取消授权');
        }

        //获得微信用户open资料
        $userinfo = $this->get_userinfo($token['access_token'], $token['openid'], $error_msg);
        if ($error_msg) {
            throw new Exception($error_msg);
        }
        $member_data = array(
            'avatar' => $userinfo['headimgurl'], //头像
            'name' => $userinfo['nickname'], //昵称
            'sex' => $userinfo['gender'] == '1' ? '2' : ($userinfo['gender'] == '2' ? '1' : '0'),
            'addr' => $userinfo['country'] . $userinfo['city'] . $userinfo['province'],
            'addon' => serialize($userinfo),//信任登录返回数据
        );
        $cur_time = time();
        $pam_data = array(
            'openid' => $userinfo['openid'],
            'login_account' => 'wx_' . substr(md5($userinfo['openid']), -5), //OPEN账号名
            'login_type' => $this->login_type,//登录类型
            'login_password' => $cur_time,//自动密码
            'password_account' => $userinfo['openid'],//用唯一openid ，微信unionid是跨应用唯一
            'createtime' => $cur_time
        );
        $member_id = $this->doLogin($member_data, $pam_data);
        if ($member_id) {
            $forward = $forward ? $forward : '/';
            if ($params['qrlp']) {
                $forward .= '?mid=' . $member_id . '&enc_str=' . $params['qrlp'];
            }
            $this->response->redirect($forward);
        } else {
            throw new Exception($error_msg);
        }
    }

    /**
     * 根据用户授权的code 获得access_token.
     */
    private function get_token($code, &$msg)
    {
        $app_id = $this->getConf('app_id', 'Wechat');
        $app_secret = $this->getConf('app_secret', 'Wechat');
        $action_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$app_id&secret=$app_secret&code=$code&grant_type=authorization_code";
        $res = \Utils::curl_client($action_url);
        $res = json_decode($res, 1);
        if ($res['errcode'] || !$res['access_token']) {
            $msg = 'access_token获取失败!' . $res['errmsg'];
            return false;
        }
        return $res;
    }

    /**
     * 根据access_token 或 openid 获得用户资料.
     *
     * 如果用户更新了图片，原有的图片地址将会失效
     */
    private function get_userinfo($token, $openid, &$msg)
    {
        $action_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
        $res = \Utils::curl_client($action_url);
        $res = json_decode($res, 1);
        if ($res['errcode'] || !$res['nickname']) {
            $msg = '用户信息获得失败!' . $res['errmsg'];
            return false;
        }
        return $res;
    }


    /**
     * 检查 access_token 是否失效
     */
    public function check_token($access_token, $openid, &$msg)
    {
        $action_url = "https://api.weixin.qq.com/sns/auth?access_token=$access_token&openid=$openid";
        $res = \Utils::curl_client($action_url);
        $res = json_decode($res,1);
        if ($res['errcode'] != 0){
            $msg = 'access_token已经失效,请从新获取';
            return false;
        }
        return true;
    }

}
