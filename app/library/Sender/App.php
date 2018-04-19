<?php
namespace Sender;
class App{
    private $driver;    // 通道

    public function __construct()
    {
        $jpush_model = new \MsgPush();
        $drivers = $jpush_model->getAll();
        foreach ($drivers as &$v) {
            if ($v['status'] == 'true') {
                $this->driver = $jpush_model->getObj($v['drive_name']);
                return $v;
            }
        }
        return false;
    }
    public function send(array $targets ,$content ,$title=''){
        return $this->driver->send($targets ,$content ,$title,$message);
    }

    public function sendOne($target ,$content ,$title=''){
        return $this->driver->sendOne($target ,$content ,$title,$message);
    }

    public function sendList(array $target_contents){
        return $this->driver->batchSend($target_contents);
    }


    public function createTask($params)
    {

    }
}
