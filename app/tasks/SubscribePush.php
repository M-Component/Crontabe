<?php
use Phalcon\Di;
class SubscribePush implements \Task\TaskInterface
{
    /**
     * @param $params
     */
    public function exec($job =null ,$params = null)
    {
        $this->redisDb =Di::getDefault()->getShared('redisDb');

        $it = null;
        $limit =10000;
        $this->redisDb->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);
        while($goods = $this->redisDb->sScan('subscribe_goods_set', $it ,'',$limit)) {
            //推送数据到cdh
        }
        return true;
    }
}
