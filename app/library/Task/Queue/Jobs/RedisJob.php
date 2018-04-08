<?php

namespace Task\Queue\Jobs;

use Task\Queue\RedisQueue;

class RedisJob extends Job implements JobInterface
{
    protected $redis;

    protected $job;

    public function __construct(RedisQueue $redis, $job, $queue)
    {
        $this->job = $job;
        $this->redis = $redis;
        $this->queue = $queue;
    }

    public function fire()
    {
        $this->resolveAndFire(json_decode($this->getRawBody(), true));
    }

    public function getRawBody()
    {
        return $this->job;
    }

    public function delete()
    {
        parent::delete();

        $this->redis->deleteReserved($this->queue, $this->job);
    }

    public function release($delay = 0)
    {
        parent::release($delay);

        $this->delete();

        $this->redis->release($this->queue, $this->job, $delay, $this->attempts() + 1);
    }

    public function attempts()
    {
        $job = json_decode($this->job, true);
        return $job['attempts'];
    }

    public function getJobId()
    {
        $job = json_decode($this->job, true);
        return $job['id'];
    }

    public function getRedisQueue()
    {
        return $this->redis;
    }

    public function getRedisJob()
    {
        return $this->job;
    }
}
