<?php
namespace Component\MsgPush\Driver;

use Phalcon\Mvc\User\Component;

class Base extends Component
{
    /**
     * 获取配置参数
     *
     * @param $pkey string
     * @return mixed
     */
    public function getConf($pkey)
    {
        $val = \Setting::getConf($pkey);
        return $val;
    }
}