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
        // 在添加计划任务的时候，需要手动吸写入任务对应的类名,如果要指向类应该执行的方法是哪个可以写成 类名@方法名，默认的是exec
        $segments = explode('@', $job);
        return count($segments) > 1 ? $segments : array($segments[0], 'exec');
    }

    protected function resolve($class)
    {
        $class = "\\".$class;
        return new $class;
    }

}