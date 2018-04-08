<?php

namespace Task\Queue;
use Task\Output;
use Task\Process;
use Phalcon\Di;
use Exception;
class Listener
{

    protected $maxProcess=5; // 最大进程数

    protected $runningProcess = 0; //运行中的进程数

    protected $sleep = 3;

    protected $connection ;

    protected $queue='default' ;

    protected $maxTries ;

    protected $memory =64;

    protected $delay =10;//执行失败的任务，延迟n秒后重试

    protected $timeout ;

    private $manager;

    private $queueWorker;

    /**自定义配置项目
     * @param null $config
     */
    public function __construct($config =null)
    {
        $di = DI::getDefault();
        $this->cache = $di->get('cache');
    }

    /**
     * 执行队列入口
     */
    public function listen()
    {
        $process =Di::getDefault()->get('config')->queue->process;
        $process->{$this->queue} = $this->maxProcess;

        foreach($process as $name=>$num){
            $maxProcess = $num ? $num : $this->maxProcess;
            for($i =0;$i <$maxProcess ;$i++){
                $this ->makeProcess($name ,$i);
                usleep(200000);
            }
        }
    }

    /**
     * 新开进程执行
     */
    public function makeProcess($name ,$num){
        $manager = new Manager();
        $manager->setAsGlobal();
        $this ->queueWorker = new Worker($manager);
        $process = new \swoole_process(function(\swoole_process $worker)use ($name,$num) {
            if(PHP_OS != 'Darwin'){
                $worker->name('Seven_queue_worker_'.$name.'_'.$num);
            }
            while(true){
                $this ->checkMpid($worker);
                $lastRestart = $this ->getLastQueueRestartTime();
                try{
                    echo '-';
                    $this ->queueWorker ->runJob(
                        $this->connection,
                        $name,
                        $this ->delay,
                        $this->sleep,
                        $this->maxTries
                    );
                }catch (Exception $e){
                    Output::stderr($e->getMessage());
                    sleep(2);
                }

                if ($this->memoryExceeded($this ->memory) ||$this ->queueShouldRestart($lastRestart)) {
                    $worker->exit(1);
                }
            }
        });
        if (!($pid = $process->start())) {
        }
        //记录当前任务
        Process::$task_list[$pid] = [
            "start"     => microtime(true),
            "type"      => "queue",
            "process"   => $process,
        ];
    }


    /**
     * 判断主进程是否已经退出
     * @param $worker
     */
    public function checkMpid(&$worker){
        if(!\swoole_process::kill(Process::getMpid(),0)){
            $worker->exit();
            // 这句提示,实际是看不到的.需要写到日志中
            echo "Master process exited, I [{$worker['pid']}] also quit\n";
        }
    }

    /**
     * 检测是否超出内存限制
     *
     * @param  int  $memoryLimit
     * @return bool
     */
    public function memoryExceeded($memoryLimit)
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }

    /**
     * @return int
     */
    public function getSleep()
    {
        return $this->sleep;
    }

    /**
     * @param  int  $sleep
     * @return void
     */
    public function setSleep($sleep)
    {
        $this->sleep = $sleep;
    }

    /**
     * 设定最大重试次数
     * @param  int  $tries
     * @return void
     */
    public function setMaxTries($tries)
    {
        $this->maxTries = $tries;
    }

    /**
     * 获取队列上次重启时间
     *
     * @return int|null
     */
    protected function getLastQueueRestartTime()
    {
        if ($this->cache) {
            return $this->cache->get('queue:restart');
        }
    }

    /**
     * 判断队列是否需要重启
     *
     * @param  int|null  $lastRestart
     * @return bool
     */
    protected function queueShouldRestart($lastRestart)
    {
        return $this->getLastQueueRestartTime() != $lastRestart;
    }
}
