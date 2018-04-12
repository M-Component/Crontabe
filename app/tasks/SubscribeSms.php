<?php
use Phalcon\Di;
class SubscribeSms extends SubscribeMessageBase implements \Task\TaskInterface
{
    private $queue ='sms';

    /**
     * @param $params
     */
    public function exec($job =null ,$params = null)
    {
        $messages = $this->getMessage($this->queue);
    }
}
