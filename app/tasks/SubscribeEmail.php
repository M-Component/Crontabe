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
        foreach($messages as $k=>$v){
            $messages[$k]=array(
                'target' =>$v['target'],
                'template'=>$v['rule'],
                'params' =>$v
            );
        }
        $this->messageSender->setMsgType('email')->batchSend($messages);
    }
}
