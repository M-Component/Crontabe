<?php
namespace Task;
use Phalcon\Di;
use Task\Queue\Listener as Queue;
use Task\Crontab\Listener as Crontab;
class Process
{
    static public $process_name = "Seven_Task_Master"; // 进程名称
    static public $pid_path;                    // pid文件位置
    static public $log_path;                    // 日志文件位置
    static public $daemon = false;              // 运行模式
    static private $pid;                        // pid
    static public $checktime = true;            // 精确对时
    static public $task_list = [];
    static public $queue = false;
    static public $cron = false;
    static public $unique_task_list =array();
    static public $delay = [];
    static public $listeners = array(
        'queue' =>'队列',
        'cron' =>'计划任务'
    );


    /**
     * 启动
     */
    static public function start()
    {
        if (!self::$queue && !self::$cron) {
            die("请选择执行Queue或Cron!\n");
        }
        if (self::$queue && self::queue_status()) {
            die(self::$listeners['queue']."已启动!\n");
        }
        if (self::$cron && self::cron_status()) {
            die(self::$listeners['cron']."已启动!\n");
        }
        self::$process_name .= self::$queue ? '_Queue' : '';
        self::$process_name .= self::$cron ? '_Cron' : '';
        self::daemon();
        self::set_process_name();
        self::run();
    }

    /**
     * 重启
     */
    static public function restart()
    {
        self::stop(false);
        sleep(1);
        self::start();
    }

    /**
     * 停止进程
     * @param bool $output
     */
    static public function stop($output = true)
    {
        if (!self::$queue && !self::$cron) {
            die("请选择执行Queue或Cron!\n");
        }
        foreach(self::$listeners as $listener =>$name){
            if($listener=='queue' && !self::$queue){
                continue;
            }
            if($listener=='cron' && !self::$cron){
                continue;
            }
            $pid = @file_get_contents(self::get_pid_file($listener));
            if ($pid) {
                if (\swoole_process::kill($pid, 0)) {
                    \swoole_process::kill($pid, SIGTERM);
                    Output::stderr($name."进程" . $pid . "已结束");
                } else {
                    self::unlink_pid($listener);
                    Output::stderr($name."进程" . $pid . "不存在,删除pid文件");
                }
            }
        }
    }

    static public function status(){
        if(self::queue_status()){
            Output::stdout(self::$listeners['queue']."已经启动");
        }
        if(self::cron_status()){
            Output::stdout(self::$listeners['cron']."已经启动");
        }
    }


    static public function queue_status(){
        $queue_pid = @file_get_contents(self::get_pid_file('queue'));
        if($queue_pid && \swoole_process::kill($queue_pid,0)){
            return true;
        }
        return false;
    }

    static public function cron_status(){
        $queue_pid = @file_get_contents(self::get_pid_file('cron'));
        if($queue_pid && \swoole_process::kill($queue_pid,0)){
            return true;
        }
        return false;
    }
    /**
     * 运行
     */
    static protected function run()
    {
        self::get_pid();
        self::register_signal();
        //开启queue
        if (self::$queue) {
            self::write_pid('queue');
            Output::stdout(self::$listeners['queue']."启动成功");
            self::listenQueue();
        }
        if(self::$cron){
            self::write_pid('cron');
            if (self::$checktime) {
                $run = true;
                Output::stdout("正在启动".self::$listeners['cron']."...");
                while ($run) {
                    $s = date("s");
                    if ($s == 0) {
                        Output::stdout(self::$listeners['cron']."启动成功");
                        self::listenCron();
                        $run = false;
                    } else {
                        Output::stdout("启动倒计时 " . (60 - $s) . " 秒");
                        sleep(1);
                    }
                }
            } else {
                Output::stdout(self::$listeners['cron']."启动成功");
                self::listenCron();
            }
        }
    }

    /**
     * 监听队列
     */
    public static function listenQueue(){
        $listener = new Queue();
        $listener->listen();
    }

    /**
     * 监听计划任务
     */
    public static function listenCron(){
        $listener = new Crontab();
        $listener->listen();
    }

    /**
     * 主进程id
     * @return mixed
     */
    public static function getMpid(){
        return self::$pid;
    }


    /**
     * 匹配运行模式
     */
    static private function daemon()
    {
        if (self::$daemon) {
            \swoole_process::daemon();
        }
    }

    /**
     *  获取当前进程的pid
     */
    static private function get_pid()
    {
        if (!function_exists("posix_getpid")) {
            self::exit2p("Please install posix extension.");
        }
        self::$pid = posix_getpid();
    }

    /**
     * 写入当前进程的pid到pid文件
     */
    static private function write_pid($listener)
    {
        file_put_contents(self::get_pid_file($listener), self::$pid);
    }

    static private function unlink_pid($listener)
    {
        @unlink(self::get_pid_file($listener));
    }

    static private function get_pid_file($listener)
    {
        return self::$pid_path.$listener.'.pid';
    }

    /**
     * 设置进程名
     */
    static private function set_process_name()
    {
        if (!function_exists("swoole_set_process_name")) {
            self::exit2p("Please install swoole extension.http://www.swoole.com/");
        }
        //mac下不能修改
        if (PHP_OS != 'Darwin') {
            \swoole_set_process_name(self::$process_name);
        }
    }

    /**
     * 退出进程口
     * @param $msg
     */
    static private function exit2p($msg)
    {
        if(self::$queue){
            //退出queue进程
            self::unlink_pid('queue');
        }
        if(self::$cron){
            //退出cron_task ,cron_worker 进程
            self::unlink_pid('cron');
        }
        Output::stdout($msg . "\n");
        exit();
    }

    /**
     * 注册信号
     */
    static private function register_signal()
    {
        \swoole_process::signal(SIGTERM, function ($signo) {
            //kill 命令
            self::exit2p("接收到Kill退出操作信号");
        });
        \swoole_process::signal(SIGCHLD, function ($signo) {
            while ($ret = \swoole_process::wait(false)) {
                $pid = $ret['pid'];
                Output::stdout("{$pid} [child process  exit]");
                if (isset(self::$task_list[$pid])) {
                    $task = self::$task_list[$pid];
                    unset(self::$task_list[$pid]);
                    $start = $task["start"];
                    $end = microtime(true);

                    if ($task["type"] == "cron_worker") {
                        $task["process"]->close();
                        Output::stdout("{$pid} [Cron Job Runtime:" . sprintf("%0.6f", $end - $start) . "]");
                        $task_info = $task['task'];
                        if (isset(self::$unique_task_list[$task_info['id']]) && self::$unique_task_list[$task_info['id']] > 0) {
                            self::$unique_task_list[$task_info['id']]--;
                        }

                    }
                    if ($task["type"] == "cron_task") {
                        Output::stdout("{$pid} [Cron Task Runtime:" . sprintf("%0.6f", $end - $start) . "]");

                        $cron_task = $task["process"];
                        \swoole_timer_after(60000 ,function()use($cron_task){
                            $new_pid = $cron_task->start();
                            self::$task_list[$new_pid] = [
                                "start"     => microtime(true),
                                "type"      => "cron_task",
                                "process"   => $cron_task,
                            ];
                        });
                    }
                    if ($task["type"] == "queue") {
                        Output::stdout("{$pid} [Queue Runtime:" . sprintf("%0.6f", $end - $start) . "]");
                        $new_pid = $task["process"]->start();
                        self::$task_list[$new_pid] = [
                            "start"     => microtime(true),
                            "type"      => "queue",
                            "process"   => $task["process"],
                        ];
                    }
                }
            };
        });
        \swoole_process::signal(SIGUSR1, function ($signo) {
            //TODO something
        });
    }
}
