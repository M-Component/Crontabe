<?php
namespace Task\Crontab;
use Task\Output;
use Task\Resolve;
use Task\Process;
class Crontab extends Resolve{
    const RunStatusError = -1;//不符合条件，不运行
    const RunStatusWait = 0;//未运行
    const RunStatusStart = 1;//运行中
    const RunStatusSuccess = 4;//运行成功
    const RunStatusFailed = 5;//运行失败

    private $expire = 300;

    private static $worker;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if(!self::$worker){
            self::$worker = new Crontab();
        }
        return self::$worker;
    }

    public function loadTask($crontab){
        if(!empty($crontab)){
            $i=0;
            foreach($crontab as $id=>$task){
                $current = time();
                $time = $current;
//                $time = $task['last_time'] ;
                $res = ParseCrontab::parse($task["rule"], $current);
                if ($res === false) {
                    Output::stderr(ParseCrontab::$error);
                } elseif (!empty($res)) {
                    foreach ($res as $sec){
                        $unique_id = $this ->get_unique_id($i);
                        $i++;
                        $cron = json_encode($task);
                        $data = array(
                            'unique_id'=>$unique_id,
                            "createTime"=>$time,
                            "sec"=>$time+$sec,
                            "cron"=>$cron,
                            "id"=>$id,
                            "unique"=>$task['unique'] ? : 0,
                            "runStatus"=>self::RunStatusWait,
                            'job'=>$task['job']
                        );
                        Table::setTask($unique_id ,$data);
                    }
                }
            }
        }
        $this ->clean();
        return true;
    }


    /**
     * 获取当前可以执行的任务
     * @return array
     */
    public function getTasks()
    {
        if (count(Table::getTasks()) <= 0) {
            return false;
        }
        foreach (Table::getTasks() as $k => $task) {
            if(isset($task['unique']) && $task['unique']){
                if (isset(Process::$unique_task_list[$task["id"]]) && (Process::$unique_task_list[$task["id"]] >= $task["unique"])) {
                    continue;
                }
            }
            //考虑是否限制在几秒的误差，主要是初次执行延迟1秒
            if ((time() > $task["sec"]) && ($task["runStatus"] == self::RunStatusWait)) {
                $data[$k] = $task;
                Process::$unique_task_list[$task["id"]]++;
            }
        }
        return $data;
    }

    /**
     * 执行任务
     * @param $task
     */
    public function fire($task){
        $cron = json_decode($task["cron"] ,1);
        $payload = array(
            'job' =>$cron['job'],
            'data' =>$cron['data'],
        );
        //开始执行
        $manager = Manager::getInstance();
        $manager ->start($cron);
        $task['runStatus'] = self::RunStatusStart;
        Table::setTask($task['unique_id'] ,$task);
        $this ->resolveAndFire($payload);
        //执行完成
        $task['runStatus'] = self::RunStatusSuccess;
        Table::setTask($task['unique_id'] ,$task);
        $manager ->finish($cron);
    }

    /**
     * 清理已经执行过的任务
     */
    public function clean(){
        $finish_ids = array();
        $expire_ids = array();
        if (count(Table::getTasks()) > 0){
            $current= time();
            foreach (Table::getTasks() as $id=>$task){
                if ($task["runStatus"] == self::RunStatusSuccess || $task["runStatus"] == self::RunStatusFailed){
                    $finish_ids[] = $id;
                }else{
                    if ($current - $task["createTime"]>$this->getExpire()){
                        $ids[] = $id;
                        if ($task["runStatus"] == self::RunStatusStart|| $task["runStatus"] == self::RunStatusError){
                            $expire_ids[] = $task["id"];
                        }
                    }
                }

            }
        }
        //删除
        foreach ($finish_ids as $id){
            Table::delTask($id);
        }
        //TODO 超时操作
    }


    public function getExpire(){
        return $this ->expire;
    }

    public function setExpire($expire){
        $this ->expire = $expire;
    }

    private function get_unique_id($unique_id){
        $now = (int)(microtime(true)*1000);
        return $now.$unique_id;
    }


}
