<?php
use Phalcon\Di;
class SubscribeEmail extends SubscribeMessageBase implements \Task\TaskInterface
{
    private $queue ='email';

    /**
     * @param $params
     */
    public function exec($job =null ,$params = null)
    {
        $messages = $this->getMessage($this->queue);
    }
}
