<?php
use Phalcon\Di;
class SubscribeApp extends SubscribeMessageBase implements \Task\TaskInterface
{
    private $queue ='app';

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
        $this->messageSender->setMsgType('app')->send($messages);
    }
}
