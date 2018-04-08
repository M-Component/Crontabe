<?php

namespace Task\Queue\Jobs;

use Task\Resolve;

abstract class Job extends Resolve
{

    protected $queue;

    protected $deleted = false; //删除

    protected $released = false;//释放

    abstract public function fire();

    public function delete()
    {
        $this->deleted = true;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function release($delay = 0)
    {
        $this->released = true;
    }

    public function isReleased()
    {
        return $this->released;
    }

    public function isDeletedOrReleased()
    {
        return $this->isDeleted() || $this->isReleased();
    }


    abstract public function attempts();

    abstract public function getRawBody();

    public function failed()
    {
        $payload = json_decode($this->getRawBody(), true);

        list($class, $method) = $this->parseJob($payload['job']);

        $this->instance = $this->resolve($class);

        if (method_exists($this->instance, 'failed')) {
            $this->instance->failed($payload['data']);
        }
    }

    public function getName()
    {
        return json_decode($this->getRawBody(), true)['job'];
    }

    public function getQueue()
    {
        return $this->queue;
    }
}
