<?php
namespace Component\MsgPush;

class Send{
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
    public function send(array $registrationId,$alert,$title,$message){
        return $this->driver->send($registrationId,$alert,$title,$message);
    }

    public function sendOne($registrationId,$alert,$title,$message){
        return $this->driver->sendOne($registrationId,$alert,$title,$message);
    }

    public function batchSend(array $registrationid_alerts){
        return $this->driver->batchSend($registrationid_alerts);
    }
}