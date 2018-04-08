<?php
namespace Task;

use Phalcon\CLI\Console;

class ConsoleApp extends Console
{
    private $options = "hdrmp:s:l:c:";
    private $longopts = [
        "help",
        "daemon",
        "reload",
        "config:",
        "queue:",
        "cron:",
        "checktime:",
    ];

    private $help = <<<HELP
  帮助信息:
  Usage: /path/to/php task.php [options] -- [args...]

  -h [--help]               显示帮助信息
  -s start                  启动进程
  -s stop                   停止进程
  -s restart                重启进程
  -d [--daemon]             是否后台运行
  -r [--reload]             重新载入配置文件
  --queue=[true|false]      执行队列
  --cron=[true|false]       执行计划任务
  --checktime=[true|false]  精确对时,仅对Crontab有效(精确对时,程序则会延时到分钟开始0秒启动)


HELP;

    public function __construct()
    {
        $this->config =  $this->di->getConfig();
        Process::$pid_path = $this->config->application->logDir;
    }

    /**
     * 运行入口
     */
    public function run()
    {
        $opt = getopt($this->options, $this->longopts);
        $this->params_help($opt);
        $this->params_daemon($opt);
        $this->params_queue($opt);
        $this->params_cron($opt);
        $this->params_checktime($opt);
        $this->params_s($opt);
    }

    /**
     * 解析帮助参数
     * @param $opt
     */
    public function params_help($opt)
    {
        if (empty($opt) || isset($opt["h"]) || isset($opt["help"])) {
            die($this->help);
        }
    }

    /**
     * 解析运行模式参数
     * @param $opt
     */
    public function params_daemon($opt)
    {
        if (isset($opt["d"]) || isset($opt["daemon"])) {
            Process::$daemon = true;
        }
    }

    /**
     * 解析精确对时参数
     * @param $opt
     */
    public function params_checktime($opt)
    {
        if (isset($opt["checktime"]) && $opt["checktime"] == "false") {
            Process::$checktime = false;
        }
    }


    /**
     * 解析启动模式参数
     * @param $opt
     */
    public function params_s($opt)
    {
        //判断传入了s参数但是值，则提示错误
        $allow = array("start", "stop", "restart");
        if ((isset($opt["s"]) && !$opt["s"]) || (isset($opt["s"]) && !in_array($opt["s"],$allow))) {
            Output::stdout("Please run: path/to/php task.php -s [start|stop|restart]");
        }

        if (isset($opt["s"]) && in_array($opt["s"], $allow)) {
            switch ($opt["s"]) {
                case "start":
                    Process::start();
                    break;
                case "stop":
                    Process::stop();
                    break;
                case "restart":
                    Process::restart();
                    break;
            }
        }
    }

    /**
     * queue参数
     *
     * @param $opt
     */
    public function params_queue($opt)
    {
        if (isset($opt["queue"])) {

            Process::$queue = true;
        }
    }

    public function params_cron($opt)
    {
        if (isset($opt["cron"])) {
            Process::$cron = true;
        }
    }
}
