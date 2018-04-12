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

    public function send(array  $targets,$content,$title='')
    {
       return $this->_sendSms($targets,$content);
    }

    public function sendOne($target,$content,$title='')
    {
        return $this->send([$target] ,$content );
    }

    public function batchSend(array $target_contents)
    {
        return $this->_sendSms($target_contents);
    }

    public function _sendSms($target,$content)
    {
        $this->init();
        return $this->socket->send($target,$content);
    }

}