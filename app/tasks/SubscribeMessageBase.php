<?php
use Phalcon\Di;
class SubscribeMessageBase
{
    protected $queue_list =array(
        'sms',
        'email',
        'wechat',
        'app'
    );

    protected $redisDb;

    public function __construct(){
        $this->redisDb =Di::getDefault()->getShared('redisDb');
        $this->messageSender =Di::getDefault()->get('messageSender');
    }

    //从队列中读取可以发送的消息
    public function getMessage($queue){
        $queue ='subscribe_'.$queue;

        $limit =100; //限制每次发送消息的最大数量
        $start = $total =0;
        $messages =array();
        $delay =array();
        $max =1000;  //限制每次取出数据的最大数量

        while($total<$limit && $start<$max && $job_raw = $this->RedisDb->lpop($queue)){
            $start++;

            $job = json_decode($job_raw,1);
            $time =time();
            $from =strtotime(date('Y-m-d '.$job['from']));
            $to =strtotime(date('Y-m-d '.$job['to']));

            if($time<$from || $time >$to){
                $job['available_time'] = $from;
                $delay[] =array(
                    'score'=>$from,
                    'value'=>json_encode($job),
                );
                continue;
            }
            $messages[] =$job;

            $total ++;            
        }
        $this->redisDb->zaddArray($queue.':delay');

        //批量请求发送api
        return $messages;
    }


    //从zset中读取可以发送的消息，移入队列
    public function migrateMessage()
    {
        $queue_list = $this->queue_list;
        $time =time();
        $this->redisDb =Di::getDefault()->getShared('redisDb');
        foreach($queue_list as $queue){
            $queue = 'subscribe_'.$queue;
            $from =$queue.':delay';

            $jobs= $this->redisDb->zrangebyscore($from, '-inf', $time);
            //加入队列头部
            if(!empty($jobs)){
                $this->redisDb->zremrangebyscore($from, '-inf', $time);
                $this->redisDb->lpushArray($queue ,$jobs);
            }
            
        }

        return true;
    }
}
