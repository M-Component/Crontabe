<?php

namespace Task\Queue;
use Illuminate\Support\Facades\File;
use Phalcon\Di;
use Task\Queue\Connectors\DatabaseConnector;
use Task\Queue\Connectors\RedisConnector;
class Manager
{

    protected $manager;

    protected $container;

    protected static $instance;

    public function __construct()
    {
        $config = Di::getDefault() ->get('config');
        $default= $config ->queue ->default;
        $this->container['config']['default'] =$default;
        $this->container['config']['connections']= $config ->queue ->connections->toArray();
        $this->manager = new QueueManager($this->container);
        $this->registerConnectors();
        $this->setAsGlobal();
    }


    public static function connection($connection = null)
    {
        return static::$instance->getConnection($connection);
    }

    public static function push($job, $data = '', $queue = null, $connection = null ,$sort =0)
    {
        return static::$instance->connection($connection)->push($job, $data, $queue ,$sort);
    }

    public static function bulk($jobs, $data = '', $queue = null, $connection = null)
    {
        return static::$instance->connection($connection)->bulk($jobs, $data, $queue);
    }

    public static function later($delay, $job, $data = '', $queue = null, $connection = null)
    {
        return static::$instance->connection($connection)->later($delay, $job, $data, $queue);
    }


    public function getConnection($name = null)
    {
        return $this->manager->connection($name);
    }

    public function addConnection(array $config, $name = 'default')
    {
        $this->container['config']["connections"][$name] = $config;
    }

    public function getQueueManager()
    {
        return $this->manager;
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->manager, $method], $parameters);
    }

    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([static::connection(), $method], $parameters);
    }

    public function setAsGlobal()
    {
        static::$instance = $this;
    }

    public function registerConnectors()
    {
        $di = Di::getDefault();
        $this->manager->addConnector('database', function ()use ($di)  {
            return new DatabaseConnector($di->get('db'));
        });

        $this->manager->addConnector('redis', function () use ($di) {
            return new RedisConnector($di->get('redisDb'));
        });
    }
}
