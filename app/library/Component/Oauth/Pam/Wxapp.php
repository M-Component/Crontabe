<?php

namespace Component\Oauth\Pam;
use Component\Oauth\OauthInterface;
use Phalcon\Exception;

final class Wxapp extends Base implements OauthInterface
{
    public $login_type = 'wxapp';
    public $name = 'APP微信信任登录';
    public $version = '';

    public function __construct()
    {
        parent::__construct();

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
                'default' => 'APP微信信任登录',
           ) ,
            'order_num' => array(
                'title' => '排序' ,
                'type' => 'number',
                'default' => 0,
            ) ,
            /*个性化字段开始*/
            'app_id' => array(
                'title' => 'APPID' ,
                'type' => 'text',
            ) ,
            'app_secret' => array(
                'title' => 'APPSECRET' ,
                'type' => 'text',
            ) ,
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

    public function authorize_url(){
        
    }

    public function callback(&$params){
        
    }
}
