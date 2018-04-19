<?php

namespace Component\MsgPush;

interface MsgPushInterface{

    public function setting();
    public function send(array $registrationId,$message ,$title ,$extend_params = null);
    public function sendOne($registrationId,$message ,$title, $extend_params = null);
    public function batchSend(array $registrationid_alerts);
}