<?php

namespace Task\Queue;

abstract class Queue
{
    /**
     * 添加一组任务到队列
     *
     */
    public function bulk($jobs, $data = '', $queue = null)
    {
        foreach ((array) $jobs as $job) {
            $this->push($job, $data, $queue);
        }
    }

    protected function createPayload($job, $data = '', $queue = null)
    {
        return json_encode(array('job' =>$job ,'data'=>$data));
    }

}
