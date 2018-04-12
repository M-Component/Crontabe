<?php
namespace Component\Mailer;
/**
 *  发送邮件
 */

class Send
{

    private $driver;

    public function __construct()
    {
        $mailer = new \Mailer();
        $drivers = $mailer->getAll();

        // 获取被启用的配置项
        foreach ($drivers as $k => &$v) {
            if ($v['status'] == 'true') {
                $this->driver = $v['mailer_name'];
                break;
            }
        }
    }

    public function sendOne($target,$content,$title= '')
    {
        return $this->send([$target],$content ,$title);
    }

    public function sendBatch(array $target_contents)
    {

    }

    public function send(array $target, $content,$title)
    {
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

        $mailer = new \Phalcon\Mailer\Manager($config);
        $message = $mailer->createMessage()
            ->to($target[0], $target[0])
            ->subject($title)
            ->content($content);
        if (count($target)>1){
            foreach($target as $item){
                $message->bcc($item);
            }
        }
        if ($message->send()) {
            return true;
        }
        return false;
    }
}
