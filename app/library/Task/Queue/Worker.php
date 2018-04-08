<?php

namespace Task\Queue;

use Exception;
use Task\Output;
use Task\Queue\Jobs\JobInterface as Job;
use Phalcon\Di;
class Worker
{
    protected $manager;

    protected $failer;

    protected $cache;

    public function __construct(Manager $manager, $failer = null)
    {
        $this->failer = $failer;
        $this->manager = $manager->getQueueManager();
    }


    /**
     * 运行任务
     *
     * @param  string  $connectionName
     * @param  string  $queue
     * @param  int  $delay
     * @param  int  $sleep
     * @param  int  $maxTries
     * @return void
     */
    public function runJob($connectionName, $queue, $delay, $sleep, $maxTries)
    {
        try {
            $this->pop($connectionName, $queue, $delay, $sleep, $maxTries);
        } catch (Exception $e) {
            throw new Exception($e ->getMessage());
        }
    }


    /**
     * 取出一个任务执行
     *
     * @param  string  $connectionName
     * @param  string  $queue
     * @param  int     $delay
     * @param  int     $sleep
     * @param  int     $maxTries
     * @return array
     */
    public function pop($connectionName, $queue = null, $delay = 0, $sleep = 3, $maxTries = 0)
    {
        $connection = $this->manager->connection($connectionName);

        $job = $this->getNextJob($connection, $queue);

        // 如果没有任务，暂停几秒继续
        if (! is_null($job)) {
            return $this->process(
                $this->manager->getName($connectionName), $job, $maxTries, $delay
            );
        }

        $this->sleep($sleep);

        return array('job' => null, 'failed' => false);
    }

    /**
     * 获取下一个任务
     */
    protected function getNextJob($connection, $queue)
    {
        if (is_null($queue)) {
            return $connection->pop();
        }

        foreach (explode(',', $queue) as $queue) {
            if (! is_null($job = $connection->pop($queue))) {
                return $job;
            }
        }
    }

    /**
     * 执行队列任务
     */
    public function process($connection, Job $job, $maxTries = 0, $delay = 0)
    {
        if ($maxTries > 0 && $job->attempts() > $maxTries) {
            //失败次数过多，目前暂时不处理，继续重试
            //return $this->logFailedJob($connection, $job);
        }
        try {
            $job->fire();
            if (! $job->isDeletedOrReleased()) {
                $job->delete();
            }
            return array('job' => $job, 'failed' => false);
        } catch (Exception $e) {
            if (! $job->isDeleted()) {
                $job->release($delay);
            }
            throw new Exception($e ->getMessage());
        }
    }

    /**
     * 记录失败的任务
     */
    protected function logFailedJob($connection, Job $job)
    {
        if ($this->failer) {
            $this->failer->log($connection, $job->getQueue(), $job->getRawBody());

            $job->delete();

            $job->failed();
        }

        return array('job' => $job, 'failed' => true);
    }

    /**
     * 判断是否超出内存限制
     *
     * @param  int   $memoryLimit
     * @return bool
     */
    public function memoryExceeded($memoryLimit)
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }

    /**
     * 停止
     *
     * @return void
     */
    public function stop()
    {
        die;
    }

    /**
     * 暂停几秒继续
     *
     * @param  int   $seconds
     * @return void
     */
    public function sleep($seconds)
    {
        sleep($seconds);
    }



    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager(QueueManager $manager)
    {
        $this->manager = $manager;
    }
}
