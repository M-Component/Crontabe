<?php

namespace Component\Mailer\Driver;
class Base extends \Phalcon\Mvc\User\Component{

    /**
     * 获取配置参数
     *
     * @param $key string
     * @param $pkey api interface class name
     * @return mixed
     */
    public function getConf($key=null, $pkey)
    {
        $val = \Setting::getConf($pkey);
        return $key ? $val[$key] : $val;
    }
}