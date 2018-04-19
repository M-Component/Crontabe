<?php

namespace Component\MsgPush;

interface MsgPushInterface{

    public function setting();
    public function send(array $registrationId,$message ,$extend_params = null);
    public function sendOne($registrationId,$message , $extend_params = null);
    public function batchSend(array $registrationid_alerts);
}
