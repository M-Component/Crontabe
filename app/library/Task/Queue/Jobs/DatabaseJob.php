<?php
namespace Task\Queue\Jobs;
use Task\Queue\DatabaseQueue;
use Phalcon\Di;
class DatabaseJob extends Job implements JobInterface
{

    protected $database;

    protected $job;

    public function __construct(DatabaseQueue $database, $job, $queue)
    {
        $this->job = $job;
        $this->queue = $queue;
        $this->database = $database;
        $this->job->attempts = $this->job->attempts + 1;
        $this->logger = DI::getDefault()->get('logger');
    }

    public function fire()
    {
        $this->resolveAndFire(json_decode($this->job->payload, true));
    }

    public function delete()
    {
        parent::delete();

        return $this->database->deleteReserved($this->queue, $this->job);
    }

    public function release($delay = 0)
    {
        parent::release($delay);

        if(false && $this->delete()){
            $this->logger->info('release success'.$this->job->id);
            $job_id = $this->database->release($this->queue, $this->job, $delay);
            $this->logger->info('release new job'.$job_id);
            return $job_id;
        }
        //调整为不删除，仅延迟
        if($this->database->release($this->queue, $this->job, $delay)){
            return $this->job;
        }
        $this->logger->error('release error'.$this->job->id);

    }

    public function attempts()
    {
        return (int) $this->job->attempts;
    }

    public function getJobId()
    {
        return $this->job->id;
    }

    public function getRawBody()
    {
        return $this->job->payload;
    }

    public function getDatabaseQueue()
    {
        return $this->database;
    }
    public function getDatabaseJob()
    {
        return $this->job;
    }
}
