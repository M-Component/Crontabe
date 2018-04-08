<?php

namespace Task\Queue;

interface QueueInterface
{
    /**
     * 添加一个新任务到队列
     *
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @param  int  $sort
     * @return mixed
     */
    public function push($job, $data = '', $queue = null ,$sort = 0);

    /**
     *
     * @param  string  $payload
     * @param  string  $queue
     * @param  array   $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = []);

    /**
     * 延迟添加一个新任务到队列
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null);


    /**
     * 从队列中取出下一个任务.
     *
     * @param  string  $queue
     */
    public function pop($queue = null);
}
