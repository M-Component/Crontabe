<?php

namespace Task\Queue;
use Closure;
class QueueManager
{
    public $app;

    protected $connections = [];

    protected $connectors = [];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function before($callback)
    {

    }


    public function after($callback)
    {

    }

    public function failing($callback)
    {

    }

    public function stopping($callback)
    {

    }

    public function connected($name = null)
    {
        return isset($this->connections[$name ?: $this->getDefaultDriver()]);
    }

    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();
        if (! isset($this->connections[$name])) {
            $this->connections[$name] = $this->resolve($name);
        }
        return $this->connections[$name];
    }

    public function addConnector($driver, Closure $resolver)
    {
        $this->connectors[$driver] = $resolver;
    }

    protected function resolve($name)
    {
        $config = $this->getConfig($name);
        return $this->getConnector($config['driver'])->connect($config);
    }

    protected function getConnector($driver)
    {
        if (isset($this->connectors[$driver])) {
            return call_user_func($this->connectors[$driver]);
        }
    }


    protected function getConfig($name)
    {
        if ($name === null || $name === 'null') {
            return ['driver' => 'null'];
        }
        return $this->app['config']["connections"][$name];
    }


    public function getDefaultDriver()
    {
        return $this->app['config']['default'];
    }

    public function setDefaultDriver($name)
    {
        $this->app['config']['default'] = $name;
    }

    public function getName($connection = null)
    {
        return $connection ?: $this->getDefaultDriver();
    }

    public function __call($method, $parameters)
    {
        $callable = [$this->connection(), $method];

        return call_user_func_array($callable, $parameters);
    }
}
