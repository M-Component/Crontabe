<?php
namespace Sender;
class App implements SenderInterface
{
    private $driver;    // 通道

    public function __construct()
    {
        $jpush_model = new \MsgPush();
        $drivers = $jpush_model->getAll();
        foreach ($drivers as &$v) {
            if ($v['status'] == 'true') {
                $this->driver = $jpush_model->getObj($v['driver_name']);
                return $v;
            }
        }
        return false;
    }

    public function send(array $targets, $content, $title = '', $extend_params = null)
    {
        return $this->driver->send($targets, $content, $title, $extend_params);
    }

    public function sendOne($target, $content, $title = '', $extend_params = null)
    {
        return $this->driver->sendOne($target, $content, $title, $extend_params);
    }

    public function sendList(array $target_contents)
    {
        return $this->driver->batchSend($target_contents);
    }

    public function createTask($params)
    {
        return $this->driver->createTask($params);
    }
}
