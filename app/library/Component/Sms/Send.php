<?php
namespace Component\Sms;

class Send
{
    private $socket;    // 通道

    public function init($data = '')
    {
        $smsModel = new \Sms();
        $sockets= $smsModel->getAll();
        foreach ($sockets as &$v){
            if($v['status']=='true'){
                $this ->socket = $smsModel->getObj($v['sms_name']);
                return $v;
            }
        }
        return false;
    }


    public function send($target,$content)
    {
        $this->init();
        return $this->socket->send($target,$content);
    }
}
