<?php

namespace Component\Oauth\Pam;
use Member\Pam;
use Phalcon\Exception;
use Phalcon\Mvc\User\Component;
class Base extends Component
{
    public function __construct()
    {

    }

    /**
     * 得到配置参数.
     *
     * @params string key
     * @payment api interface class name
     */
    protected function getConf($key, $pkey)
    {
        $val = \Setting::getConf($pkey);
        return $val[$key];
    }

    public function oauth($userinfo ,$type){
        $pam =new Pam();
        return $pam->oauthLogin($userinfo ,$type);
    }
}
