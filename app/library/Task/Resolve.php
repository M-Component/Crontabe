<?php
namespace Task;

class  Resolve{
    protected $instance;

    //TODO 消费
    protected function resolveAndFire(array $payload)
    {
        list($class, $method) = $this->parseJob($payload['job']);
        $this->instance = $this->resolve($class);
        if($this->instance instanceof TaskInterface){
            $this->instance->{$method}($this, $payload['data']);
        }else{
            //队列执行类定义不正确
            Output::stderr('队列执行类不合法');
        }

    }

    protected function parseJob($job)
    {
        $segments = explode('@', $job);
        return count($segments) > 1 ? $segments : array($segments[0], 'exec');
    }

    protected function resolve($class)
    {
        $class = "\\".$class;
        return new $class;
    }

}