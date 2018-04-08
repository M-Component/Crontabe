<?php

namespace Task\Queue;

use Phalcon\Di;
use Task\Queue\Jobs\DatabaseJob;

class DatabaseQueue extends Queue implements QueueInterface
{

    protected $database;
    protected $table= 'jobs';
    protected $model_name ='Jobs';

    protected $model;

    protected $default; //队列默认名称

    protected $expire = null;//有效期秒

    public function __construct($database ,$config)
    {
        $this->database = $database;
        $this->table = $config['table'] ? : $this->table ;
        $this->model_name = "\\".ucfirst($this->table);
        $this->model = new $this->model_name();
        $this->default = $config['queue'];
        $this ->db = DI::getDefault()->get('db');
        $this ->logger = DI::getDefault()->get('logger');
    }

    public function push($job, $data = '', $queue = null,$sort=0)
    {
        return $this->pushToDatabase(0, $queue, $this->createPayload($job, $data) ,0 ,$sort);
    }

    public function pushRaw($payload, $queue = null, array $options = [])
    {
        return $this->pushToDatabase(0, $queue, $payload);
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->pushToDatabase($delay, $queue, $this->createPayload($job, $data));
    }

    public function bulk($jobs, $data = '', $queue = null)
    {
        $queue = $this->getQueue($queue);

        $availableAt = $this->getAvailableAt(0);

        $records = array_map(function ($job) use ($queue, $data, $availableAt) {
            return $this->buildDatabaseRecord(
                $queue, $this->createPayload($job, $data), $availableAt
            );
        }, (array) $jobs);
        $mdl = new $this->model_name();
        return $mdl->batchCreate($records);
    }


    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);
        if (! is_null($this->expire)) {
            //不设置超时
            //            $this->updateJobs($queue);
        }

        if (($job = $this->getNextAvailableJob($queue))) {
            echo '='.$job->id.'=';
            return new DatabaseJob(
                $this, $job, $queue
            );
        }
    }

    public function release($queue, $job, $delay)
    {
        //仅延迟，不删除
        $job->available_time = time()+$delay;
        $job->reserved=0;
        $job->thread_id =-1;
        return $job->save();
        //return $this->pushToDatabase($delay, $queue, $job->payload, $job->attempts,$job->sort);
    }


    protected function updateJobs($queue)
    {
        $expired = time() -$this->expire;
        $model_manager = $this ->model ->getModelsManager();
        $where = ' queue=?0 and reserved=?1 and reserved_time<?2';
        $table = ucfirst($this->table);
        $phql = "UPDATE {$table} SET reserved=0,reserved_time=null,attempts=attempts+1 WHERE {$where}";
        $model_manager->executeQuery(
            $phql,
            array(
                '0' =>$this->getQueue($queue),
                '1' =>1,
                '2' =>$expired
            )
        );
    }

    protected function getNextAvailableJob($queue)
    {
        $table = $this->table;
        $time = time();
        $queue_name = $this->getQueue($queue);
        $sql = 'UPDATE '.$table.' force index(PRIMARY) SET thread_id=GREATEST(CONNECTION_ID() ,(@msgID:=id)*0),reserved=1,reserved_time='.$time.' WHERE queue=\''.$queue_name.'\' and reserved=0 AND available_time<'.$time.' order by sort desc ,id LIMIT 1;';
        $this->db->execute($sql);
        if($this->db->affectedRows() && ($row = $this->db->fetchOne('SELECT id FROM '.$table.' WHERE id=@msgID')) ){
            $mdl = new $this->model_name();
            $job = $mdl->findFirst($row['id']);
        }
        return $job ? $job :null;
    }

    public function deleteReserved($queue, $job)
    {
        if(!$job->delete ()) {
            $this->logger->error('job:'.$job->id.':删除失败');
            foreach ($job->getMessages() as $message) {
                $this ->logger->error ('job delete error:'.$message->getMessage());
            }
            return false;
        }
        return true;
    }


    protected function getAvailableAt($delay)
    {
        return time() + $delay;
    }

    public function getExpire()
    {
        return $this->expire;
    }

    public function setExpire($seconds)
    {
        $this->expire = $seconds;
    }

    protected function pushToDatabase($delay, $queue, $payload, $attempts = 0 ,$sort=0)
    {
        $data = $this->buildDatabaseRecord(
            $this->getQueue($queue), $payload, $this->getAvailableAt($delay), $attempts,$sort
        );
        $mdl = new $this->model_name();
        $mdl ->save($data);
        return $mdl ->id;
    }

    protected function buildDatabaseRecord($queue, $payload, $availableAt, $attempts = 0,$sort=0)
    {
        return array(
            'queue' => $queue,
            'payload' => $payload,
            'attempts' => $attempts,
            'reserved' => 0,
            'reserved_time' => null,
            'available_time' => $availableAt,
            'sort'=>$sort,
            'create_time' => time(),
            'thread_id'=>-1
        );
    }

    protected function getQueue($queue)
    {
        return $queue ?: $this->default;
    }
}
