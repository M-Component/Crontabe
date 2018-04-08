<?php
namespace Task\Crontab;
use Task\Output;
use Task\Process;
use Phalcon\Di;
use Exception;
use Task\Queue\Manager as Queue;
class Listener
{
    private $manager;

    private $cronWorker;

    private $table;

    /**自定义配置项目
     * @param null $config
     */
    public function __construct($config =null)
    {
        $di = DI::getDefault();
        $this->cache = $di->get('cache');

    }
    public function listen(){
        $this ->table = Table::init();
        $this ->manager = Manager::getInstance();
        $this ->cronWorker = Crontab::getInstance();
        $this ->loadTask();
        $this ->runTask();
    }

    public function loadTask(){
        //解析计划任务，将需要执行的任务，放入队列
        $process = new \swoole_process(function(\swoole_process $worker){
            if(PHP_OS != 'Darwin'){
                $worker->name("Seven_cron_task");
            }
            $this ->cronWorker ->loadTask($this ->manager ->getCrontab());
            //持续加载任务配置
            \swoole_timer_tick(60000,function()use($worker){
                $this ->checkMpid($worker);
                try{
                    $this->cronWorker->loadTask($this->manager ->getCrontab());
                }catch (Exception $e){
                    Output::stderr($e->getMessage());
                    $worker->exit(1);
                }
            });
        });
        if (!($pid = $process->start())) {
        }
        //记录当前任务
        Process::$task_list[$pid] = [
            "start"     => microtime(true),
            "type"      => "cron_task",
            "process"   => $process,
        ];
    }


    public function runTask(){
        //从任务内存表中获取要执行的任务
        \swoole_timer_tick(1000, function () {
            $tasks = $this->cronWorker->getTasks();
            if(!empty($tasks)){
                foreach($tasks as $task){
                    $process = new \swoole_process(function(\swoole_process $worker) use($task){
                        if(PHP_OS != 'Darwin'){
                            $worker->name("Seven_cron_worker");
                        }
                        try{
                            $this->cronWorker ->fire($task);
                        }catch (Exception $e){
                            Output::stderr($e->getMessage());
                        }
                    });
                    $pid = $process->start();
                    //记录当前任务
                    Process::$task_list[$pid] = [
                        "start"     => microtime(true),
                        "type"      => "cron_worker",
                        "process"   => $process,
                        "task"      => $task
                    ];
                }

            }
        });
    }

    /**
     * 判断主进程是否已经退出
     * @param $worker
     */
    public function checkMpid(&$worker){
        if(!\swoole_process::kill(Process::getMpid(),0)){
            $worker->exit();
            // 这句提示,实际是看不到的.需要写到日志中
            Output::stdout("Master process exited, child process [{$worker['pid']}] also quit") ;
        }
    }
}
