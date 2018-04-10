<?php

namespace Task\Queue;
use Phalcon\Di;
use Task\Queue\Jobs\RedisJob;

class RedisQueue extends Queue implements QueueInterface
{

    protected $redis;

    protected $default;

    protected $expire = null;

    public function __construct($redis, $config)
    {
        $this->redis = $redis;
        $this->default = $config['queue'];
    }

    public function push($job, $data = '', $queue = null,$sort=0)
    {
        return $this->pushRaw($this->createPayload($job, $data ,null), $queue);
    }

    public function pushRaw($payload, $queue = null, array $options = [])
    {
        $this->redis->rPush($this->getQueue($queue), $payload);

        $payload = json_decode($payload, true);
        return $payload['id'];
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
        $payload = $this->createPayload($job, $data);

        $this->redis->zAdd($this->getQueue($queue).':delayed', time() + $delay, $payload);
        $payload = json_decode($payload, true);
        return $payload['id'];
    }

    public function pop($queue = null)
    {
        $original = $queue ?: $this->default;

        $queue = $this->getQueue($queue);

        if (! is_null($this->expire)) {
            $this->migrateAllExpiredJobs($queue);
        }

        $job =$this->redis->lPop($queue);
        if ($job) {
            $this->redis->zAdd($queue.':reserved', time() + $this->expire, $job);
            return new RedisJob( $this, $job, $original);
        }
    }

    public function release($queue, $payload, $delay, $attempts)
    {
        $payload = json_decode($payload, true);
        $payload['attempts'] = $attempts;
        $payload = json_encode($payload);
        $this->redis->zAdd($this->getQueue($queue).':delayed', time() + $delay, $payload);
        return true;
    }

    public function deleteReserved($queue, $job)
    {
        $this->redis->zRem($this->getQueue($queue).':reserved', $job);
        return true;
    }


    protected function migrateAllExpiredJobs($queue)
    {
        $this->migrateExpiredJobs($queue.':delayed', $queue);

        $this->migrateExpiredJobs($queue.':reserved', $queue);
    }

    public function migrateExpiredJobs($from, $to)
    {
        $time =  time();
        $jobs = $this->getExpiredJobs($from,$time);
        $this->redis->multi();
        if (!empty($jobs)) {
            $this->removeExpiredJobs($from, $time);
            $this->pushExpiredJobsOntoNewQueue( $to, $jobs);
        }
        $this->redis->exec();
    }

    protected function getExpiredJobs( $from, $time)
    {
        return $this->redis->zRangeByScore($from, '-inf', $time);
    }

    protected function removeExpiredJobs($from, $time)
    {
        $this->redis->zRemRangeByScore($from, '-inf', $time);
    }


    protected function pushExpiredJobsOntoNewQueue($to, $jobs)
    {
        foreach($jobs as $job){
            $this->redis->rPush($to, $job);
        }
    }


    protected function createPayload($job, $data = '', $queue = null)
    {
        $payload = array(
            'job' =>$job,
            'data' =>$data,
            'id' =>$this->getRandomId(),
            'attempts' =>1,
            'create_time'=>time()
        );
        return json_encode($payload);
    }


    protected function getRandomId()
    {
        return \Utils::random(32);
    }


    protected function getQueue($queue)
    {
        return 'queues:'.($queue ?: $this->default);
    }

    public function getRedis()
    {
        return $this->redis;
    }

    public function getExpire()
    {
        return $this->expire;
    }

    public function setExpire($seconds)
    {
        $this->expire = $seconds;
    }
}
