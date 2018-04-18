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
        $this->drivers = $mailer->getAll();
        // 获取被启用的配置项
        foreach ($drivers as $k => &$v) {
            if ($v['status'] == 'true') {
                $this->driver = $v['mailer_name'];
                break;
            }
        }

        $config = \Setting::getConf($this->driver);
        if (!$config) return false;

        unset($config['order_num']);
        unset($config['status']);
        if ($config['email'] && $config['name']) {
            $config['from']['email'] = $config['email'];
            $config['from']['name'] = $config['name'];
        }
        unset($config['email']);
        unset($config['name']);

    }

    public function sendOne($target,$content,$title= '')
    {
        return $this->driver->send([$target],$content ,$title);
    }

    public function send(array $targets, $content,$title='')
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
