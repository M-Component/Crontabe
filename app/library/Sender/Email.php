<?php
namespace Sender;
/**
 *  发送邮件
 */

class Email implements SenderInterface
{

    private $driver;

    public function __construct()
    {
        $mailer = new \Mailer();
        $drivers = $mailer->getAll(array('status'=>'true'));
        // 获取被启用的配置项
        foreach ($drivers as $k => &$v) {
            if ($v['status'] == 'true') {
                $this->driver = $mailer->getObj($v['driver_name']);
                break;
            }
        }
    }

    public function sendOne($target,$content,$title= '', $extend_params =null)
    {
        return $this->driver->send([$target],$content ,$title);
    }

    public function send(array $targets, $content,$title='' ,$extend_params =null)
    {
        foreach($targets as $target){
            $this->driver->send($target ,$content ,$title);
        }
    }

    public function sendList(array $target_contents)
    {
        foreach($target_contents as $v){
            $this->driver->send($v['target'] ,$v['content'] ,$v['title']);
        }
    }
}
