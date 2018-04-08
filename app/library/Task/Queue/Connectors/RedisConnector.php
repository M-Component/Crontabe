<?php

namespace Task\Queue\Connectors;
use Task\Queue\RedisQueue;

class RedisConnector implements ConnectorInterface
{
    protected $redis;


    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function connect(array $config)
    {
        $queue = new RedisQueue($this->redis, $config);
        $queue->setExpire($config['expire']);
        return $queue;
    }
}
