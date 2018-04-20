<?php

namespace Component\Oauth\Pam;

use Component\Oauth\OauthInterface;
use Phalcon\Exception;

final class Wechat extends Base implements OauthInterface
{
    public $login_type = 'wechat';
    public $name = '微信公众号信任登录';
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
                'default' => '微信公众号信任登录',
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
            'token' => array(
                'title' => 'Token',
                'type' => 'text',
                'default' => ''
            ),
            'key' => array(
                'title' => 'EncodingAESKey',
                'type' => 'text',
                'default' => ''
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
        if (\Utils::is_wechat()) {
            return $url;
        }

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
    {
        $code = $params['code'];
        $forward = $params['state']; //最终转向目标

        if (!$code){
            throw new Exception('用户取消授权');
        }
        //获得微信用户open资料
        $userinfo = $this->get_userinfo($code);
        // 创建用户
        $member = $this->oauth($userinfo,$this->login_type);
        if ($member) {
            $forward = $forward ? $forward : '/';
            if ($params['qrlp']) {
                $forward .= '?mid=' . $member->id . '&enc_str=' . $params['qrlp'];
            }
            $this->response->redirect($forward);
        } else {
            throw new \Exception('授权登录失败');
        }
    }

   // 根据用户授权的code 获得access_token.
    private function get_userinfo($code)
    {
        $conf = $this->getConf(null,'Wechat');
        $wechat = new \Component\Wechat\Wechat($conf);
        $access_token = $wechat->get_access_token($code);
        if (!$access_token['access_token']){
            throw new \Exception('access_token 获取失败');
        }
        $userinfo = $wechat->get_userinfo($access_token['access_token'],$access_token['openid']);
        if (!$userinfo){
            throw new \Exception('用户信息获取失败');
        }
        return $userinfo;
    }


}
