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

    public function send($target, $content,$title)
    {
        $config = \Setting::getConf($this->driver);
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
            ->to($target, $target)
            ->subject($title)
            ->content($content);

        if ($message->send()) {
            $msg = new \Message();
            $data['target'] = $target;
            $data['type'] = 2;
            $data['title'] = $title;
            $data['content'] = $message->getContent();
            $data['create_time'] = time();
            if ($msg->create($data)) {
                unset($msg);
                return true;
            }
        }
        return false;
    }
}
