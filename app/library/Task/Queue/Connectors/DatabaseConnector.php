<?php

namespace Task\Queue\Connectors;
use Task\Queue\DatabaseQueue;

class DatabaseConnector implements ConnectorInterface
{

    protected $database;

    protected $connection;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function connect(array $config)
    {
        $queue = new DatabaseQueue($this ->database ,$config );
        $queue->setExpire($config['expire']);
        return $queue;
    }
}
