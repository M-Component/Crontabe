<?php

namespace Component\Oauth\Pam;
use Component\Oauth\OauthInterface;
use Phalcon\Exception;

final class Qq extends Base implements OauthInterface
{
    public $login_type = 'qq';
    public $name = 'QQ信任登录';
    public $version = '';

    public function __construct()
    {
        parent::__construct();
        $base_url = \Utils::base_url();
        $this->callback_url =$base_url.'/openapi/oauth/callback/qq';
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
                'title' => '信任登录名称' ,
                'type' => 'text',
                'default' => 'QQ信任登录',
            ) ,
            'order_num' => array(
                'title' => '排序' ,
                'type' => 'number',
                'default' => 0,
            ) ,
            /*个性化字段开始*/
            'app_id' => array(
                'title' => 'APP ID' ,
                'type' => 'text',
            ) ,
            'app_secret' => array(
                'title' => 'APP KEY' ,
                'type' => 'text',
            ) ,
            'redirect_uri'=>array(
                'title'=>'redirect_uri(回调地址)',
                'type'=>'textarea',
                'default'=>$this->callback_url,
                'readonly'=>'true'
            ),
            /*个性化字段结束*/
            'status' => array(
                'title' => '是否开启' ,
                'type' => 'radio',
                'options' => array(
                    'true' => '是' ,
                    'false' => '否' ,
                ) ,
                'default' => 'true',
            ),

        );
    }

    public function authorize_url()
    {
        $app_id = $this->getConf('app_id', 'Qq');
        $app_secret = $this->getConf('app_secret', 'Qq');
        $redirect_uri = urlencode($this->getConf('redirect_uri', 'Wechat'));
        //state=STATE 在前台会被跟踪替换成state=$forward;
        $url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=$app_id&redirect_uri=$redirect_uri&state=STATE";
        //手机中
        if (\Utils::is_mobile()) {
            $url.= '&display=mobile';
        }
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
        //获得token
        $token = $this->get_token($code, $error_msg);
        if ($error_msg) {
            throw new Exception($error_msg);
        }
        //获得用户open资料
        $userinfo = $this->get_userinfo($token['access_token'], null, $error_msg);
        if ($error_msg) {
            throw new Exception($error_msg);
        }
        $member_data =array(
            'avatar' => $userinfo['figureurl_qq_2'], //头像
            'name' => urldecode($userinfo['nickname']), //昵称
            'sex' => $userinfo['gender'] == '男' ? '1' : ($userinfo['gender'] == '女' ? '2' :'0'),
            'addon' => serialize($userinfo),//信任登录返回数据
        );
        $cur_time = time();
        $pam_data =array(
            'openid'=>$userinfo['openid'],
            'login_account' => 'qq_'.substr(md5($userinfo['openid']),-5), //OPEN账号名
            'login_type' => $this->login_type,//登录类型
            'login_password' => $cur_time,//自动密码
            'password_account' => $userinfo['openid'],//用唯一openid ，微信unionid是跨应用唯一
            'createtime' =>$cur_time
        );
        $member_id = $this->doLogin($member_data, $pam_data);
	    if ($member_id) {
            $forward =$forward ?$forward :'/';
            $this ->response ->redirect($forward);
        } else {
            throw new Exception($error_msg);
        }
    }

    /**
     * 根据用户授权的code 获得access_token.
     */
    private function get_token($code, &$msg)
    {
        $this->app_id = $app_id = $this->getConf('app_id', 'Qq');
        $app_secret = $this->getConf('app_secret', 'Qq');
        $action_url = "https://graph.qq.com/oauth2.0/token";
        $query = array(
            'grant_type' => 'authorization_code',
            'client_id' => $app_id,
            'client_secret' => $app_secret,
            'code' => $code,
            'redirect_uri' => $this->callback_url
        );
        $res = curl_post($action_url,$query);
	    parse_str($res,$res_arr);
        if (!$res_arr['access_token']) {
            $msg = 'access_token获取失败!'.$res_arr['errmsg'];
            return false;
        }
        return $res_arr;
    }
    /**
     * 根据access_token 或 openid 获得用户资料.
     */
    private function get_userinfo($token, $openid, &$msg)
    {
        $action_url = "https://graph.qq.com/oauth2.0/me?access_token=$token";
        $res = curl_get($action_url);
        if (strpos($res, "callback") !== false) {
            $lpos = strpos($res, "(");
            $rpos = strrpos($res, ")");
            $res = substr($res, $lpos + 1, $rpos - $lpos - 1);
            $res = json_decode($res,1);
            $openid = $res['openid'];
            if(!$openid){
                $msg = '获取授权用户信息失败';
                return false;
            }
        }
        $app_id = $this->app_id;
        $graph_url2 = "https://graph.qq.com/user/get_user_info?access_token=$token&oauth_consumer_key=$app_id&openid=$openid&format=json";
        $qq_user_info = curl_get($graph_url2);
        $qq_user_info_arr = json_decode($qq_user_info, 1);
        $qq_user_info_arr['openid'] = $openid;
        return $qq_user_info_arr;
    }

}
