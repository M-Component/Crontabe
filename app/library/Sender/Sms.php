<?php
namespace Sender;

class Sms implements SenderInterface
{
    private $socket;    // 通道

    public function __construct()
    {
        $smsModel = new \Sms();
        $sockets = $smsModel->getAll();
        foreach ($sockets as &$v) {
            if ($v['status'] == 'true') {
                $this->socket = $smsModel->getObj($v['sms_name']);
                break;
            }
        }
    }

    public function send(array $targets, $content, $title = '')
    {
        return $this->socket->send($targets, $content);
    }

    public function sendOne($target, $content, $title = '')
    {
        return $this->socket->send([$target], $content);
    }

    public function sendList(array $target_contents)
    {
        return $this->socket->batchsend($target_contents);
    }

}
