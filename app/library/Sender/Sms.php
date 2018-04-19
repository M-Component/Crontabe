<?php
namespace Sender;

class Sms implements SenderInterface
{
    private $driver;    // 通道

    public function __construct()
    {
        $smsModel = new \Sms();
        $drivers = $smsModel->getAll(array('status'=>'true'));
        foreach ($drivers as &$v) {
            if ($v['status'] == 'true') {
                $this->driver = $smsModel->getObj($v['driver_name']);
                break;
            }
        }
    }

    public function send(array $targets, $content, $title = '',$extend_params =null)
    {
        return $this->driver->send($targets, $content);
    }

    public function sendOne($target, $content, $title = '', $extend_params =null)
    {
        return $this->driver->send([$target], $content);
    }

    public function sendList(array $target_contents)
    {
        return $this->driver->batchSend($target_contents);
    }

}
